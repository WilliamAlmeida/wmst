<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { computed, nextTick, onMounted, ref } from 'vue';
import { Bot, Keyboard, Loader2, Lock, MessageCircle, Mic, MicOff, Send, ShieldCheck } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { useSpeechToText } from '@/composables/useSpeechToText';
import { vSpotlight } from '@/directives/spotlight';
import { renderMarkdown } from '@/lib/markdown';

type Locale = 'pt_BR' | 'es' | 'en';

interface ChatMessage {
    role: 'user' | 'assistant';
    content: string;
}

const props = defineProps<{
    locale: Locale;
    status: 'ok' | 'blocked' | 'invalid';
    message?: string;
    reason?: string;
    homeUrl: string;
    agent?: { id: number; name: string; slug: string; description?: string | null } | null;
    link?: { token: string; usage_type?: string; remaining_uses?: number | null; expires_at?: string | null; can_start?: boolean } | null;
}>();

const csrfToken = (document.querySelector('meta[name="csrf-token"]') as HTMLMetaElement | null)?.content ?? '';
const storageKey = computed(() => `wmst-agent-chat:${props.link?.token ?? ''}`);

type Phase = 'unavailable' | 'form' | 'screening' | 'chat' | 'finished';

const phase = ref<Phase>(props.status === 'ok' ? 'form' : 'unavailable');
const introOpen = ref(false);
const thanksOpen = ref(false);
const restoring = ref(props.status === 'ok');
const blockedMessage = ref<string | null>(null);

// Sem sessão salva: mostra o formulário se ainda há usos; senão, bloqueia
// (o link já atingiu o limite de acessos, mas quem já tem sessão pode retomar).
const enterFormOrBlock = (): void => {
    if (props.link?.can_start) {
        introOpen.value = true;

        return;
    }

    phase.value = 'unavailable';
    blockedMessage.value = 'Este link de demonstração já atingiu o limite de acessos.';
};

/* ---------------- Formulário de identificação ---------------- */

const form = ref({ name: '', phone: '+55 ', reason: '' });
const formErrors = ref<{ name?: string; phone?: string; reason?: string; general?: string }>({});

// Máscara de telefone brasileiro: +55 (DD) XXXXX-XXXX
const formatPhone = (event: Event): void => {
    const target = event.target as HTMLInputElement;
    let digits = target.value.replace(/\D/g, '');

    if (!digits.startsWith('55')) {
        digits = '55' + digits;
    }

    digits = digits.slice(0, 13); // 55 + até 11 dígitos

    const ddd = digits.slice(2, 4);
    const rest = digits.slice(4);

    let out = '+55';

    if (ddd) {
        out += ` (${ddd}`;
        if (ddd.length === 2) {
            out += ')';
        }
    }

    if (rest) {
        out += ' ';
        if (rest.length <= 4) {
            out += rest;
        } else if (rest.length <= 8) {
            out += `${rest.slice(0, 4)}-${rest.slice(4)}`;
        } else {
            out += `${rest.slice(0, 5)}-${rest.slice(5, 9)}`;
        }
    }

    form.value.phone = out;
};

const validateForm = (): boolean => {
    const errors: typeof formErrors.value = {};
    const name = form.value.name.trim();
    const phoneDigits = form.value.phone.replace(/[\s\(\)\-\.]/g, '');
    const reason = form.value.reason.trim();

    if (name.length < 6) {
        errors.name = 'Nome inválido ou muito curto.';
    }

    if (!/^\+55\d{10,11}$/.test(phoneDigits)) {
        errors.phone = 'Telefone inválido. Informe no formato +55 (DDD) número.';
    }

    if (reason.length < 100) {
        errors.reason = 'Motivo inválido ou muito curto.';
    }

    formErrors.value = errors;

    return Object.keys(errors).length === 0;
};

/* ---------------- Motivo: digitar ou falar ---------------- */

const reasonActive = ref(false);

const { supported: voiceSupported, listening, toggle: toggleVoice, stop: stopVoice } = useSpeechToText((text) => {
    const current = form.value.reason.trim();
    form.value.reason = current === '' ? text : `${current} ${text}`;
});

const startReasonTyping = (): void => {
    reasonActive.value = true;
};

const startReasonVoice = (): void => {
    reasonActive.value = true;

    if (!listening.value) {
        toggleVoice();
    }
};

/* ---------------- Estado do chat ---------------- */

const messages = ref<ChatMessage[]>([]);
const sessionToken = ref<string | null>(null);
const maxMessageLength = ref(500);
const remainingInteractions = ref<number | null>(null);
const draft = ref('');
const sending = ref(false);
const chatError = ref<string | null>(null);
const scrollArea = ref<HTMLElement | null>(null);

// Voz (Web Speech) direto no campo de mensagem do chat.
const {
    supported: draftVoiceSupported,
    listening: draftListening,
    toggle: toggleDraftVoice,
    stop: stopDraftVoice,
} = useSpeechToText((text) => {
    const current = draft.value.trim();
    draft.value = current === '' ? text : `${current} ${text}`;
});

const scrollToBottom = async (): Promise<void> => {
    await nextTick();
    scrollArea.value?.scrollTo({ top: scrollArea.value.scrollHeight, behavior: 'smooth' });
};

const api = async <T,>(method: 'GET' | 'POST', url: string, payload?: Record<string, unknown>): Promise<{ ok: boolean; status: number; body: T | null }> => {
    const response = await fetch(url, {
        method,
        headers: {
            'Content-Type': 'application/json',
            Accept: 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'X-Requested-With': 'XMLHttpRequest',
        },
        body: payload ? JSON.stringify(payload) : undefined,
    });

    const body = (await response.json().catch(() => null)) as T | null;

    return { ok: response.ok, status: response.status, body };
};

/* ---------------- Restaurar sessão (localStorage) ---------------- */

onMounted(async () => {
    if (props.status !== 'ok' || !props.link) {
        restoring.value = false;

        return;
    }

    const saved = localStorage.getItem(storageKey.value);

    if (!saved) {
        restoring.value = false;
        enterFormOrBlock();

        return;
    }

    const { ok, body } = await api<{
        status: 'active' | 'completed';
        max_message_length: number;
        remaining_interactions: number;
        messages: ChatMessage[];
    }>('GET', `/share/${props.link.token}/chat/${saved}`);

    restoring.value = false;

    if (!ok || !body) {
        localStorage.removeItem(storageKey.value);
        enterFormOrBlock();

        return;
    }

    sessionToken.value = saved;
    messages.value = body.messages;
    maxMessageLength.value = body.max_message_length;
    remainingInteractions.value = body.remaining_interactions;

    if (body.status === 'completed') {
        phase.value = 'finished';
        thanksOpen.value = true;
    } else {
        phase.value = 'chat';
    }

    await scrollToBottom();
});

/* ---------------- Iniciar sessão ---------------- */

const startSession = async (): Promise<void> => {
    if (!props.link || !validateForm()) {
        return;
    }

    stopVoice();
    phase.value = 'screening';
    formErrors.value = {};

    const { ok, status, body } = await api<{
        session_token: string;
        max_message_length: number;
        remaining_interactions: number;
        messages?: ChatMessage[];
        message?: string;
        errors?: Record<string, string[]>;
    }>('POST', `/share/${props.link.token}/chat`, {
        name: form.value.name,
        phone: form.value.phone,
        reason: form.value.reason,
    });

    if (!ok || !body || !('session_token' in body) || !body.session_token) {
        phase.value = 'form';

        if (status === 422 && body && 'errors' in body && body.errors) {
            formErrors.value = {
                name: body.errors.name?.[0],
                phone: body.errors.phone?.[0],
                reason: body.errors.reason?.[0],
            };
        }

        if (!Object.values(formErrors.value).some(Boolean)) {
            formErrors.value.general = body?.message ?? 'Não foi possível iniciar o teste agora. Tente novamente.';
        }

        return;
    }

    sessionToken.value = body.session_token;
    maxMessageLength.value = body.max_message_length;
    remainingInteractions.value = body.remaining_interactions;
    localStorage.setItem(storageKey.value, body.session_token);

    messages.value = body.messages ?? [];
    phase.value = 'chat';
    await scrollToBottom();
};

/* ---------------- Enviar mensagem ---------------- */

const sendMessage = async (): Promise<void> => {
    const text = draft.value.trim();

    if (text === '' || sending.value || !props.link || !sessionToken.value) {
        return;
    }

    stopDraftVoice();
    sending.value = true;
    chatError.value = null;
    messages.value.push({ role: 'user', content: text });
    draft.value = '';
    await scrollToBottom();

    let assistantIndex = -1;

    const restoreOnError = (message: string): void => {
        if (assistantIndex === -1) {
            messages.value.pop();
            draft.value = text;
        }
        chatError.value = message;
    };

    try {
        const response = await fetch(`/share/${props.link.token}/chat/${sessionToken.value}/message`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                Accept: 'application/json, text/event-stream',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: JSON.stringify({ message: text }),
        });

        const contentType = response.headers.get('content-type') ?? '';

        // Respostas não-stream (limite, validação, indisponível) vêm em JSON.
        if (!response.ok || !response.body || !contentType.includes('text/event-stream')) {
            const body = (await response.json().catch(() => null)) as { message?: string; limit_reached?: boolean } | null;

            if (response.status === 409 && body?.limit_reached) {
                phase.value = 'finished';
                thanksOpen.value = true;
            } else {
                restoreOnError(body?.message ?? 'Não foi possível enviar sua mensagem. Tente novamente.');
            }

            sending.value = false;

            return;
        }

        // ---- Consumo do SSE ----
        const reader = response.body.getReader();
        const decoder = new TextDecoder();
        let buffer = '';
        let done = false;

        while (!done) {
            const { value, done: streamDone } = await reader.read();

            if (streamDone) {
                break;
            }

            buffer += decoder.decode(value, { stream: true });
            const parts = buffer.split('\n\n');
            buffer = parts.pop() ?? '';

            for (const part of parts) {
                const line = part.replace(/^data:\s?/, '').trim();

                if (line === '' || line === '[DONE]') {
                    if (line === '[DONE]') {
                        done = true;
                    }

                    continue;
                }

                let payload: { delta?: string; error?: string; done?: boolean; remaining_interactions?: number; limit_reached?: boolean };

                try {
                    payload = JSON.parse(line);
                } catch {
                    continue;
                }

                if (payload.delta) {
                    if (assistantIndex === -1) {
                        messages.value.push({ role: 'assistant', content: '' });
                        assistantIndex = messages.value.length - 1;
                        sending.value = false;
                    }

                    messages.value[assistantIndex].content += payload.delta;
                    await scrollToBottom();
                } else if (payload.error) {
                    restoreOnError(payload.error);
                } else if (payload.done) {
                    if (typeof payload.remaining_interactions === 'number') {
                        remainingInteractions.value = payload.remaining_interactions;
                    }

                    if (payload.limit_reached) {
                        phase.value = 'finished';
                        thanksOpen.value = true;
                    }
                }
            }
        }
    } catch {
        restoreOnError('Falha de conexão. Tente novamente.');
    } finally {
        sending.value = false;
        await scrollToBottom();
    }
};

const onInputKeydown = (event: KeyboardEvent): void => {
    if (event.key === 'Enter' && !event.shiftKey) {
        event.preventDefault();
        void sendMessage();
    }
};

const unavailableTitle = computed(() => (props.status === 'invalid' ? 'Link inválido' : 'Link indisponível'));
</script>

<template>
    <Head :title="agent ? `Teste: ${agent.name}` : 'Teste de Agente IA'" />

    <div class="relative flex min-h-screen flex-col overflow-hidden bg-gradient-to-b from-[#16233F] via-[#1b2b4d] to-[#16233F] text-white">
        <!-- GRID BACKGROUND (efeito da home) -->
        <div
            class="pointer-events-none absolute inset-0 z-0 [background-image:linear-gradient(rgba(255,255,255,0.05)_1px,transparent_1px),linear-gradient(90deg,rgba(255,255,255,0.05)_1px,transparent_1px)] [background-size:40px_40px] [mask-image:radial-gradient(ellipse_at_top,black,transparent_75%)]"
        />
        <div
            class="pointer-events-none absolute inset-0 z-0 [background-image:radial-gradient(700px_circle_at_85%_-5%,rgba(53,194,74,0.14),transparent_45%)]"
        />

        <!-- HEADER MÍNIMO -->
        <header class="relative z-10 border-b border-white/10">
            <div class="mx-auto flex w-full max-w-3xl items-center gap-3 px-4 py-4">
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#35C24A]/15 text-[#35C24A]">
                    <Bot class="h-5 w-5" />
                </span>
                <div class="min-w-0">
                    <p class="truncate font-semibold">{{ agent?.name ?? 'Agente IA' }}</p>
                    <p class="text-xs text-white/60">Demonstração ao vivo · WMST</p>
                </div>
                <span
                    v-if="phase === 'chat' && remainingInteractions !== null"
                    class="ml-auto shrink-0 whitespace-nowrap rounded-full border border-white/15 px-2.5 py-1 text-[11px] text-white/70 sm:text-xs"
                >
                    {{ remainingInteractions }} <span class="hidden sm:inline">{{ remainingInteractions === 1 ? 'msg restante' : 'msgs restantes' }}</span><span class="sm:hidden"> restantes</span>
                </span>
            </div>
        </header>

        <!-- INDISPONÍVEL -->
        <main v-if="phase === 'unavailable'" class="relative z-10 flex flex-1 items-center justify-center px-4">
            <div
                v-spotlight
                class="spotlight-card w-full max-w-md rounded-2xl border border-white/10 bg-white/5 p-8 text-center"
            >
                <Lock class="mx-auto h-10 w-10 text-white/40" />
                <h1 class="mt-4 text-xl font-semibold">{{ unavailableTitle }}</h1>
                <p class="mt-2 text-sm text-white/70">{{ blockedMessage ?? message ?? 'Este link não está mais disponível.' }}</p>
                <a
                    :href="`${homeUrl}#trial`"
                    class="mt-6 inline-flex items-center gap-2 rounded-lg bg-[#35C24A] px-4 py-2 text-sm font-semibold text-[#16233F] hover:bg-[#2fae42]"
                >
                    Solicitar novo teste
                </a>
            </div>
        </main>

        <!-- CARREGANDO SESSÃO SALVA -->
        <main v-else-if="restoring" class="relative z-10 flex flex-1 items-center justify-center">
            <Loader2 class="h-8 w-8 animate-spin text-white/50" />
        </main>

        <!-- FORMULÁRIO DE IDENTIFICAÇÃO -->
        <main v-else-if="phase === 'form' || phase === 'screening'" class="relative z-10 flex flex-1 items-center justify-center px-4 py-8">
            <div
                v-spotlight
                class="spotlight-card w-full max-w-md rounded-2xl border border-white/10 bg-white p-6 text-zinc-900 shadow-2xl md:p-8"
            >
                <h1 class="text-xl font-bold">Antes de começar</h1>
                <p class="mt-1 text-sm text-zinc-600">
                    Preencha seus dados para liberar o teste de <strong>{{ agent?.name }}</strong>.
                </p>

                <form class="mt-5 grid gap-4" @submit.prevent="startSession">
                    <div class="grid gap-1.5">
                        <Label for="visitor-name">Nome</Label>
                        <Input id="visitor-name" v-model="form.name" placeholder="Seu nome completo" autocomplete="name" maxlength="100" />
                        <p v-if="formErrors.name" class="text-xs text-red-600">{{ formErrors.name }}</p>
                    </div>

                    <div class="grid gap-1.5">
                        <Label for="visitor-phone">Telefone (WhatsApp)</Label>
                        <input
                            id="visitor-phone"
                            :value="form.phone"
                            inputmode="tel"
                            autocomplete="tel"
                            placeholder="+55 (12) 98218-4879"
                            maxlength="19"

                            class="h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-xs outline-none focus-visible:border-ring focus-visible:ring-[3px] focus-visible:ring-ring/50"
                            @input="formatPhone"
                        />
                        <p v-if="formErrors.phone" class="text-xs text-red-600">{{ formErrors.phone }}</p>
                    </div>

                    <div class="grid gap-1.5">
                        <div class="flex flex-wrap items-center justify-between gap-2">
                            <Label for="visitor-reason">Por que você quer testar este agente?</Label>
                            <button
                                v-if="reasonActive && voiceSupported"
                                type="button"
                                class="inline-flex items-center gap-1.5 rounded-md border px-2 py-1 text-xs font-medium"
                                :class="listening ? 'border-red-400 bg-red-50 text-red-700' : 'border-zinc-300 text-zinc-700 hover:bg-zinc-100'"
                                @click="toggleVoice"
                            >
                                <component :is="listening ? MicOff : Mic" class="h-3.5 w-3.5" />
                                {{ listening ? 'Parar' : 'Falar' }}
                            </button>
                        </div>

                        <!-- Escolha inicial: digitar ou falar -->
                        <div v-if="!reasonActive" class="grid grid-cols-2 gap-2">
                            <button
                                type="button"
                                class="flex flex-col items-center justify-center gap-1.5 rounded-lg border border-zinc-300 py-4 text-sm font-medium text-zinc-700 transition hover:border-zinc-900 hover:bg-zinc-50"
                                @click="startReasonTyping"
                            >
                                <Keyboard class="h-5 w-5" />
                                Digitar
                            </button>
                            <button
                                type="button"
                                :disabled="!voiceSupported"
                                class="flex flex-col items-center justify-center gap-1.5 rounded-lg border border-zinc-300 py-4 text-sm font-medium text-zinc-700 transition hover:border-[#35C24A] hover:bg-[#35C24A]/5 disabled:cursor-not-allowed disabled:opacity-50"
                                @click="startReasonVoice"
                            >
                                <Mic class="h-5 w-5" />
                                Falar
                            </button>
                        </div>

                        <!-- Textarea (após escolher) -->
                        <template v-else>
                            <textarea
                                id="visitor-reason"
                                v-model="form.reason"
                                class="min-h-28 rounded-md border border-zinc-300 px-3 py-2 text-sm"
                                :placeholder="listening ? 'Pode falar… estou ouvindo.' : 'Conte um pouco sobre o seu interesse: seu negócio, o problema que quer resolver, o que espera ver na demonstração…'"
                            />
                            <p v-if="listening" class="flex items-center gap-1.5 text-xs text-[#1F8A30]">
                                <span class="relative flex h-2 w-2">
                                    <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-[#35C24A] opacity-75" />
                                    <span class="relative inline-flex h-2 w-2 rounded-full bg-[#35C24A]" />
                                </span>
                                Ouvindo… fale e o texto aparece acima.
                            </p>
                        </template>

                        <p v-if="formErrors.reason" class="text-xs text-red-600">{{ formErrors.reason }}</p>
                    </div>

                    <p v-if="formErrors.general" class="rounded-md bg-red-50 px-3 py-2 text-xs text-red-700">
                        {{ formErrors.general }}
                    </p>

                    <Button type="submit" class="w-full" :disabled="phase === 'screening'">
                        <Loader2 v-if="phase === 'screening'" class="h-4 w-4 animate-spin" />
                        {{ phase === 'screening' ? 'Validando suas informações…' : 'Iniciar teste' }}
                    </Button>

                    <p class="flex items-center justify-center gap-1.5 text-center text-[11px] text-zinc-500">
                        <ShieldCheck class="h-3.5 w-3.5" />
                        Suas informações são analisadas por IA antes de liberar o teste.
                    </p>
                </form>
            </div>
        </main>

        <!-- CHAT -->
        <main v-else class="relative z-10 mx-auto flex w-full max-w-3xl flex-1 flex-col px-4">
            <div ref="scrollArea" class="flex-1 space-y-3 overflow-y-auto py-6">
                <div v-if="messages.length === 0" class="flex h-full items-center justify-center">
                    <div class="text-center text-white/50">
                        <MessageCircle class="mx-auto h-8 w-8" />
                        <p class="mt-2 text-sm">Envie a primeira mensagem para começar a conversa.</p>
                    </div>
                </div>

                <div
                    v-for="(msg, index) in messages"
                    :key="index"
                    class="flex"
                    :class="msg.role === 'user' ? 'justify-end' : 'justify-start'"
                >
                    <div
                        v-if="msg.role === 'user'"
                        class="max-w-[85%] rounded-2xl bg-[#35C24A] px-4 py-2.5 text-sm whitespace-pre-wrap text-[#0d1a12]"
                    >
                        {{ msg.content }}
                    </div>
                    <div
                        v-else
                        class="chat-md max-w-[85%] rounded-2xl bg-white/10 px-4 py-2.5 text-sm text-white [&_a]:underline [&_code]:rounded [&_code]:bg-black/30 [&_code]:px-1 [&_li]:my-0.5 [&_ol]:list-decimal [&_ol]:pl-4 [&_p]:my-1 [&_pre]:my-2 [&_pre]:overflow-x-auto [&_pre]:rounded-lg [&_pre]:bg-black/40 [&_pre]:p-3 [&_ul]:list-disc [&_ul]:pl-4"
                        v-html="renderMarkdown(msg.content)"
                    />
                </div>

                <div v-if="sending" class="flex justify-start">
                    <div class="flex items-center gap-2 rounded-2xl bg-white/10 px-4 py-2.5 text-sm text-white/70">
                        <Loader2 class="h-3.5 w-3.5 animate-spin" />
                        pensando…
                    </div>
                </div>
            </div>

            <div class="sticky bottom-0 z-10 border-t border-white/10 bg-[#16233F]/95 py-4 backdrop-blur">
                <p v-if="chatError" class="mb-2 rounded-md bg-red-500/15 px-3 py-2 text-xs text-red-200">
                    {{ chatError }}
                </p>

                <div v-if="phase === 'finished'" class="rounded-xl border border-white/15 bg-white/5 px-4 py-3 text-center text-sm text-white/70">
                    Demonstração encerrada. Obrigado pelo seu tempo!
                </div>

                <form v-else class="flex gap-2" @submit.prevent="sendMessage">
                    <div class="flex-1 relative">
                        <div class="absolute -bottom-3 right-0 text-[10px] text-white/60">{{ draft.length }}/{{ maxMessageLength }}</div>
                        <textarea
                            v-model="draft"
                            :maxlength="maxMessageLength"
                            rows="1"
                            class="max-h-32 h-fit min-h-12 w-full resize-y rounded-lg border border-white/15 bg-white/10 px-4 py-2.5 text-sm text-white placeholder:text-white/40 focus:border-[#35C24A] focus:outline-none"
                            placeholder="Escreva sua mensagem…"
                            @keydown="onInputKeydown"
                        />
                    </div>
                    <Button
                        v-if="draftVoiceSupported"
                        type="button"
                        :title="draftListening ? 'Parar' : 'Falar'"
                        class="h-11 border border-white/15 bg-white/10 text-white hover:bg-white/20"
                        :class="draftListening ? 'border-red-400 bg-red-500/20 text-red-200' : ''"
                        @click="toggleDraftVoice"
                    >
                        <component :is="draftListening ? MicOff : Mic" class="h-4 w-4" />
                    </Button>
                    <Button
                        type="submit"
                        class="h-11 bg-[#35C24A] text-[#16233F] hover:bg-[#2fae42]"
                        :disabled="sending || draft.trim() === ''"
                    >
                        <Send class="h-4 w-4" />
                    </Button>
                </form>
            </div>
        </main>

        <!-- DIALOG INICIAL (AVISO) -->
        <Dialog :open="introOpen" @update:open="introOpen = $event">
            <DialogContent class="sm:max-w-md" :show-close-button="false">
                <DialogHeader>
                    <DialogTitle>Antes de iniciar o teste</DialogTitle>
                    <DialogDescription class="space-y-2 pt-2 text-left">
                        <span class="block">
                            As informações que você preencher (nome, telefone e motivo) serão usadas pela nossa equipe
                            para <strong>entrar em contato após o teste</strong>. Por isso, recomendamos preenchê-las corretamente.
                        </span>
                        <span class="block">
                            Uma IA irá analisar suas informações — se elas não parecerem confiáveis,
                            <strong>o teste não será iniciado</strong>.
                        </span>
                    </DialogDescription>
                </DialogHeader>
                <DialogFooter>
                    <Button type="button" class="w-full" @click="introOpen = false">Entendi, preencher meus dados</Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>

        <!-- MODAL DE AGRADECIMENTO (LIMITE ATINGIDO) -->
        <Dialog :open="thanksOpen" @update:open="thanksOpen = $event">
            <DialogContent class="sm:max-w-md">
                <DialogHeader>
                    <DialogTitle>Obrigado por testar! 🎉</DialogTitle>
                    <DialogDescription class="pt-2 text-left">
                        Você atingiu o limite de mensagens desta demonstração. Gostou do que viu?
                        <strong>Em breve nossa equipe entrará em contato</strong> pelo telefone informado para
                        conversar sobre como aplicar isso no seu negócio.
                    </DialogDescription>
                </DialogHeader>
                <DialogFooter>
                    <Button type="button" class="w-full" @click="thanksOpen = false">Fechar</Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    </div>
</template>
