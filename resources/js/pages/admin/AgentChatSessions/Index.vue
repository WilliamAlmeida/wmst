<script setup lang="ts">
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import AgentChatSessionController from '@/actions/App/Http/Controllers/Admin/AgentChatSessionController';
import Heading from '@/components/Heading.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';

interface SessionItem {
    id: number;
    visitor_name: string;
    visitor_phone: string;
    visitor_reason: string;
    screening_status: string;
    status: 'active' | 'completed';
    interactions_count: number;
    prompt_tokens: number;
    completion_tokens: number;
    messages_count: number;
    last_message_at: string | null;
    created_at: string;
    agent: { id: number; name: string } | null;
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
    sessions: Paginated<SessionItem>;
    agents: Array<{ id: number; name: string }>;
    filters: { agent?: number | string | null; status?: string | null };
    totals: { sessions: number; active: number; prompt_tokens: number; completion_tokens: number };
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

const filterForm = useForm({
    agent: props.filters.agent ? String(props.filters.agent) : '',
    status: props.filters.status ?? '',
});

const applyFilters = (): void => {
    router.get(
        AgentChatSessionController.index.url({
            query: {
                agent: filterForm.agent || undefined,
                status: filterForm.status || undefined,
            },
        }),
        {},
        { preserveScroll: true, preserveState: true, replace: true },
    );
};

const formatNumber = (value: number): string => new Intl.NumberFormat('pt-BR').format(value);

const formatDate = (value: string | null): string => {
    if (!value) {
        return '-';
    }

    return new Date(value).toLocaleString('pt-BR', { dateStyle: 'short', timeStyle: 'short' });
};
</script>

<template>
    <Head title="Sessões de Chat" />

    <div class="space-y-6 p-4">
        <Heading
            title="Sessões de Chat"
            description="CRM dos visitantes que testaram os agentes pelos links compartilhados."
        />

        <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
            <div class="rounded-xl border p-4">
                <p class="text-xs uppercase tracking-wide text-muted-foreground">Sessões</p>
                <p class="mt-1 text-2xl font-semibold">{{ formatNumber(totals.sessions) }}</p>
            </div>
            <div class="rounded-xl border p-4">
                <p class="text-xs uppercase tracking-wide text-muted-foreground">Ativas</p>
                <p class="mt-1 text-2xl font-semibold">{{ formatNumber(totals.active) }}</p>
            </div>
            <div class="rounded-xl border p-4">
                <p class="text-xs uppercase tracking-wide text-muted-foreground">Tokens de entrada</p>
                <p class="mt-1 text-2xl font-semibold">{{ formatNumber(totals.prompt_tokens) }}</p>
            </div>
            <div class="rounded-xl border p-4">
                <p class="text-xs uppercase tracking-wide text-muted-foreground">Tokens de saída</p>
                <p class="mt-1 text-2xl font-semibold">{{ formatNumber(totals.completion_tokens) }}</p>
            </div>
        </div>

        <section class="rounded-xl border p-4 md:p-6">
            <div class="mb-4 flex flex-wrap items-end gap-3">
                <div class="grid gap-1">
                    <Label for="filter-agent">Agente</Label>
                    <select id="filter-agent" v-model="filterForm.agent" class="h-10 rounded-md border px-3 text-sm">
                        <option value="">Todos</option>
                        <option v-for="agent in agents" :key="agent.id" :value="String(agent.id)">
                            {{ agent.name }}
                        </option>
                    </select>
                </div>

                <div class="grid gap-1">
                    <Label for="filter-status">Status</Label>
                    <select id="filter-status" v-model="filterForm.status" class="h-10 rounded-md border px-3 text-sm">
                        <option value="">Todos</option>
                        <option value="active">Ativa</option>
                        <option value="completed">Concluída</option>
                    </select>
                </div>

                <Button type="button" variant="outline" @click="applyFilters">Filtrar</Button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b text-left">
                            <th class="px-2 py-2 font-medium">Visitante</th>
                            <th class="px-2 py-2 font-medium">Agente</th>
                            <th class="px-2 py-2 font-medium">Interações</th>
                            <th class="px-2 py-2 font-medium">Tokens (in/out)</th>
                            <th class="px-2 py-2 font-medium">Última msg</th>
                            <th class="px-2 py-2 font-medium">Status</th>
                            <th class="px-2 py-2 font-medium">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="session in sessions.data" :key="session.id" class="border-b last:border-0">
                            <td class="px-2 py-3">
                                <div class="font-medium">{{ session.visitor_name }}</div>
                                <div class="text-xs text-muted-foreground">{{ session.visitor_phone }}</div>
                            </td>
                            <td class="px-2 py-3">{{ session.agent?.name ?? '-' }}</td>
                            <td class="px-2 py-3">{{ session.interactions_count }} ({{ session.messages_count }} msgs)</td>
                            <td class="px-2 py-3">
                                {{ formatNumber(session.prompt_tokens) }} / {{ formatNumber(session.completion_tokens) }}
                            </td>
                            <td class="px-2 py-3">{{ formatDate(session.last_message_at) }}</td>
                            <td class="px-2 py-3">
                                <Badge :variant="session.status === 'active' ? 'default' : 'outline'">
                                    {{ session.status === 'active' ? 'ativa' : 'concluída' }}
                                </Badge>
                            </td>
                            <td class="px-2 py-3">
                                <Link
                                    :href="AgentChatSessionController.show.url(session.id)"
                                    class="inline-flex items-center rounded-md border border-zinc-300 px-2.5 py-1.5 text-xs"
                                >
                                    Ver conversa
                                </Link>
                            </td>
                        </tr>
                        <tr v-if="sessions.data.length === 0">
                            <td colspan="7" class="px-2 py-8 text-center text-sm text-muted-foreground">
                                Nenhuma sessão registrada ainda.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="mt-4 flex flex-wrap gap-2">
                <button
                    v-for="link in sessions.links"
                    :key="link.label"
                    :disabled="!link.url"
                    class="rounded border px-3 py-1 text-xs"
                    :class="link.active ? 'border-black bg-black text-white' : ''"
                    v-html="link.label"
                    @click="link.url && router.visit(link.url)"
                />
            </div>
        </section>
    </div>
</template>
