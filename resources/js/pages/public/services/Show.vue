<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ArrowLeft, ArrowRight, CheckCircle2, MessageCircle, Sparkles, ChevronRight } from 'lucide-vue-next';
import { computed } from 'vue';
import Reveal from '@/components/public/Reveal.vue';

type Locale = 'pt_BR' | 'es' | 'en';

interface ProcessStep {
    title: string;
    description: string;
}

interface Service {
    slug: string;
    icon: string;
    title: string;
    highlight: string;
    description: string;
    badge: string;
    whatsapp: string;
    deliverables: string[];
    process: ProcessStep[];
}

interface RelatedService {
    slug: string;
    title: string;
    badge: string;
    highlight: string;
    description: string;
    whatsapp: string;
}

const props = defineProps<{
    locale: Locale;
    service: Service;
    related: RelatedService[];
    canonicalUrl: string;
    alternateUrls: Record<Locale, string>;
    servicesUrl: string;
}>();

const copy: Record<Locale, { back: string; cta: string; deliverables: string; process: string; related: string }> = {
    pt_BR: { back: 'Ver todas as solucoes', cta: 'Quero comecar agora', deliverables: 'O que você recebe', process: 'Como funciona', related: 'Outras solucoes' },
    es: { back: 'Ver todas las soluciones', cta: 'Quiero empezar ahora', deliverables: 'Lo que recibes', process: 'Como funciona', related: 'Otras soluciones' },
    en: { back: 'View all solutions', cta: 'I want to start now', deliverables: 'What you receive', process: 'How it works', related: 'Other solutions' },
};

const serviceUrl = (slug: string): string =>
    props.locale === 'pt_BR' ? `/solucoes/${slug}` : `/${props.locale}/solucoes/${slug}`;

const serviceSchema = computed(() =>
    JSON.stringify({
        '@context': 'https://schema.org',
        '@type': 'Service',
        name: props.service.title,
        description: props.service.description,
        url: props.canonicalUrl,
        provider: { '@type': 'Organization', name: 'WMST' },
        areaServed: { '@type': 'Country', name: 'Brazil' },
        hasOfferCatalog: {
            '@type': 'OfferCatalog',
            name: props.service.title,
            itemListElement: props.service.deliverables.map((d, i) => ({
                '@type': 'ListItem',
                position: i + 1,
                name: d,
            })),
        },
    }),
);

const breadcrumbSchema = computed(() =>
    JSON.stringify({
        '@context': 'https://schema.org',
        '@type': 'BreadcrumbList',
        itemListElement: [
            { '@type': 'ListItem', position: 1, name: 'Home', item: props.locale === 'pt_BR' ? '/' : `/${props.locale}` },
            { '@type': 'ListItem', position: 2, name: 'Solucoes', item: props.servicesUrl },
            { '@type': 'ListItem', position: 3, name: props.service.title, item: props.canonicalUrl },
        ],
    }),
);
</script>

<template>
    <Head :title="`${service.title} - WMST`">
        <meta name="description" :content="service.description" />
        <link rel="canonical" :href="canonicalUrl" />
        <link rel="alternate" hreflang="pt-BR" :href="alternateUrls.pt_BR" />
        <link rel="alternate" hreflang="es" :href="alternateUrls.es" />
        <link rel="alternate" hreflang="en" :href="alternateUrls.en" />
        <link rel="alternate" hreflang="x-default" :href="alternateUrls.pt_BR" />
        <meta property="og:title" :content="`${service.title} - WMST`" />
        <meta property="og:description" :content="service.description" />
        <meta property="og:url" :content="canonicalUrl" />
        <meta property="og:image" content="/images/logo-wmst.png" />
        <component :is="'script'" type="application/ld+json" v-html="serviceSchema" />
        <component :is="'script'" type="application/ld+json" v-html="breadcrumbSchema" />
    </Head>

    <!-- HERO -->
    <section class="relative overflow-hidden bg-brand-radial">
        <div class="pointer-events-none absolute inset-0 bg-brand-grid opacity-30 [mask-image:radial-gradient(ellipse_at_center,black,transparent_70%)]" />
        <div class="relative mx-auto max-w-7xl px-4 pt-12 pb-14 md:px-8 md:pt-20 md:pb-20">
            <Link :href="servicesUrl" class="inline-flex items-center gap-1.5 text-sm text-zinc-600 hover:text-[color:var(--color-brand)]">
                <ArrowLeft class="h-4 w-4" />
                {{ copy[locale].back }}
            </Link>

            <Reveal class="mt-8 max-w-3xl">
                <span class="inline-flex items-center gap-2 rounded-full border border-[color:var(--color-brand)]/20 bg-[color:var(--color-brand-soft)] px-3 py-1 text-xs font-semibold text-[color:var(--color-brand)]">
                    <Sparkles class="h-3.5 w-3.5" />
                    {{ service.badge }}
                </span>
                <h1 class="mt-4 font-display text-4xl font-bold leading-tight text-zinc-900 md:text-5xl">
                    {{ service.title }}
                </h1>
                <p class="mt-3 text-xl font-semibold text-emerald-600">{{ service.highlight }}</p>
                <p class="mt-4 text-lg text-zinc-600">{{ service.description }}</p>

                <div class="mt-8 flex flex-wrap gap-3">
                    <a
                        :href="service.whatsapp"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="inline-flex items-center gap-2 rounded-lg bg-[#25D366] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#1fb955]"
                    >
                        <MessageCircle class="h-4 w-4" />
                        {{ copy[locale].cta }}
                    </a>
                </div>
            </Reveal>
        </div>
    </section>

    <!-- DELIVERABLES + PROCESS in two columns -->
    <section class="bg-white py-20">
        <div class="mx-auto max-w-7xl px-4 md:px-8">
            <div class="grid gap-12 md:grid-cols-2">
                <!-- Deliverables -->
                <div>
                    <h2 class="font-display text-2xl font-bold text-zinc-900">{{ copy[locale].deliverables }}</h2>
                    <ul class="mt-8 space-y-4">
                        <li
                            v-for="(d, i) in service.deliverables"
                            :key="d"
                            class="flex gap-4 rounded-2xl border border-zinc-200 bg-white p-5 shadow-sm"
                        >
                            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-[color:var(--color-brand)] to-[color:var(--color-brand-2)] text-xs font-bold text-white">
                                {{ i + 1 }}
                            </span>
                            <div class="flex items-center gap-2 text-sm font-medium text-zinc-800">
                                <CheckCircle2 class="h-4 w-4 shrink-0 text-[color:var(--color-brand)]" />
                                {{ d }}
                            </div>
                        </li>
                    </ul>
                </div>

                <!-- Process -->
                <div>
                    <h2 class="font-display text-2xl font-bold text-zinc-900">{{ copy[locale].process }}</h2>
                    <ol class="mt-8 space-y-6">
                        <li
                            v-for="(step, i) in service.process"
                            :key="step.title"
                            class="flex gap-4"
                        >
                            <div class="flex flex-col items-center gap-1">
                                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-[color:var(--color-brand)] to-[color:var(--color-brand-2)] text-xs font-bold text-white">
                                    {{ i + 1 }}
                                </span>
                                <div v-if="i < service.process.length - 1" class="w-px flex-1 bg-zinc-200" />
                            </div>
                            <div class="pb-6">
                                <h3 class="font-display text-base font-semibold text-zinc-900">{{ step.title }}</h3>
                                <p class="mt-1 text-sm text-zinc-600">{{ step.description }}</p>
                            </div>
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA BANNER -->
    <section class="bg-zinc-50 py-16">
        <div class="mx-auto max-w-4xl px-4 text-center md:px-8">
            <Reveal>
                <div class="overflow-hidden rounded-3xl bg-gradient-to-br from-[color:var(--color-brand)] to-[color:var(--color-brand-2)] px-8 py-12 text-white shadow-2xl">
                    <h2 class="font-display text-3xl font-bold">Pronto para comecar?</h2>
                    <p class="mt-3 text-base text-white/90">Fale agora com um especialista da WMST. Resposta em ate 2 horas.</p>
                    <a
                        :href="service.whatsapp"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="mt-8 inline-flex items-center gap-2 rounded-xl bg-white px-6 py-3 text-sm font-bold text-zinc-900 shadow transition hover:bg-zinc-100"
                    >
                        <MessageCircle class="h-4 w-4 text-[#25D366]" />
                        {{ copy[locale].cta }}
                        <ArrowRight class="h-4 w-4" />
                    </a>
                </div>
            </Reveal>
        </div>
    </section>

    <!-- RELATED -->
    <section v-if="related.length" class="bg-white py-16">
        <div class="mx-auto max-w-7xl px-4 md:px-8">
            <h2 class="font-display text-2xl font-bold text-zinc-900">{{ copy[locale].related }}</h2>
            <div class="mt-8 grid gap-4 md:grid-cols-3">
                <Link
                    v-for="rel in related"
                    :key="rel.slug"
                    :href="serviceUrl(rel.slug)"
                    class="group flex flex-col gap-3 rounded-2xl border border-zinc-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-[color:var(--color-brand)]/40 hover:shadow-lg"
                >
                    <span class="text-xs font-semibold uppercase tracking-wide text-[color:var(--color-brand)]">{{ rel.badge }}</span>
                    <h3 class="font-display text-base font-semibold text-zinc-900 group-hover:text-[color:var(--color-brand)]">{{ rel.title }}</h3>
                    <p class="text-sm font-medium text-emerald-600">{{ rel.highlight }}</p>
                    <span class="mt-auto inline-flex items-center gap-1 text-xs font-semibold text-zinc-700 group-hover:text-[color:var(--color-brand)]">
                        Ver solução <ChevronRight class="h-3.5 w-3.5" />
                    </span>
                </Link>
            </div>
        </div>
    </section>
</template>
