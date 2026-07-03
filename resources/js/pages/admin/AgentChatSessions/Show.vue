<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ArrowLeft } from 'lucide-vue-next';
import AgentChatSessionController from '@/actions/App/Http/Controllers/Admin/AgentChatSessionController';
import Heading from '@/components/Heading.vue';
import { Badge } from '@/components/ui/badge';

interface ChatMessage {
    id: number;
    role: 'user' | 'assistant';
    content: string;
    created_at: string;
}

interface SessionDetail {
    id: number;
    visitor_name: string;
    visitor_phone: string;
    visitor_reason: string;
    screening_status: string;
    screening_notes: string | null;
    status: 'active' | 'completed';
    interactions_count: number;
    prompt_tokens: number;
    completion_tokens: number;
    last_message_at: string | null;
    created_at: string;
    agent: { id: number; name: string; provider: string | null; model: string | null; max_interactions: number } | null;
    share_link: { id: number; token: string; label: string | null } | null;
    messages: ChatMessage[];
}

defineProps<{
    session: SessionDetail;
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Sessões de Chat',
                href: AgentChatSessionController.index(),
            },
        ],
    },
});

const formatNumber = (value: number): string => new Intl.NumberFormat('pt-BR').format(value);

const formatDate = (value: string | null): string => {
    if (!value) {
        return '-';
    }

    return new Date(value).toLocaleString('pt-BR', { dateStyle: 'short', timeStyle: 'short' });
};
</script>

<template>
    <Head :title="`Sessão de ${session.visitor_name}`" />

    <div class="space-y-6 p-4">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <Heading
                :title="`Sessão de ${session.visitor_name}`"
                :description="`Conversa com ${session.agent?.name ?? 'agente removido'}.`"
            />
            <Link
                :href="AgentChatSessionController.index()"
                class="inline-flex items-center gap-1.5 rounded-md border border-zinc-300 px-3 py-2 text-sm font-medium hover:bg-zinc-100"
            >
                <ArrowLeft class="h-4 w-4" />
                Voltar
            </Link>
        </div>

        <div class="grid gap-6 lg:grid-cols-[320px_1fr]">
            <!-- CRM DO VISITANTE -->
            <aside class="space-y-4">
                <section class="rounded-xl border p-4">
                    <h2 class="text-sm font-semibold uppercase tracking-wide text-muted-foreground">Visitante</h2>
                    <dl class="mt-3 space-y-2 text-sm">
                        <div>
                            <dt class="text-xs text-muted-foreground">Nome</dt>
                            <dd class="font-medium">{{ session.visitor_name }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-muted-foreground">Telefone</dt>
                            <dd>
                                <a :href="`https://wa.me/${session.visitor_phone.replace('+', '')}`" target="_blank" rel="noopener" class="font-medium text-blue-600 underline">
                                    {{ session.visitor_phone }}
                                </a>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-xs text-muted-foreground">Motivo do teste</dt>
                            <dd class="whitespace-pre-wrap">{{ session.visitor_reason }}</dd>
                        </div>
                    </dl>
                </section>

                <section class="rounded-xl border p-4">
                    <h2 class="text-sm font-semibold uppercase tracking-wide text-muted-foreground">Sessão</h2>
                    <dl class="mt-3 space-y-2 text-sm">
                        <div class="flex items-center justify-between">
                            <dt class="text-xs text-muted-foreground">Status</dt>
                            <dd>
                                <Badge :variant="session.status === 'active' ? 'default' : 'outline'">
                                    {{ session.status === 'active' ? 'ativa' : 'concluída' }}
                                </Badge>
                            </dd>
                        </div>
                        <div class="flex items-center justify-between">
                            <dt class="text-xs text-muted-foreground">Interações</dt>
                            <dd class="font-medium">{{ session.interactions_count }} / {{ session.agent?.max_interactions ?? '-' }}</dd>
                        </div>
                        <div class="flex items-center justify-between">
                            <dt class="text-xs text-muted-foreground">Tokens entrada</dt>
                            <dd class="font-medium">{{ formatNumber(session.prompt_tokens) }}</dd>
                        </div>
                        <div class="flex items-center justify-between">
                            <dt class="text-xs text-muted-foreground">Tokens saída</dt>
                            <dd class="font-medium">{{ formatNumber(session.completion_tokens) }}</dd>
                        </div>
                        <div class="flex items-center justify-between">
                            <dt class="text-xs text-muted-foreground">Início</dt>
                            <dd>{{ formatDate(session.created_at) }}</dd>
                        </div>
                        <div class="flex items-center justify-between">
                            <dt class="text-xs text-muted-foreground">Última mensagem</dt>
                            <dd>{{ formatDate(session.last_message_at) }}</dd>
                        </div>
                        <div class="flex items-center justify-between">
                            <dt class="text-xs text-muted-foreground">Provider/Model</dt>
                            <dd class="text-right text-xs">{{ session.agent?.provider ?? '-' }}<br />{{ session.agent?.model ?? '-' }}</dd>
                        </div>
                        <div v-if="session.share_link" class="flex items-center justify-between">
                            <dt class="text-xs text-muted-foreground">Link</dt>
                            <dd class="text-xs">{{ session.share_link.label ?? session.share_link.token.slice(0, 12) + '…' }}</dd>
                        </div>
                    </dl>
                </section>

                <section class="rounded-xl border p-4">
                    <h2 class="text-sm font-semibold uppercase tracking-wide text-muted-foreground">Triagem</h2>
                    <p class="mt-2 text-sm">
                        <Badge variant="outline">{{ session.screening_status === 'ai' ? 'validado por IA' : 'heurística de código' }}</Badge>
                    </p>
                    <p v-if="session.screening_notes" class="mt-2 text-xs text-muted-foreground">{{ session.screening_notes }}</p>
                </section>
            </aside>

            <!-- TRANSCRIPT -->
            <section class="rounded-xl border p-4 md:p-6">
                <h2 class="mb-4 text-sm font-semibold uppercase tracking-wide text-muted-foreground">
                    Conversa ({{ session.messages.length }} mensagens)
                </h2>

                <div class="space-y-3">
                    <div
                        v-for="message in session.messages"
                        :key="message.id"
                        class="flex"
                        :class="message.role === 'user' ? 'justify-end' : 'justify-start'"
                    >
                        <div
                            class="max-w-[80%] rounded-2xl px-4 py-2.5 text-sm whitespace-pre-wrap"
                            :class="message.role === 'user' ? 'bg-zinc-900 text-white' : 'border bg-muted/40'"
                        >
                            <p class="mb-1 text-[10px] uppercase tracking-wide opacity-60">
                                {{ message.role === 'user' ? session.visitor_name : session.agent?.name ?? 'Agente' }}
                                · {{ formatDate(message.created_at) }}
                            </p>
                            {{ message.content }}
                        </div>
                    </div>

                    <p v-if="session.messages.length === 0" class="py-8 text-center text-sm text-muted-foreground">
                        Nenhuma mensagem trocada nesta sessão.
                    </p>
                </div>
            </section>
        </div>
    </div>
</template>
