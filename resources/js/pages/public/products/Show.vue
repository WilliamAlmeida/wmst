<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ArrowLeft, ArrowRight, CheckCircle2, MessageCircle, Sparkles, ChevronRight, ExternalLink, Target, Plug } from 'lucide-vue-next';
import { computed } from 'vue';
import Reveal from '@/components/public/Reveal.vue';
import ScanFrame from '@/components/public/ScanFrame.vue';
import { vSpotlight } from '@/directives/spotlight';
import { vTilt } from '@/directives/tilt';

type Locale = 'pt_BR' | 'es' | 'en';

interface Feature {
    icon: string;
    title: string;
    description: string;
}

interface ProcessStep {
    title: string;
    description: string;
}

interface Product {
    slug: string;
    name: string;
    badge: string;
    tagline: string;
    description: string;
    logo: string;
    whatsapp: string;
    website?: string;
    features: Feature[];
    metrics: Array<{ label: string; value: string }>;
    process: ProcessStep[];
    tech: string[];
    useCases?: string[];
    integrations?: string[];
    pricingNote?: string;
}

interface RelatedProduct {
    slug: string;
    name: string;
    badge: string;
    tagline: string;
    logo: string;
    whatsapp: string;
}

const props = defineProps<{
    locale: Locale;
    product: Product;
    related: RelatedProduct[];
    canonicalUrl: string;
    alternateUrls: Record<Locale, string>;
    productsUrl: string;
}>();

const ctaLabel: Record<Locale, string> = {
    pt_BR: 'Quero conhecer o',
    es: 'Quiero conocer el',
    en: 'I want to learn about',
};

const processLabel: Record<Locale, string> = {
    pt_BR: 'Como funciona',
    es: 'Como funciona',
    en: 'How it works',
};

const techLabel: Record<Locale, string> = {
    pt_BR: 'Tecnologias',
    es: 'Tecnologias',
    en: 'Technologies',
};

const relatedLabel: Record<Locale, string> = {
    pt_BR: 'Outros produtos',
    es: 'Otros productos',
    en: 'Other products',
};

const backLabel: Record<Locale, string> = {
    pt_BR: 'Ver todos os produtos',
    es: 'Ver todos los productos',
    en: 'View all products',
};

const visitSiteLabel: Record<Locale, string> = {
    pt_BR: 'Visitar site oficial',
    es: 'Visitar sitio oficial',
    en: 'Visit official site',
};

const useCasesLabel: Record<Locale, string> = {
    pt_BR: 'Para quem é ideal',
    es: 'Para quien es ideal',
    en: 'Who it is for',
};

const integrationsLabel: Record<Locale, string> = {
    pt_BR: 'Integrações',
    es: 'Integraciones',
    en: 'Integrations',
};

const pricingLabel: Record<Locale, string> = {
    pt_BR: 'Investimento',
    es: 'Inversion',
    en: 'Pricing',
};

const productUrl = (slug: string): string =>
    props.locale === 'pt_BR' ? `/produtos/${slug}` : `/${props.locale}/produtos/${slug}`;

const softwareSchema = computed(() =>
    JSON.stringify({
        '@context': 'https://schema.org',
        '@type': 'SoftwareApplication',
        name: props.product.name,
        applicationCategory: 'BusinessApplication',
        description: props.product.description,
        url: props.canonicalUrl,
        author: { '@type': 'Organization', name: 'WMST' },
        offers: {
            '@type': 'Offer',
            availability: 'https://schema.org/InStock',
            url: props.product.whatsapp,
        },
    }),
);

const breadcrumbSchema = computed(() =>
    JSON.stringify({
        '@context': 'https://schema.org',
        '@type': 'BreadcrumbList',
        itemListElement: [
            { '@type': 'ListItem', position: 1, name: 'Home', item: props.locale === 'pt_BR' ? '/' : `/${props.locale}` },
            { '@type': 'ListItem', position: 2, name: 'Produtos', item: props.productsUrl },
            { '@type': 'ListItem', position: 3, name: props.product.name, item: props.canonicalUrl },
        ],
    }),
);
</script>

<template>
    <Head :title="`${product.name} - WMST`">
        <meta name="description" :content="product.tagline" />
        <link rel="canonical" :href="canonicalUrl" />
        <link rel="alternate" hreflang="pt-BR" :href="alternateUrls.pt_BR" />
        <link rel="alternate" hreflang="es" :href="alternateUrls.es" />
        <link rel="alternate" hreflang="en" :href="alternateUrls.en" />
        <link rel="alternate" hreflang="x-default" :href="alternateUrls.pt_BR" />
        <meta property="og:title" :content="`${product.name} - WMST`" />
        <meta property="og:description" :content="product.tagline" />
        <meta property="og:url" :content="canonicalUrl" />
        <meta property="og:image" :content="product.logo" />
        <component :is="'script'" type="application/ld+json" v-html="softwareSchema" />
        <component :is="'script'" type="application/ld+json" v-html="breadcrumbSchema" />
    </Head>

    <!-- HERO -->
    <section class="relative overflow-hidden bg-brand-radial">
        <div class="pointer-events-none absolute inset-0 bg-brand-grid opacity-30 [mask-image:radial-gradient(ellipse_at_center,black,transparent_70%)]" />
        <div class="relative mx-auto max-w-7xl px-4 pt-12 pb-0 md:px-8 md:pt-20">
            <Link :href="productsUrl" class="inline-flex items-center gap-1.5 text-sm text-zinc-600 hover:text-[color:var(--color-brand)]">
                <ArrowLeft class="h-4 w-4" />
                {{ backLabel[locale] }}
            </Link>

            <Reveal class="mt-8 grid items-end gap-8 md:grid-cols-2 md:gap-12">
                <div class="pb-12">
                    <span class="inline-flex items-center gap-2 rounded-full border border-brand-2/25 bg-brand-2-soft px-3 py-1 text-xs font-semibold text-(--wmst-green-700)">
                        <Sparkles class="h-3.5 w-3.5" />
                        {{ product.badge }}
                    </span>
                    <h1 class="mt-4 font-display text-4xl font-bold leading-tight text-zinc-900 md:text-5xl">
                        {{ product.name }}
                    </h1>
                    <p class="mt-3 text-lg font-medium text-zinc-600">{{ product.tagline }}</p>
                    <p class="mt-4 text-base text-zinc-600">{{ product.description }}</p>

                    <div class="mt-8 flex flex-wrap gap-3">
                        <a
                            v-spotlight
                            :href="product.whatsapp"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="spotlight-btn inline-flex items-center gap-2 rounded-lg bg-[#25D366] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#1fb955]"
                        >
                            <MessageCircle class="h-4 w-4" />
                            {{ ctaLabel[locale] }} {{ product.name }}
                        </a>
                        <a
                            v-if="product.website"
                            :href="product.website"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="inline-flex items-center gap-2 rounded-lg border border-zinc-300 bg-white px-5 py-3 text-sm font-semibold text-zinc-900 transition hover:border-[color:var(--color-brand)] hover:text-[color:var(--color-brand)]"
                        >
                            <ExternalLink class="h-4 w-4" />
                            {{ visitSiteLabel[locale] }}
                        </a>
                    </div>
                </div>

                <!-- Product logo elevated card -->
                <div class="relative flex justify-center pb-6 md:justify-end md:pb-8">
                    <ScanFrame v-tilt="{ max: 10, scale: 1.05 }" size="lg">
                        <div class="relative flex h-56 w-56 items-center justify-center rounded-3xl border border-zinc-200 bg-white shadow-2xl md:h-72 md:w-72">
                            <div class="absolute -inset-4 rounded-3xl bg-gradient-to-br from-brand-soft via-transparent to-brand-2-soft blur-2xl" />
                            <img :src="product.logo" :alt="product.name" class="relative z-10 h-36 w-36 rounded-2xl object-contain md:h-48 md:w-48" width="192" height="192" loading="eager" fetchpriority="high" decoding="async" />
                        </div>
                    </ScanFrame>
                </div>
            </Reveal>
        </div>
    </section>

    <!-- METRICS STRIP -->
    <section class="bg-white py-8 shadow-sm">
        <div class="mx-auto max-w-7xl px-4 md:px-8">
            <div class="flex flex-wrap justify-center gap-6 md:gap-16">
                <div v-for="m in product.metrics" :key="m.label" class="text-center">
                    <p class="font-display text-3xl font-bold gradient-text">{{ m.value }}</p>
                    <p class="mt-1 text-xs text-zinc-500">{{ m.label }}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- FEATURES -->
    <section class="bg-zinc-50 py-20">
        <div class="mx-auto max-w-7xl px-4 md:px-8">
            <h2 class="font-display text-2xl font-bold text-zinc-900 md:text-3xl">
                Funcionalidades
            </h2>
            <div class="mt-10 grid gap-6 md:grid-cols-2">
                <Reveal v-for="(feat, i) in product.features" :key="feat.title" :delay="i * 80">
                    <div class="flex gap-4 rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-[color:var(--color-brand-soft)] to-[color:var(--color-brand-2-soft)]">
                            <CheckCircle2 class="h-5 w-5 text-[color:var(--color-brand)]" />
                        </div>
                        <div>
                            <h3 class="font-display text-base font-semibold text-zinc-900">{{ feat.title }}</h3>
                            <p class="mt-1 text-sm text-zinc-600">{{ feat.description }}</p>
                        </div>
                    </div>
                </Reveal>
            </div>
        </div>
    </section>

    <!-- HOW IT WORKS -->
    <section class="bg-white py-20">
        <div class="mx-auto max-w-7xl px-4 md:px-8">
            <h2 class="font-display text-2xl font-bold text-zinc-900 md:text-3xl">
                {{ processLabel[locale] }}
            </h2>
            <div class="mt-10 grid gap-0 md:grid-cols-4">
                <div
                    v-for="(step, i) in product.process"
                    :key="step.title"
                    class="relative flex flex-col items-start gap-4 p-6"
                >
                    <!-- Connector line -->
                    <div v-if="i < product.process.length - 1" class="pointer-events-none absolute right-0 top-9 hidden h-px w-1/2 bg-gradient-to-r from-[color:var(--color-brand)]/40 to-transparent md:block" />
                    <div v-if="i > 0" class="pointer-events-none absolute left-0 top-9 hidden h-px w-1/2 bg-gradient-to-r from-transparent to-[color:var(--color-brand)]/40 md:block" />

                    <span class="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-[color:var(--color-brand)] to-[color:var(--color-brand-2)] text-sm font-bold text-white shadow">
                        {{ i + 1 }}
                    </span>
                    <div>
                        <h3 class="font-display text-base font-semibold text-zinc-900">{{ step.title }}</h3>
                        <p class="mt-1 text-sm text-zinc-600">{{ step.description }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- TECH STACK -->
    <section class="bg-zinc-50 py-12">
        <div class="mx-auto max-w-7xl px-4 md:px-8">
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-zinc-500">{{ techLabel[locale] }}</p>
            <div class="mt-4 flex flex-wrap gap-2">
                <span
                    v-for="t in product.tech"
                    :key="t"
                    class="rounded-full border border-zinc-300 bg-white px-3 py-1.5 text-xs font-medium text-zinc-700"
                >
                    {{ t }}
                </span>
            </div>
        </div>
    </section>

    <!-- USE CASES + INTEGRATIONS -->
    <section v-if="product.useCases?.length || product.integrations?.length" class="bg-white py-16">
        <div class="mx-auto grid max-w-7xl gap-8 px-4 md:px-8 lg:grid-cols-2">
            <Reveal v-if="product.useCases?.length">
                <div class="flex h-full flex-col rounded-2xl border border-zinc-200 bg-gradient-to-br from-white to-zinc-50 p-8 shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-[color:var(--color-brand-soft)] to-[color:var(--color-brand-2-soft)]">
                            <Target class="h-5 w-5 text-[color:var(--color-brand)]" />
                        </div>
                        <h2 class="font-display text-xl font-bold text-zinc-900 md:text-2xl">{{ useCasesLabel[locale] }}</h2>
                    </div>
                    <ul class="mt-6 space-y-3">
                        <li v-for="uc in product.useCases" :key="uc" class="flex items-start gap-3">
                            <CheckCircle2 class="mt-0.5 h-5 w-5 shrink-0 text-brand-2" />
                            <span class="text-sm text-zinc-700">{{ uc }}</span>
                        </li>
                    </ul>
                </div>
            </Reveal>
            <Reveal v-if="product.integrations?.length" :delay="100">
                <div class="flex h-full flex-col rounded-2xl border border-zinc-200 bg-gradient-to-br from-white to-zinc-50 p-8 shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-[color:var(--color-brand-2-soft)] to-[color:var(--color-brand-soft)]">
                            <Plug class="h-5 w-5 text-[color:var(--color-brand-2)]" />
                        </div>
                        <h2 class="font-display text-xl font-bold text-zinc-900 md:text-2xl">{{ integrationsLabel[locale] }}</h2>
                    </div>
                    <div class="mt-6 flex flex-wrap gap-2">
                        <span v-for="i in product.integrations" :key="i" class="rounded-full bg-white px-3 py-1.5 text-xs font-medium text-zinc-700 shadow-sm ring-1 ring-zinc-200">
                            {{ i }}
                        </span>
                    </div>
                    <p v-if="product.pricingNote" class="mt-6 rounded-xl bg-[color:var(--color-brand-soft)] px-4 py-3 text-sm text-zinc-700">
                        <span class="font-semibold text-[color:var(--color-brand)]">{{ pricingLabel[locale] }}:</span>
                        {{ product.pricingNote }}
                    </p>
                </div>
            </Reveal>
        </div>
    </section>

    <!-- CTA BANNER -->
    <section class="bg-white py-16">
        <div class="mx-auto max-w-4xl px-4 text-center md:px-8">
            <Reveal>
                <ScanFrame size="md">
                    <div class="overflow-hidden rounded-3xl bg-gradient-to-br from-brand via-(--wmst-navy-700) to-brand-2 px-8 py-12 text-white shadow-2xl">
                        <h2 class="font-display text-3xl font-bold">Pronto para começar?</h2>
                        <p class="mt-3 text-base text-white/90">Fale agora com um especialista e receba uma demo do {{ product.name }}.</p>
                        <a
                            v-spotlight
                            :href="product.whatsapp"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="spotlight-btn-dark mt-8 inline-flex items-center gap-2 rounded-xl bg-white px-6 py-3 text-sm font-bold text-zinc-900 shadow transition hover:bg-zinc-100"
                        >
                            <MessageCircle class="h-4 w-4 text-[#25D366]" />
                            Solicitar demo gratuita
                            <ArrowRight class="h-4 w-4" />
                        </a>
                    </div>
                </ScanFrame>
            </Reveal>
        </div>
    </section>

    <!-- RELATED -->
    <section v-if="related.length" class="bg-zinc-50 py-16">
        <div class="mx-auto max-w-7xl px-4 md:px-8">
            <h2 class="font-display text-2xl font-bold text-zinc-900">{{ relatedLabel[locale] }}</h2>
            <div class="mt-8 grid gap-6 md:grid-cols-2">
                <Link
                    v-for="rel in related.slice(0, 2)"
                    :key="rel.slug"
                    :href="productUrl(rel.slug)"
                    v-spotlight
                    class="group spotlight-card flex items-center gap-6 overflow-hidden rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm transition hover:-translate-y-0.5 hover:shadow-lg"
                >
                    <img :src="rel.logo" :alt="rel.name" class="h-16 w-16 shrink-0 rounded-xl object-contain" width="64" height="64" loading="lazy" decoding="async" />
                    <div class="flex-1">
                        <span class="text-xs font-semibold uppercase tracking-wide text-[color:var(--color-brand)]">{{ rel.badge }}</span>
                        <h3 class="mt-1 font-display text-lg font-semibold text-zinc-900 group-hover:text-[color:var(--color-brand)]">{{ rel.name }}</h3>
                        <p class="mt-1 line-clamp-2 text-sm text-zinc-600">{{ rel.tagline }}</p>
                    </div>
                    <ChevronRight class="h-5 w-5 shrink-0 text-zinc-400 group-hover:text-[color:var(--color-brand)]" />
                </Link>
            </div>
        </div>
    </section>
</template>
