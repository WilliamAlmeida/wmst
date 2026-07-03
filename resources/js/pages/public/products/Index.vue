<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ArrowRight, Sparkles, CheckCircle2 } from 'lucide-vue-next';
import Reveal from '@/components/public/Reveal.vue';
import SectionHeading from '@/components/public/SectionHeading.vue';
import ScanFrame from '@/components/public/ScanFrame.vue';
import { useSeo } from '@/composables/useSeo';
import { vSpotlight } from '@/directives/spotlight';

type Locale = 'pt_BR' | 'es' | 'en';

interface Product {
    slug: string;
    name: string;
    badge: string;
    tagline: string;
    description: string;
    logo: string;
    metrics: Array<{ label: string; value: string }>;
    whatsapp: string;
    features: Array<{ icon: string; title: string; description: string }>;
}

const props = defineProps<{
    locale: Locale;
    products: Product[];
    canonicalUrl: string;
    alternateUrls: Record<Locale, string>;
}>();

const titleByLocale: Record<Locale, string> = {
    pt_BR: 'Nossos Produtos - WMST | Agenda Clinic, IBOX Delivery, Conecta',
    es: 'Nuestros Productos - WMST | Agenda Clinic, IBOX Delivery, Conecta',
    en: 'Our Products - WMST | Agenda Clinic, IBOX Delivery, Conecta',
};

const copy: Record<Locale, { eyebrow: string; title: string; subtitle: string; cta: string; details: string }> = {
    pt_BR: { eyebrow: 'Produtos WMST', title: 'Soluções prontas para o seu setor', subtitle: 'Plataformas verticais desenvolvidas e evoluídas há anos, com cases reais e ROI comprovado.', cta: 'Quero saber mais', details: 'Ver detalhes' },
    es: { eyebrow: 'Productos WMST', title: 'Soluciones listas para tu sector', subtitle: 'Plataformas verticales desarrolladas y evolucionadas durante anos, con casos reales y ROI comprobado.', cta: 'Quiero saber mas', details: 'Ver detalles' },
    en: { eyebrow: 'WMST Products', title: 'Ready-made solutions for your sector', subtitle: 'Vertical platforms built and evolved for years, with real cases and proven ROI.', cta: 'I want to learn more', details: 'See details' },
};

const productUrl = (slug: string): string =>
    props.locale === 'pt_BR' ? `/produtos/${slug}` : `/${props.locale}/produtos/${slug}`;

const softwareSchema = JSON.stringify(
    props.products.map((p) => ({
        '@context': 'https://schema.org',
        '@type': 'SoftwareApplication',
        name: p.name,
        applicationCategory: 'BusinessApplication',
        description: p.description,
        url: props.locale === 'pt_BR' ? `${props.alternateUrls.pt_BR.replace('/produtos', '')}/produtos/${p.slug}` : undefined,
        author: { '@type': 'Organization', name: 'WMST' },
    })),
);

const { defaultOgImage } = useSeo();
const ogImage = defaultOgImage();
</script>

<template>
    <Head :title="titleByLocale[locale]">
        <meta name="description" :content="copy[locale].subtitle" />
        <link rel="canonical" :href="canonicalUrl" />
        <link rel="alternate" hreflang="pt-BR" :href="alternateUrls.pt_BR" />
        <link rel="alternate" hreflang="es" :href="alternateUrls.es" />
        <link rel="alternate" hreflang="en" :href="alternateUrls.en" />
        <link rel="alternate" hreflang="x-default" :href="alternateUrls.pt_BR" />
        <meta property="og:title" :content="titleByLocale[locale]" />
        <meta property="og:description" :content="copy[locale].subtitle" />
        <meta property="og:url" :content="canonicalUrl" />
        <meta property="og:image" :content="ogImage" />
        <meta property="og:site_name" content="WMST" />
        <meta name="twitter:card" content="summary_large_image" />
        <meta name="twitter:image" :content="ogImage" />
        <component :is="'script'" type="application/ld+json" v-html="softwareSchema" />
    </Head>

    <!-- HERO -->
    <section class="relative overflow-hidden bg-brand-radial">
        <div class="pointer-events-none absolute inset-0 bg-brand-grid opacity-30 [mask-image:radial-gradient(ellipse_at_center,black,transparent_70%)]" />
        <div class="relative mx-auto max-w-7xl px-4 pt-16 pb-14 md:px-8 md:pt-24 md:pb-20">
            <Reveal>
                <span class="inline-flex items-center gap-2 rounded-full border border-brand-2/25 bg-brand-2-soft px-3 py-1 text-xs font-semibold text-(--wmst-green-700)">
                    <Sparkles class="h-3.5 w-3.5" />
                    {{ copy[locale].eyebrow }}
                </span>
                <h1 class="mt-4 max-w-3xl font-display text-4xl font-bold leading-tight md:text-5xl">
                    {{ copy[locale].title }}
                </h1>
                <p class="mt-4 max-w-2xl text-lg text-zinc-600">{{ copy[locale].subtitle }}</p>
            </Reveal>
        </div>
    </section>

    <!-- PRODUCTS -->
    <section class="bg-zinc-50 py-20">
        <div class="mx-auto max-w-7xl space-y-12 px-4 md:px-8">
            <Reveal v-for="(product, i) in products" :key="product.slug">
                <article v-spotlight class="spotlight-card grid items-center gap-8 overflow-hidden rounded-3xl border border-zinc-200 bg-white shadow-sm transition hover:shadow-lg md:grid-cols-2 md:gap-0">
                    <!-- Image side -->
                    <div
                        class="relative flex min-h-[340px] flex-col items-center justify-center gap-7 overflow-hidden bg-gradient-to-br from-brand via-(--wmst-navy-700) to-(--wmst-navy-900) p-8 md:p-10"
                        :class="i % 2 === 1 ? 'md:order-2' : ''"
                    >
                        <div class="pointer-events-none absolute inset-0 bg-brand-grid opacity-[0.06]" />
                        <ScanFrame size="md">
                            <div class="flex h-32 w-32 items-center justify-center rounded-2xl bg-white p-4 shadow-xl md:h-36 md:w-36">
                                <img
                                    :src="product.logo"
                                    :alt="product.name"
                                    class="h-full w-full object-contain"
                                    loading="lazy"
                                    decoding="async"
                                />
                            </div>
                        </ScanFrame>
                        <!-- Metrics -->
                        <div class="relative flex flex-wrap justify-center gap-2">
                            <span
                                v-for="m in product.metrics"
                                :key="m.label"
                                class="inline-flex flex-col items-center rounded-xl border border-white/10 bg-white/10 px-3 py-2 text-center backdrop-blur"
                            >
                                <span class="font-display text-lg font-bold text-brand-2">{{ m.value }}</span>
                                <span class="text-[10px] text-white/60">{{ m.label }}</span>
                            </span>
                        </div>
                    </div>

                    <!-- Content side -->
                    <div class="flex flex-col justify-center p-8 md:p-12">
                        <span class="inline-flex w-fit rounded-full border border-brand-2/25 bg-brand-2-soft px-3 py-1 text-xs font-semibold text-(--wmst-green-700)">
                            {{ product.badge }}
                        </span>
                        <h2 class="mt-4 font-display text-3xl font-bold text-zinc-900">{{ product.name }}</h2>
                        <p class="mt-1 text-sm font-medium text-zinc-500">{{ product.tagline }}</p>
                        <p class="mt-4 text-base text-zinc-600">{{ product.description }}</p>

                        <ul class="mt-6 grid gap-2 text-sm">
                            <li
                                v-for="feat in product.features.slice(0, 4)"
                                :key="feat.title"
                                class="flex items-center gap-2 text-zinc-700"
                            >
                                <CheckCircle2 class="h-4 w-4 shrink-0 text-[color:var(--color-brand)]" />
                                {{ feat.title }}
                            </li>
                        </ul>

                        <div class="mt-8 flex flex-wrap gap-3">
                            <Link
                                :href="productUrl(product.slug)"
                                v-spotlight
                                class="spotlight-btn inline-flex items-center gap-2 rounded-lg bg-brand px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-(--wmst-navy-700)"
                            >
                                {{ copy[locale].details }}
                                <ArrowRight class="h-4 w-4" />
                            </Link>
                            <a
                                :href="product.whatsapp"
                                target="_blank"
                                rel="noopener noreferrer"
                                v-spotlight
                                class="spotlight-btn inline-flex items-center gap-2 rounded-lg bg-[#25D366] px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-[#1fb955]"
                            >
                                {{ copy[locale].cta }}
                            </a>
                        </div>
                    </div>
                </article>
            </Reveal>
        </div>
    </section>
</template>
