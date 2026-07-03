<script setup lang="ts">
import { Head, router, useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import { Bot, Link2, Mic, MicOff, Pencil, Plus, Sparkles } from 'lucide-vue-next';
import AgentShareLinkController from '@/actions/App/Http/Controllers/Admin/AgentShareLinkController';
import AiAgentController from '@/actions/App/Http/Controllers/Admin/AiAgentController';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import { Badge } from '@/components/ui/badge';
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
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import { useSpeechToText } from '@/composables/useSpeechToText';

interface AiAgent {
    id: number;
    name: string;
    slug: string;
    description?: string | null;
    system_prompt?: string | null;
    provider: string | null;
    model: string | null;
    is_active: boolean;
    share_links_count: number;
}

interface ShareLink {
    id: number;
    ai_agent_id: number;
    token: string;
    usage_type: 'single_use' | 'multi_use';
    used_count: number;
    max_uses: number | null;
    expiry_type: 'fixed_datetime' | 'relative_duration';
    expires_at: string | null;
    expires_in_minutes: number | null;
    issued_at: string | null;
    revoked_at: string | null;
    created_at: string;
    agent: {
        id: number;
        name: string;
    };
}

interface PaginationLink {
    url: string | null;
    label: string;
    active: boolean;
}

interface Paginated<T> {
    data: T[];
    links: PaginationLink[];
}

const props = defineProps<{
    agents: Paginated<AiAgent>;
    recentShareLinks: ShareLink[];
    shareBaseUrl: string;
    providers: string[];
}>();

// Provedores suportados (config/ai.php) + o valor atual do agente, caso não esteja na lista.
const providerOptions = computed(() => {
    const current = agentForm.provider?.trim();
    const list = [...props.providers];

    if (current && !list.includes(current)) {
        list.unshift(current);
    }

    return list;
});

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Agentes IA',
                href: AiAgentController.index(),
            },
        ],
    },
});

const csrfToken = (document.querySelector('meta[name="csrf-token"]') as HTMLMetaElement | null)?.content ?? '';

/* ---------------- Agente: modal único de criar/editar ---------------- */

const agentModalOpen = ref(false);
const editingAgent = ref<AiAgent | null>(null);

const agentForm = useForm({
    name: '',
    slug: '',
    description: '',
    system_prompt: '',
    provider: 'openai',
    model: 'gpt-4o-mini',
    is_active: true,
});

const openCreateAgent = (): void => {
    editingAgent.value = null;
    agentForm.clearErrors();
    agentForm.reset();
    agentForm.provider = 'openai';
    agentForm.model = 'gpt-4o-mini';
    agentForm.is_active = true;
    agentModalOpen.value = true;
};

const openEditAgent = (agent: AiAgent): void => {
    editingAgent.value = agent;
    agentForm.clearErrors();
    agentForm.name = agent.name;
    agentForm.slug = agent.slug;
    agentForm.description = agent.description ?? '';
    agentForm.system_prompt = agent.system_prompt ?? '';
    agentForm.provider = agent.provider ?? '';
    agentForm.model = agent.model ?? '';
    agentForm.is_active = agent.is_active;
    agentModalOpen.value = true;
};

const submitAgentForm = (): void => {
    const onSuccess = (): void => {
        agentModalOpen.value = false;
        editingAgent.value = null;
        agentForm.reset();
    };

    if (editingAgent.value) {
        agentForm.patch(AiAgentController.update.url(editingAgent.value.id), {
            preserveScroll: true,
            onSuccess,
        });

        return;
    }

    agentForm.post(AiAgentController.store.url(), {
        preserveScroll: true,
        onSuccess,
    });
};

const removeAgent = (agent: AiAgent): void => {
    if (!window.confirm(`Deseja remover o agente ${agent.name}?`)) {
        return;
    }

    router.delete(AiAgentController.destroy.url(agent.id), { preserveScroll: true });
};

const toggleAgentActive = (agent: AiAgent): void => {
    router.patch(
        AiAgentController.update.url(agent.id),
        { is_active: !agent.is_active },
        { preserveScroll: true },
    );
};

/* ---------------- Voz (Web Speech) no system_prompt ---------------- */

const { supported: voiceSupported, listening, toggle: toggleVoice } = useSpeechToText((text) => {
    const current = agentForm.system_prompt.trim();
    agentForm.system_prompt = current === '' ? text : `${current} ${text}`;
});

/* ---------------- Melhoria de prompt via IA ---------------- */

const improving = ref(false);
const improveError = ref<string | null>(null);
const improveModalOpen = ref(false);
const improveResult = ref<{ improved_prompt: string; summary: string } | null>(null);

const improvePrompt = async (): Promise<void> => {
    if (agentForm.system_prompt.trim() === '') {
        improveError.value = 'Escreva um prompt antes de pedir a melhoria.';

        return;
    }

    improving.value = true;
    improveError.value = null;

    try {
        const response = await fetch('/dashboard/ai-agents/improve-prompt', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                Accept: 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: JSON.stringify({
                current_prompt: agentForm.system_prompt,
                name: agentForm.name || undefined,
            }),
        });

        const body = (await response.json().catch(() => null)) as
            | { improved_prompt: string; summary: string }
            | { message?: string }
            | null;

        if (!response.ok || !body || !('improved_prompt' in body)) {
            throw new Error((body as { message?: string } | null)?.message ?? 'Não foi possível melhorar o prompt.');
        }

        improveResult.value = body;
        improveModalOpen.value = true;
    } catch (error) {
        improveError.value = error instanceof Error ? error.message : 'Falha ao melhorar o prompt.';
    } finally {
        improving.value = false;
    }
};

const applyImproved = (mode: 'append' | 'replace'): void => {
    if (!improveResult.value) {
        return;
    }

    const improved = improveResult.value.improved_prompt.trim();

    if (mode === 'replace') {
        agentForm.system_prompt = improved;
    } else {
        const current = agentForm.system_prompt.trim();
        agentForm.system_prompt = current === '' ? improved : `${current}\n\n${improved}`;
    }

    improveModalOpen.value = false;
    improveResult.value = null;
};

/* ---------------- Links de teste ---------------- */

const selectedAgentId = ref<string>('');

const shareLinkForm = useForm({
    label: '',
    expiry_type: 'fixed_datetime' as 'fixed_datetime' | 'relative_duration',
    expires_at: '',
    expires_in_minutes: '10080',
    usage_type: 'multi_use' as 'single_use' | 'multi_use',
    max_uses: '',
});

const createShareLink = (): void => {
    if (selectedAgentId.value === '') {
        return;
    }

    shareLinkForm
        .transform((data) => ({
            ...data,
            expires_at: data.expiry_type === 'fixed_datetime' ? data.expires_at || null : null,
            expires_in_minutes: data.expiry_type === 'relative_duration' ? Number(data.expires_in_minutes) : null,
            max_uses: data.max_uses ? Number(data.max_uses) : null,
        }))
        .post(AgentShareLinkController.store.url(Number(selectedAgentId.value)), {
            preserveScroll: true,
            onSuccess: () => {
                shareLinkForm.reset('label', 'expires_at', 'max_uses');
                shareLinkForm.expiry_type = 'fixed_datetime';
                shareLinkForm.expires_in_minutes = '10080';
                shareLinkForm.usage_type = 'multi_use';
            },
        });
};

const quickCreateSevenDayLink = (): void => {
    if (selectedAgentId.value === '') {
        return;
    }

    router.post(
        AgentShareLinkController.store.url(Number(selectedAgentId.value)),
        {
            label: 'Link de teste 7 dias',
            expiry_type: 'relative_duration',
            expires_in_minutes: 60 * 24 * 7,
            usage_type: 'multi_use',
        },
        { preserveScroll: true },
    );
};

const revokeShareLink = (id: number): void => {
    router.patch(AgentShareLinkController.revoke.url(id), {}, { preserveScroll: true });
};

const publicUrl = (token: string): string => `${props.shareBaseUrl}/${token}`;

const linkStatus = (link: ShareLink): 'revoked' | 'expired' | 'active' => {
    if (link.revoked_at) {
        return 'revoked';
    }

    if (link.expiry_type === 'fixed_datetime' && link.expires_at) {
        if (new Date(link.expires_at).getTime() <= Date.now()) {
            return 'expired';
        }
    }

    if (link.usage_type === 'single_use' && link.used_count >= 1) {
        return 'expired';
    }

    if (link.max_uses !== null && link.used_count >= link.max_uses) {
        return 'expired';
    }

    return 'active';
};

const activeAgents = computed(() => props.agents.data.filter((agent) => agent.is_active));
</script>

<template>
    <Head title="Agentes IA" />

    <div class="space-y-6 p-4">
        <Heading
            title="Gestão de Agentes IA"
            description="Cadastre agentes e gere links de teste com controle de expiração e rastreio."
        />

        <Tabs default-value="agents">
            <TabsList>
                <TabsTrigger value="agents">
                    <Bot class="h-4 w-4" />
                    Agentes
                </TabsTrigger>
                <TabsTrigger value="links">
                    <Link2 class="h-4 w-4" />
                    Links de teste
                </TabsTrigger>
            </TabsList>

            <!-- TAB: AGENTES -->
            <TabsContent value="agents" class="space-y-4">
                <div class="flex items-center justify-between gap-3">
                    <p class="text-sm text-muted-foreground">
                        {{ agents.data.length }} agente(s) nesta página.
                    </p>
                    <Button type="button" @click="openCreateAgent">
                        <Plus class="h-4 w-4" />
                        Novo agente
                    </Button>
                </div>

                <section class="rounded-xl border p-4 md:p-6">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b text-left">
                                    <th class="px-2 py-2 font-medium">Agente</th>
                                    <th class="px-2 py-2 font-medium">Provider/Model</th>
                                    <th class="px-2 py-2 font-medium">Links</th>
                                    <th class="px-2 py-2 font-medium">Status</th>
                                    <th class="px-2 py-2 font-medium">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="agent in agents.data" :key="agent.id" class="border-b last:border-0">
                                    <td class="px-2 py-3">
                                        <div class="font-medium">{{ agent.name }}</div>
                                        <div class="text-xs text-muted-foreground">/{{ agent.slug }}</div>
                                    </td>
                                    <td class="px-2 py-3">{{ agent.provider ?? '-' }} / {{ agent.model ?? '-' }}</td>
                                    <td class="px-2 py-3">{{ agent.share_links_count }}</td>
                                    <td class="px-2 py-3">
                                        <Badge :variant="agent.is_active ? 'default' : 'outline'">
                                            {{ agent.is_active ? 'ativo' : 'inativo' }}
                                        </Badge>
                                    </td>
                                    <td class="px-2 py-3">
                                        <div class="flex flex-wrap gap-2">
                                            <Button size="sm" type="button" variant="outline" @click="openEditAgent(agent)">
                                                <Pencil class="h-3.5 w-3.5" />
                                                Editar
                                            </Button>
                                            <Button size="sm" type="button" variant="outline" @click="toggleAgentActive(agent)">
                                                {{ agent.is_active ? 'Desativar' : 'Ativar' }}
                                            </Button>
                                            <Button size="sm" type="button" variant="destructive" @click="removeAgent(agent)">
                                                Excluir
                                            </Button>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-if="agents.data.length === 0">
                                    <td colspan="5" class="px-2 py-8 text-center text-sm text-muted-foreground">
                                        Nenhum agente ainda. Clique em “Novo agente” para começar.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4 flex flex-wrap gap-2">
                        <button
                            v-for="link in agents.links"
                            :key="link.label"
                            :disabled="!link.url"
                            class="rounded border px-3 py-1 text-xs"
                            :class="link.active ? 'border-black bg-black text-white' : ''"
                            v-html="link.label"
                            @click="link.url && router.visit(link.url)"
                        />
                    </div>
                </section>
            </TabsContent>

            <!-- TAB: LINKS DE TESTE -->
            <TabsContent value="links" class="space-y-4">
                <section class="rounded-xl border p-4 md:p-6">
                    <h2 class="mb-4 text-lg font-semibold">Gerar link de teste</h2>

                    <div class="grid gap-4 md:grid-cols-3">
                        <div class="grid gap-2 md:col-span-3">
                            <Label for="share-agent">Agente</Label>
                            <select id="share-agent" v-model="selectedAgentId" class="h-10 rounded-md border px-3 text-sm">
                                <option value="">Selecione um agente…</option>
                                <option v-for="agent in activeAgents" :key="agent.id" :value="String(agent.id)">
                                    {{ agent.name }}
                                </option>
                            </select>
                            <p v-if="activeAgents.length === 0" class="text-xs text-muted-foreground">
                                Nenhum agente ativo. Ative ou crie um agente na aba “Agentes”.
                            </p>
                        </div>

                        <div class="grid gap-2">
                            <Label for="share-label">Label</Label>
                            <Input id="share-label" v-model="shareLinkForm.label" placeholder="Ex: Teste lead abril" />
                            <InputError :message="shareLinkForm.errors.label" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="expiry-type">Tipo de expiração</Label>
                            <select id="expiry-type" v-model="shareLinkForm.expiry_type" class="h-10 rounded-md border px-3 text-sm">
                                <option value="fixed_datetime">Data/hora fixa</option>
                                <option value="relative_duration">Duração relativa</option>
                            </select>
                            <InputError :message="shareLinkForm.errors.expiry_type" />
                        </div>

                        <div class="grid gap-2">
                            <Label v-if="shareLinkForm.expiry_type === 'fixed_datetime'" for="expires-at">Expira em</Label>
                            <Label v-else for="expires-minutes">Duração (minutos)</Label>
                            <Input
                                v-if="shareLinkForm.expiry_type === 'fixed_datetime'"
                                id="expires-at"
                                v-model="shareLinkForm.expires_at"
                                type="datetime-local"
                            />
                            <Input v-else id="expires-minutes" v-model="shareLinkForm.expires_in_minutes" type="number" min="1" />
                            <InputError :message="shareLinkForm.errors.expires_at || shareLinkForm.errors.expires_in_minutes" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="usage-type">Uso</Label>
                            <select id="usage-type" v-model="shareLinkForm.usage_type" class="h-10 rounded-md border px-3 text-sm">
                                <option value="multi_use">Múltiplos acessos</option>
                                <option value="single_use">Uso único</option>
                            </select>
                            <InputError :message="shareLinkForm.errors.usage_type" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="max-uses">Max usos (opcional)</Label>
                            <Input id="max-uses" v-model="shareLinkForm.max_uses" type="number" min="1" />
                            <InputError :message="shareLinkForm.errors.max_uses" />
                        </div>
                    </div>

                    <div class="mt-4 flex flex-wrap gap-2">
                        <Button type="button" :disabled="selectedAgentId === '' || shareLinkForm.processing" @click="createShareLink">
                            Gerar link
                        </Button>
                        <Button type="button" variant="outline" :disabled="selectedAgentId === ''" @click="quickCreateSevenDayLink">
                            Link rápido 7 dias
                        </Button>
                    </div>
                </section>

                <section class="rounded-xl border p-4 md:p-6">
                    <h2 class="mb-4 text-lg font-semibold">Links recentes</h2>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b text-left">
                                    <th class="px-2 py-2 font-medium">Agente</th>
                                    <th class="px-2 py-2 font-medium">URL</th>
                                    <th class="px-2 py-2 font-medium">Uso</th>
                                    <th class="px-2 py-2 font-medium">Status</th>
                                    <th class="px-2 py-2 font-medium">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="link in recentShareLinks" :key="link.id" class="border-b last:border-0">
                                    <td class="px-2 py-3">{{ link.agent.name }}</td>
                                    <td class="px-2 py-3">
                                        <a class="text-xs text-blue-600 underline" :href="publicUrl(link.token)" target="_blank">
                                            {{ publicUrl(link.token) }}
                                        </a>
                                    </td>
                                    <td class="px-2 py-3">
                                        {{ link.used_count }}
                                        <span v-if="link.max_uses !== null"> / {{ link.max_uses }}</span>
                                    </td>
                                    <td class="px-2 py-3">
                                        <Badge
                                            :variant="linkStatus(link) === 'active' ? 'default' : linkStatus(link) === 'revoked' ? 'destructive' : 'outline'"
                                        >
                                            {{ linkStatus(link) }}
                                        </Badge>
                                    </td>
                                    <td class="px-2 py-3">
                                        <Button
                                            v-if="linkStatus(link) === 'active'"
                                            size="sm"
                                            type="button"
                                            variant="destructive"
                                            @click="revokeShareLink(link.id)"
                                        >
                                            Revogar
                                        </Button>
                                    </td>
                                </tr>
                                <tr v-if="recentShareLinks.length === 0">
                                    <td colspan="5" class="px-2 py-8 text-center text-sm text-muted-foreground">
                                        Nenhum link gerado ainda.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </section>
            </TabsContent>
        </Tabs>

        <!-- MODAL: CRIAR / EDITAR AGENTE -->
        <Dialog :open="agentModalOpen" @update:open="agentModalOpen = $event">
            <DialogContent class="max-h-[90vh] overflow-y-auto sm:max-w-2xl">
                <DialogHeader>
                    <DialogTitle>{{ editingAgent ? 'Editar agente' : 'Novo agente' }}</DialogTitle>
                    <DialogDescription>
                        Defina o comportamento do agente. O prompt do sistema é o que orienta as respostas.
                    </DialogDescription>
                </DialogHeader>

                <form class="grid gap-4" @submit.prevent="submitAgentForm">
                    <div class="grid gap-2 md:grid-cols-2">
                        <div class="grid gap-2">
                            <Label for="agent-name">Nome</Label>
                            <Input id="agent-name" v-model="agentForm.name" placeholder="Ex: SDR Assistente" />
                            <InputError :message="agentForm.errors.name" />
                        </div>
                        <div class="grid gap-2">
                            <Label for="agent-slug">Slug (opcional)</Label>
                            <Input id="agent-slug" v-model="agentForm.slug" placeholder="sdr-assistente" />
                            <InputError :message="agentForm.errors.slug" />
                        </div>
                    </div>

                    <div class="grid gap-2 md:grid-cols-2">
                        <div class="grid gap-2">
                            <Label for="agent-provider">Provider</Label>
                            <select id="agent-provider" v-model="agentForm.provider" class="h-10 rounded-md border px-3 text-sm">
                                <option v-for="provider in providerOptions" :key="provider" :value="provider">
                                    {{ provider }}
                                </option>
                            </select>
                            <InputError :message="agentForm.errors.provider" />
                        </div>
                        <div class="grid gap-2">
                            <Label for="agent-model">Model</Label>
                            <Input id="agent-model" v-model="agentForm.model" placeholder="Ex: gpt-4o-mini, nvidia/nemotron-3-ultra-550b-a55b:free" />
                            <InputError :message="agentForm.errors.model" />
                        </div>
                    </div>

                    <div class="grid gap-2">
                        <Label for="agent-description">Descrição (resumo curto)</Label>
                        <textarea
                            id="agent-description"
                            v-model="agentForm.description"
                            class="min-h-16 rounded-md border px-3 py-2 text-sm"
                            placeholder="Resumo do que o agente faz"
                        />
                        <InputError :message="agentForm.errors.description" />
                    </div>

                    <div class="grid gap-2">
                        <div class="flex flex-wrap items-center justify-between gap-2">
                            <Label for="agent-system-prompt">Prompt do sistema</Label>
                            <div class="flex items-center gap-2">
                                <Button
                                    v-if="voiceSupported"
                                    type="button"
                                    size="sm"
                                    :variant="listening ? 'destructive' : 'outline'"
                                    @click="toggleVoice"
                                >
                                    <component :is="listening ? MicOff : Mic" class="h-3.5 w-3.5" />
                                    {{ listening ? 'Ouvindo… parar' : 'Ditar' }}
                                </Button>
                                <Button type="button" size="sm" variant="outline" :disabled="improving" @click="improvePrompt">
                                    <Sparkles class="h-3.5 w-3.5" />
                                    {{ improving ? 'Melhorando…' : 'Melhorar prompt' }}
                                </Button>
                            </div>
                        </div>
                        <textarea
                            id="agent-system-prompt"
                            v-model="agentForm.system_prompt"
                            class="min-h-48 rounded-md border px-3 py-2 font-mono text-sm"
                            placeholder="Você é um assistente da WMST. Seu papel é… Tom de voz… Objetivos… Quando não souber, encaminhe para um humano."
                        />
                        <p v-if="!voiceSupported" class="text-xs text-muted-foreground">
                            Ditado por voz indisponível neste navegador (use Chrome ou Edge).
                        </p>
                        <p v-if="improveError" class="text-xs text-red-600">{{ improveError }}</p>
                        <InputError :message="agentForm.errors.system_prompt" />
                    </div>

                    <label class="flex items-center gap-2 text-sm">
                        <input v-model="agentForm.is_active" type="checkbox" class="h-4 w-4" />
                        Agente ativo
                    </label>

                    <DialogFooter>
                        <Button type="button" variant="outline" @click="agentModalOpen = false">Cancelar</Button>
                        <Button type="submit" :disabled="agentForm.processing">
                            {{ editingAgent ? 'Salvar alterações' : 'Criar agente' }}
                        </Button>
                    </DialogFooter>
                </form>
            </DialogContent>
        </Dialog>

        <!-- MODAL: RESULTADO DA MELHORIA DE PROMPT -->
        <Dialog :open="improveModalOpen" @update:open="improveModalOpen = $event">
            <DialogContent class="max-h-[90vh] overflow-y-auto sm:max-w-2xl">
                <DialogHeader>
                    <DialogTitle>Prompt melhorado</DialogTitle>
                    <DialogDescription>
                        {{ improveResult?.summary || 'Revise a sugestão e escolha como aplicar.' }}
                    </DialogDescription>
                </DialogHeader>

                <textarea
                    :value="improveResult?.improved_prompt"
                    readonly
                    class="min-h-64 w-full rounded-md border bg-muted/40 px-3 py-2 font-mono text-sm"
                />

                <DialogFooter>
                    <Button type="button" variant="outline" @click="improveModalOpen = false">Cancelar</Button>
                    <Button type="button" variant="outline" @click="applyImproved('replace')">Substituir o atual</Button>
                    <Button type="button" @click="applyImproved('append')">Adicionar após o atual</Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    </div>
</template>
