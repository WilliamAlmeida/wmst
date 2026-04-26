<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ArrowRight, Sparkles, MessageCircle, CheckCircle2 } from 'lucide-vue-next';
import Reveal from '@/components/public/Reveal.vue';

type Locale = 'pt_BR' | 'es' | 'en';

interface Service {
    slug: string;
    icon: string;
    title: string;
    highlight: string;
    description: string;
    badge: string;
    whatsapp: string;
    deliverables: string[];
}

const props = defineProps<{
    locale: Locale;
    services: Service[];
    canonicalUrl: string;
    alternateUrls: Record<Locale, string>;
}>();

const titleByLocale: Record<Locale, string> = {
    pt_BR: 'Solucoes e Servicos - WMST | IA, automacao e sistemas sob medida',
    es: 'Soluciones y Servicios - WMST | IA, automatizacion y sistemas a medida',
    en: 'Solutions & Services - WMST | AI, automation and custom systems',
};

const copy: Record<Locale, { eyebrow: string; title: string; subtitle: string; cta: string; details: string; includesLabel: string }> = {
    pt_BR: { eyebrow: 'Servicos', title: 'Tudo o que voce precisa para crescer', subtitle: 'Automacoes, IA e desenvolvimento sob medida para empresas que querem resultados mensuráveis.', cta: 'Falar com especialista', details: 'Ver detalhes', includesLabel: 'O que esta incluido' },
    es: { eyebrow: 'Servicios', title: 'Todo lo que necesitas para crecer', subtitle: 'Automatizaciones, IA y desarrollo a medida para empresas que quieren resultados medibles.', cta: 'Hablar con especialista', details: 'Ver detalles', includesLabel: 'Que esta incluido' },
    en: { eyebrow: 'Services', title: 'Everything you need to grow', subtitle: 'Automation, AI and custom development for companies that want measurable results.', cta: 'Talk to a specialist', details: 'See details', includesLabel: 'What is included' },
};

const serviceUrl = (slug: string): string =>
    props.locale === 'pt_BR' ? `/solucoes/${slug}` : `/${props.locale}/solucoes/${slug}`;

const serviceListSchema = JSON.stringify({
    '@context': 'https://schema.org',
    '@type': 'ItemList',
    name: titleByLocale[props.locale],
    itemListElement: props.services.map((s, i) => ({
        '@type': 'ListItem',
        position: i + 1,
        name: s.title,
        description: s.description,
        url: `${props.canonicalUrl.replace('/solucoes', '')}${serviceUrl(s.slug)}`,
    })),
});
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
        <meta property="og:image" content="/images/logo-wmst.png" />
        <component :is="'script'" type="application/ld+json" v-html="serviceListSchema" />
    </Head>

    <!-- HERO -->
    <section class="relative overflow-hidden bg-brand-radial">
        <div class="pointer-events-none absolute inset-0 bg-brand-grid opacity-30 [mask-image:radial-gradient(ellipse_at_center,black,transparent_70%)]" />
        <div class="relative mx-auto max-w-7xl px-4 pt-16 pb-14 md:px-8 md:pt-24 md:pb-20">
            <Reveal>
                <span class="inline-flex items-center gap-2 rounded-full border border-[color:var(--color-brand)]/20 bg-[color:var(--color-brand-soft)] px-3 py-1 text-xs font-semibold text-[color:var(--color-brand)]">
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

    <!-- SERVICES GRID -->
    <section class="bg-zinc-50 py-20">
        <div class="mx-auto max-w-7xl px-4 md:px-8">
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                <Reveal v-for="(service, i) in services" :key="service.slug" :delay="(i % 3) * 80">
                    <Link
                        :href="serviceUrl(service.slug)"
                        class="group flex h-full flex-col overflow-hidden rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:border-[color:var(--color-brand)]/40 hover:shadow-xl"
                    >
                        <div class="mb-4 inline-flex h-11 w-11 items-center justify-center rounded-xl bg-gradient-to-br from-[color:var(--color-brand-soft)] to-[color:var(--color-brand-2-soft)] text-[color:var(--color-brand)] transition-transform group-hover:-rotate-6 group-hover:scale-110">
                            <Sparkles class="h-5 w-5" />
                        </div>
                        <span class="inline-flex w-fit rounded-md border border-[color:var(--color-brand)]/20 px-2 py-0.5 text-xs font-semibold text-[color:var(--color-brand)]">
                            {{ service.badge }}
                        </span>
                        <h2 class="mt-3 font-display text-lg font-semibold text-zinc-900 group-hover:text-[color:var(--color-brand)]">
                            {{ service.title }}
                        </h2>
                        <p class="mt-1 text-sm font-semibold text-emerald-600">{{ service.highlight }}</p>
                        <p class="mt-3 flex-1 text-sm leading-relaxed text-zinc-600">{{ service.description }}</p>

                        <ul class="mt-4 space-y-1.5 border-t border-zinc-100 pt-4 text-xs text-zinc-600">
                            <li v-for="d in service.deliverables.slice(0, 3)" :key="d" class="flex items-start gap-2">
                                <CheckCircle2 class="mt-px h-3.5 w-3.5 shrink-0 text-[color:var(--color-brand)]" />
                                {{ d }}
                            </li>
                        </ul>

                        <span class="mt-5 inline-flex items-center gap-2 text-sm font-semibold text-zinc-900 group-hover:text-[color:var(--color-brand)]">
                            {{ copy[locale].details }}
                            <ArrowRight class="h-4 w-4 transition-transform group-hover:translate-x-1" />
                        </span>
                    </Link>
                </Reveal>
            </div>
        </div>
    </section>

    <!-- CTA BANNER -->
    <section class="bg-white py-16">
        <div class="mx-auto max-w-4xl px-4 text-center md:px-8">
            <Reveal>
                <div class="overflow-hidden rounded-3xl bg-gradient-to-br from-[color:var(--color-brand)] to-[color:var(--color-brand-2)] px-8 py-12 text-white shadow-2xl">
                    <h2 class="font-display text-3xl font-bold">Nao sabe por onde comecar?</h2>
                    <p class="mt-3 text-base text-white/90">Nosso time faz um diagnostico gratuito e aponta as maiores oportunidades para o seu negocio.</p>
                    <a
                        href="https://wa.me/5512982184879?text=Ol%C3%A1%2C%20gostaria%20de%20um%20diagn%C3%B3stico%20gratuito"
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
</template>
