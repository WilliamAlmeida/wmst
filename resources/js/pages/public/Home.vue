<script setup lang="ts">
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import TrialSignupController from '@/actions/App/Http/Controllers/Public/TrialSignupController';

type Locale = 'pt_BR' | 'es' | 'en';

interface AgentItem {
    id: number;
    name: string;
    slug: string;
    description: string | null;
}

const props = defineProps<{
    locale: Locale;
    alternateUrls: Record<Locale, string>;
    blogUrls: Record<Locale, string>;
    activeAgents: AgentItem[];
    metrics: Array<{ label: string; value: string }>;
}>();

const page = usePage<{ flash?: { trialSignup?: { status: string; url: string; agent: string } } }>();

const copy: Record<Locale, { title: string; subtitle: string; cta: string; blog: string; formTitle: string }> = {
    pt_BR: {
        title: 'Solucoes digitais para acelerar vendas e operacao',
        subtitle: 'Unimos estrategia, IA aplicada e engenharia para transformar visitas em clientes.',
        cta: 'Solicitar teste com agente IA',
        blog: 'Ver conteudos do blog',
        formTitle: 'Peca seu link de teste',
    },
    es: {
        title: 'Soluciones digitales para acelerar ventas y operacion',
        subtitle: 'Combinamos estrategia, IA aplicada e ingenieria para convertir visitas en clientes.',
        cta: 'Solicitar prueba con agente IA',
        blog: 'Ver contenidos del blog',
        formTitle: 'Solicita tu enlace de prueba',
    },
    en: {
        title: 'Digital solutions to accelerate sales and operations',
        subtitle: 'We combine strategy, applied AI, and engineering to turn visitors into customers.',
        cta: 'Request AI agent trial',
        blog: 'Read our blog',
        formTitle: 'Request your trial link',
    },
};

const trialForm = useForm({
    name: '',
    email: '',
    company: '',
    phone: '',
    product: 'custom',
    ai_agent_slug: '',
    message: '',
    consent_accepted: false,
});

const submitTrial = (): void => {
    const action =
        props.locale === 'pt_BR'
            ? TrialSignupController.store['/trial-signups']()
            : TrialSignupController.store['/{locale}/trial-signups']({ locale: props.locale });

    trialForm.post(action.url, {
        preserveScroll: true,
    });
};

const organizationSchema = JSON.stringify({
    '@context': 'https://schema.org',
    '@type': 'Organization',
    name: 'WMST',
    url: props.alternateUrls.pt_BR,
    sameAs: [props.alternateUrls.pt_BR],
    description: copy[props.locale].subtitle,
});

const serviceSchema = JSON.stringify({
    '@context': 'https://schema.org',
    '@type': 'Service',
    serviceType: 'Desenvolvimento de software, automacao e IA aplicada',
    provider: {
        '@type': 'Organization',
        name: 'WMST',
    },
});
</script>

<template>
    <Head :title="copy[locale].title">
        <meta name="description" :content="copy[locale].subtitle" />
        <link rel="canonical" :href="alternateUrls[locale]" />
        <link rel="alternate" hreflang="pt-BR" :href="alternateUrls.pt_BR" />
        <link rel="alternate" hreflang="es" :href="alternateUrls.es" />
        <link rel="alternate" hreflang="en" :href="alternateUrls.en" />
        <link rel="alternate" hreflang="x-default" :href="alternateUrls.pt_BR" />
        <script type="application/ld+json" v-html="organizationSchema" />
        <script type="application/ld+json" v-html="serviceSchema" />
    </Head>

    <section class="mx-auto grid max-w-7xl gap-8 px-4 py-12 md:grid-cols-[1.2fr_0.8fr] md:px-8 md:py-20">
        <div class="space-y-6">
            <span class="inline-flex rounded-full border border-amber-300 bg-amber-100 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-amber-900">
                AI + Product + Growth
            </span>
            <h1 class="text-3xl font-bold leading-tight md:text-5xl">
                {{ copy[locale].title }}
            </h1>
            <p class="max-w-2xl text-base text-zinc-600 md:text-lg">
                {{ copy[locale].subtitle }}
            </p>

            <div class="flex flex-wrap gap-3">
                <a href="#trial" class="rounded-md bg-zinc-900 px-5 py-2.5 text-sm font-medium text-white hover:bg-zinc-700">
                    {{ copy[locale].cta }}
                </a>
                <Link :href="blogUrls[locale]" class="rounded-md border border-zinc-300 px-5 py-2.5 text-sm font-medium hover:bg-white">
                    {{ copy[locale].blog }}
                </Link>
            </div>

            <div class="grid gap-3 pt-2 sm:grid-cols-2 lg:grid-cols-4">
                <article v-for="metric in metrics" :key="metric.label" class="rounded-xl border border-zinc-200 bg-white p-4 shadow-sm">
                    <p class="text-2xl font-bold">{{ metric.value }}</p>
                    <p class="text-xs uppercase tracking-wide text-zinc-500">{{ metric.label }}</p>
                </article>
            </div>
        </div>

        <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-xl">
            <h2 class="text-lg font-semibold">Produtos e frentes</h2>
            <ul class="mt-4 grid gap-3 text-sm text-zinc-700">
                <li class="rounded-lg border border-zinc-200 p-3">Agenda Clinic: gestao para clinicas e operacao de atendimento.</li>
                <li class="rounded-lg border border-zinc-200 p-3">IBOX Delivery: pedidos, entrega e retencao para restaurantes.</li>
                <li class="rounded-lg border border-zinc-200 p-3">Conecta: automacao de marketing, CRM e social selling.</li>
            </ul>
        </div>
    </section>

    <section id="trial" class="mx-auto max-w-7xl px-4 pb-16 md:px-8 md:pb-24">
        <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-xl md:p-8">
            <div class="mb-6 flex flex-col gap-2">
                <h2 class="text-2xl font-bold">{{ copy[locale].formTitle }}</h2>
                <p class="text-sm text-zinc-600">Receba um link expirável e rastreável para testar um agente em produção.</p>
            </div>

            <div
                v-if="page.props.flash?.trialSignup?.status === 'created'"
                class="mb-5 rounded-lg border border-emerald-300 bg-emerald-50 p-4 text-sm text-emerald-900"
            >
                <p class="font-medium">Link de teste criado para {{ page.props.flash.trialSignup.agent }}.</p>
                <a :href="page.props.flash.trialSignup.url" target="_blank" class="mt-1 inline-block underline">
                    Abrir link de teste
                </a>
            </div>

            <form class="grid gap-4 md:grid-cols-2" @submit.prevent="submitTrial">
                <div class="grid gap-1">
                    <label for="name" class="text-sm font-medium">Nome</label>
                    <input id="name" v-model="trialForm.name" class="h-10 rounded-md border px-3 text-sm" required />
                    <p v-if="trialForm.errors.name" class="text-xs text-red-600">{{ trialForm.errors.name }}</p>
                </div>

                <div class="grid gap-1">
                    <label for="email" class="text-sm font-medium">Email</label>
                    <input id="email" v-model="trialForm.email" type="email" class="h-10 rounded-md border px-3 text-sm" required />
                    <p v-if="trialForm.errors.email" class="text-xs text-red-600">{{ trialForm.errors.email }}</p>
                </div>

                <div class="grid gap-1">
                    <label for="company" class="text-sm font-medium">Empresa</label>
                    <input id="company" v-model="trialForm.company" class="h-10 rounded-md border px-3 text-sm" />
                </div>

                <div class="grid gap-1">
                    <label for="phone" class="text-sm font-medium">Telefone</label>
                    <input id="phone" v-model="trialForm.phone" class="h-10 rounded-md border px-3 text-sm" />
                </div>

                <div class="grid gap-1">
                    <label for="product" class="text-sm font-medium">Interesse principal</label>
                    <select id="product" v-model="trialForm.product" class="h-10 rounded-md border px-3 text-sm">
                        <option value="agenda_clinic">Agenda Clinic</option>
                        <option value="ibox_delivery">IBOX Delivery</option>
                        <option value="conecta">Conecta</option>
                        <option value="custom">Projeto customizado</option>
                    </select>
                </div>

                <div class="grid gap-1">
                    <label for="agent" class="text-sm font-medium">Agente de teste</label>
                    <select id="agent" v-model="trialForm.ai_agent_slug" class="h-10 rounded-md border px-3 text-sm">
                        <option value="">Auto (mais recente)</option>
                        <option v-for="agent in activeAgents" :key="agent.id" :value="agent.slug">
                            {{ agent.name }}
                        </option>
                    </select>
                </div>

                <div class="grid gap-1 md:col-span-2">
                    <label for="message" class="text-sm font-medium">Contexto do teste</label>
                    <textarea id="message" v-model="trialForm.message" class="min-h-24 rounded-md border px-3 py-2 text-sm" />
                </div>

                <label class="flex items-start gap-2 text-sm text-zinc-700 md:col-span-2">
                    <input v-model="trialForm.consent_accepted" type="checkbox" class="mt-0.5 h-4 w-4" />
                    <span>Concordo com o processamento dos dados para contato comercial e demonstração.</span>
                </label>
                <p v-if="trialForm.errors.consent_accepted" class="text-xs text-red-600 md:col-span-2">
                    {{ trialForm.errors.consent_accepted }}
                </p>

                <button
                    type="submit"
                    class="rounded-md bg-zinc-900 px-5 py-2.5 text-sm font-medium text-white hover:bg-zinc-700 disabled:opacity-50 md:col-span-2"
                    :disabled="trialForm.processing"
                >
                    {{ trialForm.processing ? 'Enviando...' : copy[locale].cta }}
                </button>
            </form>
        </div>
    </section>
</template>
