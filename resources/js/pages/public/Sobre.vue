<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import { Zap, ShieldCheck, Heart, Sparkles, MessageCircle, ArrowRight, MapPin, Mail, Phone, Clock } from 'lucide-vue-next';
import SectionHeading from '@/components/public/SectionHeading.vue';
import Reveal from '@/components/public/Reveal.vue';
import StatTile from '@/components/public/StatTile.vue';
import { vSpotlight } from '@/directives/spotlight';

interface CompanyValue { icon: string; title: string; description: string; }
interface Metric { label: string; value: string; }
interface Contact { whatsapp: string; phone: string; email: string; address: string; hours: string; }

interface Company {
    mission: string;
    vision: string;
    story: string;
    values: CompanyValue[];
    metrics: Metric[];
    contact: Contact;
}

const props = defineProps<{
    locale: string;
    company: Company;
    canonicalUrl: string;
    alternateUrls: Record<string, string>;
}>();

const iconMap: Record<string, unknown> = { Zap, ShieldCheck, Heart, Sparkles };

const copy = computed(() => {
    const map = {
        pt_BR: {
            title: 'Sobre a WMST | Software house e automações IA em Lorena',
            description: 'Conheça a WMST: software sob medida, automações com IA e produtos próprios para PMEs que precisam crescer sem ampliar equipe.',
            eyebrow: 'Sobre nós',
            heading: 'Engenharia de software com',
            highlight: 'obsessão por resultados',
            mission: 'Missão',
            vision: 'Visão',
            story: 'Nossa história',
            valuesTitle: 'Valores que',
            valuesHighlight: 'guiam o time',
            metricsTitle: 'Números que',
            metricsHighlight: 'falam por nós',
            ctaTitle: 'Vamos transformar seu negócio?',
            ctaDescription: 'Fale com nosso time e receba um diagnóstico gratuito em até 24h.',
            ctaWhatsapp: 'Falar no WhatsApp',
            ctaContact: 'Ver formas de contato',
            contactPath: '/contato',
        },
        es: {
            title: 'Sobre WMST | Software house y automatizaciones IA',
            description: 'Conoce a WMST: software a medida, automatizaciones con IA y productos propios para PYMEs que necesitan crecer sin ampliar equipo.',
            eyebrow: 'Sobre nosotros',
            heading: 'Ingenieria de software con',
            highlight: 'obsesion por resultados',
            mission: 'Mision',
            vision: 'Vision',
            story: 'Nuestra historia',
            valuesTitle: 'Valores que',
            valuesHighlight: 'guian al equipo',
            metricsTitle: 'Números que',
            metricsHighlight: 'hablan por nosotros',
            ctaTitle: 'Transformemos tu negócio?',
            ctaDescription: 'Habla con nuestro equipo y recibe un diagnostico gratuito en 24h.',
            ctaWhatsapp: 'Hablar por WhatsApp',
            ctaContact: 'Ver formas de contacto',
            contactPath: '/es/contato',
        },
        en: {
            title: 'About WMST | Software house and AI automation',
            description: 'Meet WMST: custom software, AI automation and proprietary products for SMBs that need to grow without scaling headcount.',
            eyebrow: 'About us',
            heading: 'Software engineering with',
            highlight: 'an obsession for results',
            mission: 'Mission',
            vision: 'Vision',
            story: 'Our story',
            valuesTitle: 'Values that',
            valuesHighlight: 'guide our team',
            metricsTitle: 'Numbers that',
            metricsHighlight: 'speak for us',
            ctaTitle: 'Ready to transform your business?',
            ctaDescription: 'Talk to our team and get a free diagnostic within 24h.',
            ctaWhatsapp: 'Talk on WhatsApp',
            ctaContact: 'See contact options',
            contactPath: '/en/contato',
        },
    } as const;
    return map[props.locale as keyof typeof map] ?? map.pt_BR;
});

const orgSchema = computed(() => JSON.stringify({
    '@context': 'https://schema.org',
    '@type': 'Organization',
    name: 'WMST',
    url: props.alternateUrls.pt_BR,
    description: props.company.mission,
    address: { '@type': 'PostalAddress', addressLocality: 'Lorena', addressRegion: 'SP', addressCountry: 'BR' },
    contactPoint: { '@type': 'ContactPoint', telephone: props.company.contact.phone, email: props.company.contact.email, contactType: 'sales', areaServed: 'BR' },
    foundingLocation: 'Lorena, Brasil',
}));
</script>

<template>
    <Head>
        <title>{{ copy.title }}</title>
        <meta name="description" :content="copy.description" />
        <link rel="canonical" :href="canonicalUrl" />
        <link v-for="(href, lang) in alternateUrls" :key="lang" rel="alternate" :hreflang="lang === 'pt_BR' ? 'pt-BR' : lang" :href="href" />
        <meta property="og:type" content="website" />
        <meta property="og:title" :content="copy.title" />
        <meta property="og:description" :content="copy.description" />
        <meta property="og:url" :content="canonicalUrl" />
        <component :is="'script'" type="application/ld+json" v-html="orgSchema" />
    </Head>

    <!-- HERO -->
        <section class="relative overflow-hidden bg-hero-dark py-24 text-white">
            <div class="pointer-events-none absolute inset-0 bg-brand-grid opacity-[0.08]" />
            <div class="relative mx-auto max-w-5xl px-4 text-center md:px-8">
                <Reveal>
                    <p class="mb-4 text-xs font-semibold uppercase tracking-[0.3em] text-brand-2">{{ copy.eyebrow }}</p>
                    <h1 class="font-display text-4xl font-bold leading-tight md:text-6xl">
                        {{ copy.heading }} <span class="gradient-text-light">{{ copy.highlight }}</span>
                    </h1>
                    <p class="mx-auto mt-6 max-w-3xl text-lg leading-relaxed text-zinc-300">{{ company.story }}</p>
                </Reveal>
            </div>
        </section>

        <!-- MISSION + VISION -->
        <section class="bg-white py-20">
            <div class="mx-auto grid max-w-6xl gap-8 px-4 md:grid-cols-2 md:px-8">
                <Reveal>
                    <article class="rounded-2xl border border-zinc-200 bg-gradient-to-br from-zinc-50 to-white p-8 shadow-sm">
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-[color:var(--color-brand)]">{{ copy.mission }}</p>
                        <p class="mt-4 text-lg leading-relaxed text-zinc-700">{{ company.mission }}</p>
                    </article>
                </Reveal>
                <Reveal :delay="100">
                    <article class="rounded-2xl border border-zinc-200 bg-gradient-to-br from-zinc-50 to-white p-8 shadow-sm">
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-[color:var(--color-brand-2)]">{{ copy.vision }}</p>
                        <p class="mt-4 text-lg leading-relaxed text-zinc-700">{{ company.vision }}</p>
                    </article>
                </Reveal>
            </div>
        </section>

        <!-- VALUES -->
        <section class="bg-zinc-50 py-20">
            <div class="mx-auto max-w-6xl px-4 md:px-8">
                <SectionHeading :title="copy.valuesTitle + ' '" :highlight="copy.valuesHighlight" />
                <div class="mt-12 grid gap-6 md:grid-cols-2 lg:grid-cols-4">
                    <Reveal v-for="(v, i) in company.values" :key="v.title" :delay="i * 80">
                        <article v-spotlight class="spotlight-card flex h-full flex-col rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-xl">
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-brand to-brand-2 text-white">
                                <component :is="iconMap[v.icon]" class="h-6 w-6" />
                            </div>
                            <h3 class="mt-4 font-display text-lg font-semibold text-zinc-900">{{ v.title }}</h3>
                            <p class="mt-2 text-sm leading-relaxed text-zinc-600">{{ v.description }}</p>
                        </article>
                    </Reveal>
                </div>
            </div>
        </section>

        <!-- METRICS -->
        <section class="bg-white py-20">
            <div class="mx-auto max-w-6xl px-4 md:px-8">
                <SectionHeading :title="copy.metricsTitle + ' '" :highlight="copy.metricsHighlight" />
                <div class="mt-12 grid gap-6 md:grid-cols-2 lg:grid-cols-4">
                    <Reveal v-for="(m, i) in company.metrics" :key="m.label" :delay="i * 80">
                        <StatTile :label="m.label" :value="m.value" />
                    </Reveal>
                </div>
            </div>
        </section>

        <!-- CTA -->
        <section class="relative overflow-hidden bg-gradient-to-br from-brand via-(--wmst-navy-700) to-brand-2 py-20">
            <div class="relative mx-auto max-w-4xl px-4 text-center text-white md:px-8">
                <Reveal>
                    <h2 class="font-display text-3xl font-bold md:text-4xl">{{ copy.ctaTitle }}</h2>
                    <p class="mx-auto mt-4 max-w-2xl text-lg text-white/90">{{ copy.ctaDescription }}</p>
                    <div class="mt-8 flex flex-wrap justify-center gap-3">
                        <a v-spotlight :href="company.contact.whatsapp" target="_blank" rel="noopener noreferrer" class="spotlight-btn-dark inline-flex items-center gap-2 rounded-lg bg-white px-6 py-3 text-sm font-semibold text-zinc-900 shadow-lg transition hover:-translate-y-0.5">
                            <MessageCircle class="h-5 w-5 text-[#25D366]" /> {{ copy.ctaWhatsapp }}
                        </a>
                        <Link :href="copy.contactPath" class="inline-flex items-center gap-2 rounded-lg border border-white/30 bg-white/10 px-6 py-3 text-sm font-semibold text-white backdrop-blur transition hover:bg-white/20">
                            {{ copy.ctaContact }} <ArrowRight class="h-4 w-4" />
                        </Link>
                    </div>
                    <div class="mx-auto mt-10 grid max-w-3xl gap-4 text-sm text-white/90 sm:grid-cols-2 md:grid-cols-4">
                        <div class="flex items-center justify-center gap-2"><MapPin class="h-4 w-4" />{{ company.contact.address }}</div>
                        <div class="flex items-center justify-center gap-2"><Phone class="h-4 w-4" />{{ company.contact.phone }}</div>
                        <div class="flex items-center justify-center gap-2"><Mail class="h-4 w-4" />{{ company.contact.email }}</div>
                        <div class="flex items-center justify-center gap-2"><Clock class="h-4 w-4" />{{ company.contact.hours }}</div>
                    </div>
                </Reveal>
            </div>
        </section>
</template>
