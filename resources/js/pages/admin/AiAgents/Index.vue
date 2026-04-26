<script setup lang="ts">
import { Head, router, useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import AgentShareLinkController from '@/actions/App/Http/Controllers/Admin/AgentShareLinkController';
import AiAgentController from '@/actions/App/Http/Controllers/Admin/AiAgentController';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

interface AiAgent {
    id: number;
    name: string;
    slug: string;
    description?: string | null;
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
}>();

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

const createAgentForm = useForm({
    name: '',
    slug: '',
    description: '',
    provider: 'openai',
    model: 'gpt-4o-mini',
    is_active: true,
});

const editingAgentId = ref<number | null>(null);

const editAgentForm = useForm({
    name: '',
    slug: '',
    description: '',
    provider: '',
    model: '',
    is_active: true,
});

const shareLinkForm = useForm({
    label: '',
    expiry_type: 'fixed_datetime' as 'fixed_datetime' | 'relative_duration',
    expires_at: '',
    expires_in_minutes: '10080',
    usage_type: 'multi_use' as 'single_use' | 'multi_use',
    max_uses: '',
});

const submitAgent = (): void => {
    createAgentForm.post(AiAgentController.store.url(), {
        preserveScroll: true,
        onSuccess: () => {
            createAgentForm.reset('name', 'slug', 'description');
            createAgentForm.provider = 'openai';
            createAgentForm.model = 'gpt-4o-mini';
            createAgentForm.is_active = true;
        },
    });
};

const startEditAgent = (agent: AiAgent): void => {
    editingAgentId.value = agent.id;
    editAgentForm.clearErrors();
    editAgentForm.name = agent.name;
    editAgentForm.slug = agent.slug;
    editAgentForm.description = agent.description ?? '';
    editAgentForm.provider = agent.provider ?? '';
    editAgentForm.model = agent.model ?? '';
    editAgentForm.is_active = agent.is_active;
};

const cancelEditAgent = (): void => {
    editingAgentId.value = null;
    editAgentForm.reset();
    editAgentForm.clearErrors();
};

const submitAgentUpdate = (agentId: number): void => {
    editAgentForm.patch(AiAgentController.update.url(agentId), {
        preserveScroll: true,
        onSuccess: () => {
            cancelEditAgent();
        },
    });
};

const removeAgent = (agent: AiAgent): void => {
    if (!window.confirm(`Deseja remover o agente ${agent.name}?`)) {
        return;
    }

    router.delete(AiAgentController.destroy.url(agent.id), {
        preserveScroll: true,
    });
};

const toggleAgentActive = (agent: AiAgent): void => {
    router.patch(
        AiAgentController.update.url(agent.id),
        {
            is_active: !agent.is_active,
        },
        {
            preserveScroll: true,
        },
    );
};

const isEditingAgent = (agentId: number): boolean => editingAgentId.value === agentId;

const createShareLink = (agentId: number): void => {
    shareLinkForm
        .transform((data) => ({
            ...data,
            expires_at: data.expiry_type === 'fixed_datetime' ? data.expires_at || null : null,
            expires_in_minutes: data.expiry_type === 'relative_duration' ? Number(data.expires_in_minutes) : null,
            max_uses: data.max_uses ? Number(data.max_uses) : null,
        }))
        .post(AgentShareLinkController.store.url(agentId), {
            preserveScroll: true,
            onSuccess: () => {
                shareLinkForm.reset('label', 'expires_at', 'max_uses');
                shareLinkForm.expiry_type = 'fixed_datetime';
                shareLinkForm.expires_in_minutes = '10080';
                shareLinkForm.usage_type = 'multi_use';
            },
        });
};

const revokeShareLink = (id: number): void => {
    router.patch(AgentShareLinkController.revoke.url(id), {}, { preserveScroll: true });
};

const quickCreateSevenDayLink = (agentId: number): void => {
    router.post(
        AgentShareLinkController.store.url(agentId),
        {
            label: 'Link de teste 7 dias',
            expiry_type: 'relative_duration',
            expires_in_minutes: 60 * 24 * 7,
            usage_type: 'multi_use',
        },
        {
            preserveScroll: true,
        },
    );
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

    <div class="space-y-8 p-4">
        <Heading
            title="Gestão de Agentes IA"
            description="Cadastre agentes e gere links de teste com controle de expiração e rastreio."
        />

        <section class="rounded-xl border p-4 md:p-6">
            <h2 class="mb-4 text-lg font-semibold">Novo Agente</h2>

            <form class="grid gap-4" @submit.prevent="submitAgent">
                <div class="grid gap-2 md:grid-cols-2">
                    <div class="grid gap-2">
                        <Label for="name">Nome</Label>
                        <Input id="name" v-model="createAgentForm.name" placeholder="Ex: SDR Assistente" />
                        <InputError :message="createAgentForm.errors.name" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="slug">Slug (opcional)</Label>
                        <Input id="slug" v-model="createAgentForm.slug" placeholder="sdr-assistente" />
                        <InputError :message="createAgentForm.errors.slug" />
                    </div>
                </div>

                <div class="grid gap-2 md:grid-cols-2">
                    <div class="grid gap-2">
                        <Label for="provider">Provider</Label>
                        <Input id="provider" v-model="createAgentForm.provider" placeholder="openai" />
                        <InputError :message="createAgentForm.errors.provider" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="model">Model</Label>
                        <Input id="model" v-model="createAgentForm.model" placeholder="gpt-4o-mini" />
                        <InputError :message="createAgentForm.errors.model" />
                    </div>
                </div>

                <div class="grid gap-2">
                    <Label for="description">Descrição</Label>
                    <textarea
                        id="description"
                        v-model="createAgentForm.description"
                        class="min-h-20 rounded-md border px-3 py-2 text-sm"
                        placeholder="Descrição do agente"
                    />
                    <InputError :message="createAgentForm.errors.description" />
                </div>

                <div class="flex justify-end">
                    <Button :disabled="createAgentForm.processing" type="submit">
                        Criar agente
                    </Button>
                </div>
            </form>
        </section>

        <section class="rounded-xl border p-4 md:p-6">
            <h2 class="mb-4 text-lg font-semibold">Gerar Link de Teste</h2>

            <form class="grid gap-4 md:grid-cols-3" @submit.prevent="() => undefined">
                <div class="grid gap-2">
                    <Label for="share-label">Label</Label>
                    <Input id="share-label" v-model="shareLinkForm.label" placeholder="Ex: Teste lead abril" />
                    <InputError :message="shareLinkForm.errors.label" />
                </div>

                <div class="grid gap-2">
                    <Label for="expiry-type">Tipo de expiração</Label>
                    <select
                        id="expiry-type"
                        v-model="shareLinkForm.expiry_type"
                        class="h-10 rounded-md border px-3 text-sm"
                    >
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
                    <Input
                        v-else
                        id="expires-minutes"
                        v-model="shareLinkForm.expires_in_minutes"
                        type="number"
                        min="1"
                    />
                    <InputError
                        :message="shareLinkForm.errors.expires_at || shareLinkForm.errors.expires_in_minutes"
                    />
                </div>

                <div class="grid gap-2">
                    <Label for="usage-type">Uso</Label>
                    <select
                        id="usage-type"
                        v-model="shareLinkForm.usage_type"
                        class="h-10 rounded-md border px-3 text-sm"
                    >
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
            </form>

            <div class="mt-4 flex flex-wrap gap-2">
                <Button
                    v-for="agent in activeAgents"
                    :key="agent.id"
                    size="sm"
                    type="button"
                    variant="outline"
                    @click="createShareLink(agent.id)"
                >
                    Gerar para {{ agent.name }}
                </Button>
            </div>

            <div class="mt-3 flex flex-wrap gap-2">
                <Button
                    v-for="agent in activeAgents"
                    :key="`quick-${agent.id}`"
                    size="sm"
                    type="button"
                    @click="quickCreateSevenDayLink(agent.id)"
                >
                    Link rápido 7d: {{ agent.name }}
                </Button>
            </div>
        </section>

        <section class="rounded-xl border p-4 md:p-6">
            <h2 class="mb-4 text-lg font-semibold">Agentes Cadastrados</h2>

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
                                <div v-if="isEditingAgent(agent.id)" class="grid gap-2">
                                    <Input v-model="editAgentForm.name" placeholder="Nome" />
                                    <Input v-model="editAgentForm.slug" placeholder="slug" />
                                    <InputError :message="editAgentForm.errors.name || editAgentForm.errors.slug" />
                                </div>
                                <div v-else>
                                    <div class="font-medium">{{ agent.name }}</div>
                                    <div class="text-xs text-muted-foreground">/{{ agent.slug }}</div>
                                </div>
                            </td>
                            <td class="px-2 py-3">
                                <div v-if="isEditingAgent(agent.id)" class="grid gap-2">
                                    <Input v-model="editAgentForm.provider" placeholder="Provider" />
                                    <Input v-model="editAgentForm.model" placeholder="Model" />
                                    <InputError :message="editAgentForm.errors.provider || editAgentForm.errors.model" />
                                </div>
                                <span v-else>{{ agent.provider ?? '-' }} / {{ agent.model ?? '-' }}</span>
                            </td>
                            <td class="px-2 py-3">{{ agent.share_links_count }}</td>
                            <td class="px-2 py-3">
                                <Badge :variant="(isEditingAgent(agent.id) ? editAgentForm.is_active : agent.is_active) ? 'default' : 'outline'">
                                    {{ (isEditingAgent(agent.id) ? editAgentForm.is_active : agent.is_active) ? 'ativo' : 'inativo' }}
                                </Badge>
                                <label v-if="isEditingAgent(agent.id)" class="mt-2 flex items-center gap-2 text-xs">
                                    <input v-model="editAgentForm.is_active" type="checkbox" class="h-4 w-4" />
                                    Ativo
                                </label>
                            </td>
                            <td class="px-2 py-3">
                                <div v-if="isEditingAgent(agent.id)" class="flex flex-wrap gap-2">
                                    <Button
                                        size="sm"
                                        type="button"
                                        :disabled="editAgentForm.processing"
                                        @click="submitAgentUpdate(agent.id)"
                                    >
                                        Salvar
                                    </Button>
                                    <Button
                                        size="sm"
                                        type="button"
                                        variant="outline"
                                        @click="cancelEditAgent"
                                    >
                                        Cancelar
                                    </Button>
                                </div>
                                <div v-else class="flex flex-wrap gap-2">
                                    <Button
                                        size="sm"
                                        type="button"
                                        variant="outline"
                                        @click="startEditAgent(agent)"
                                    >
                                        Editar
                                    </Button>
                                    <Button
                                        size="sm"
                                        type="button"
                                        variant="outline"
                                        @click="toggleAgentActive(agent)"
                                    >
                                        {{ agent.is_active ? 'Desativar' : 'Ativar' }}
                                    </Button>
                                    <Button
                                        size="sm"
                                        type="button"
                                        variant="destructive"
                                        @click="removeAgent(agent)"
                                    >
                                        Excluir
                                    </Button>
                                </div>
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

        <section class="rounded-xl border p-4 md:p-6">
            <h2 class="mb-4 text-lg font-semibold">Links Recentes</h2>

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
                                <a
                                    class="text-xs text-blue-600 underline"
                                    :href="publicUrl(link.token)"
                                    target="_blank"
                                >
                                    {{ publicUrl(link.token) }}
                                </a>
                            </td>
                            <td class="px-2 py-3">
                                {{ link.used_count }}
                                <span v-if="link.max_uses !== null"> / {{ link.max_uses }}</span>
                            </td>
                            <td class="px-2 py-3">
                                <Badge
                                    :variant="
                                        linkStatus(link) === 'active'
                                            ? 'default'
                                            : linkStatus(link) === 'revoked'
                                              ? 'destructive'
                                              : 'outline'
                                    "
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
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</template>
