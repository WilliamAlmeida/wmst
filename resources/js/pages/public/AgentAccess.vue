<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { vSpotlight } from '@/directives/spotlight';

type Locale = 'pt_BR' | 'es' | 'en';

defineProps<{
    locale: Locale;
    status: 'ok' | 'blocked' | 'invalid';
    message: string;
    reason?: string;
    homeUrl: string;
    agent?: { id: number; name: string; slug: string; description?: string | null } | null;
    link?: { token: string; usage_type?: string; remaining_uses?: number | null; expires_at?: string | null } | null;
}>();

const titleByStatus = {
    ok: 'Teste de Agente IA',
    blocked: 'Link indisponível',
    invalid: 'Link inválido',
};
</script>

<template>
    <Head :title="titleByStatus[status]">
        <meta name="description" :content="message" />
    </Head>

    <section class="mx-auto max-w-4xl px-4 py-14 md:px-8 md:py-20">
        <article class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-xl md:p-8">
            <p class="text-xs uppercase tracking-wide text-zinc-500">Agente IA</p>
            <h1 class="mt-2 text-3xl font-bold">{{ titleByStatus[status] }}</h1>
            <p class="mt-3 text-zinc-600">{{ message }}</p>

            <div v-if="status === 'ok' && agent" class="mt-6 grid gap-3 rounded-xl border border-brand-2/25 bg-brand-2-soft p-4">
                <p class="text-sm"><span class="font-semibold">Agente:</span> {{ agent.name }}</p>
                <p class="text-sm" v-if="link?.remaining_uses !== null && link?.remaining_uses !== undefined">
                    <span class="font-semibold">Usos restantes:</span> {{ link.remaining_uses }}
                </p>
                <p class="text-sm" v-if="link?.expires_at">
                    <span class="font-semibold">Expira em:</span> {{ link.expires_at }}
                </p>
            </div>

            <div v-else class="mt-6 rounded-xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-900">
                <p>Motivo: {{ reason ?? 'indisponível' }}</p>
            </div>

            <div class="mt-6 flex flex-wrap gap-3">
                <Link v-spotlight :href="homeUrl" class="spotlight-btn rounded-md bg-brand px-4 py-2 text-sm font-semibold text-white transition hover:bg-(--wmst-navy-700)">
                    Voltar para o site
                </Link>
                <a :href="`${homeUrl}#trial`" class="rounded-md border border-zinc-300 px-4 py-2 text-sm">
                    Solicitar novo teste
                </a>
            </div>
        </article>
    </section>
</template>
