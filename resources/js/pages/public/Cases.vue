<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import { TrendingUp, Clock, Target, ArrowRight, MessageCircle, CheckCircle2 } from 'lucide-vue-next';
import SectionHeading from '@/components/public/SectionHeading.vue';
import Reveal from '@/components/public/Reveal.vue';
import TestimonialCard from '@/components/public/TestimonialCard.vue';

interface CaseStudy {
    slug: string;
    icon: string;
    metric: string;
    segment: string;
    title: string;
    challenge: string;
    solution: string;
    result: string;
    tags: string[];
}

interface Testimonial { name: string; role: string; company: string; testimonial: string; }

const props = defineProps<{
    locale: string;
    cases: CaseStudy[];
    testimonials: Testimonial[];
    canonicalUrl: string;
    alternateUrls: Record<string, string>;
}>();

const iconMap: Record<string, unknown> = { TrendingUp, Clock, Target };

const copy = computed(() => {
    const map = {
        pt_BR: {
            title: 'Cases de sucesso WMST | Resultados reais com automacao e IA',
            description: 'Veja resultados reais de clientes WMST: aumento de conversoes, reducao de no-show e ROI de ate 500% com automacoes e produtos sob medida.',
            eyebrow: 'Cases de sucesso',
            heading: 'Resultados reais que',
            highlight: 'falam por nos',
            description2: 'Selecionamos cases que mostram o impacto direto das nossas solucoes em diferentes setores.',
            challenge: 'Desafio',
            solution: 'Solucao',
            result: 'Resultado',
            testimonialsTitle: 'O que dizem',
            testimonialsHighlight: 'nossos clientes',
            ctaTitle: 'Quer ser nosso proximo case?',
            ctaDescription: 'Fale com nosso time e descubra em 15 minutos como podemos transformar seu negocio.',
            ctaButton: 'Falar no WhatsApp',
            ctaContact: 'Outras formas de contato',
            contactPath: '/contato',
            whatsapp: 'https://wa.me/5512982184879?text=Olá, vi os cases da WMST e quero conversar',
        },
        es: {
            title: 'Casos de exito WMST | Resultados reales con automatizacion e IA',
            description: 'Mira resultados reales de clientes WMST: aumento de conversiones, reduccion de no-show y ROI hasta 500% con automatizaciones y productos a medida.',
            eyebrow: 'Casos de exito',
            heading: 'Resultados reales que',
            highlight: 'hablan por nosotros',
            description2: 'Seleccionamos casos que muestran el impacto directo de nuestras soluciones en diferentes sectores.',
            challenge: 'Desafio',
            solution: 'Solucion',
            result: 'Resultado',
            testimonialsTitle: 'Lo que dicen',
            testimonialsHighlight: 'nuestros clientes',
            ctaTitle: 'Quieres ser nuestro proximo caso?',
            ctaDescription: 'Habla con nuestro equipo y descubre en 15 minutos como transformar tu negocio.',
            ctaButton: 'Hablar por WhatsApp',
            ctaContact: 'Otras formas de contacto',
            contactPath: '/es/contato',
            whatsapp: 'https://wa.me/5512982184879?text=Hola, vi los casos de WMST y quiero conversar',
        },
        en: {
            title: 'WMST success stories | Real results with automation and AI',
            description: 'See real results from WMST clients: more conversions, fewer no-shows and up to 500% ROI with automation and custom products.',
            eyebrow: 'Success stories',
            heading: 'Real results that',
            highlight: 'speak for us',
            description2: 'We picked cases that show the direct impact of our solutions across different industries.',
            challenge: 'Challenge',
            solution: 'Solution',
            result: 'Result',
            testimonialsTitle: 'What our',
            testimonialsHighlight: 'clients say',
            ctaTitle: 'Want to be our next case study?',
            ctaDescription: 'Talk to our team and discover in 15 minutes how to transform your business.',
            ctaButton: 'Talk on WhatsApp',
            ctaContact: 'Other contact options',
            contactPath: '/en/contato',
            whatsapp: 'https://wa.me/5512982184879?text=Hi, I saw the WMST cases and want to chat',
        },
    } as const;
    return map[props.locale as keyof typeof map] ?? map.pt_BR;
});

const itemListSchema = computed(() => JSON.stringify({
    '@context': 'https://schema.org',
    '@type': 'ItemList',
    name: copy.value.heading,
    itemListElement: props.cases.map((c, i) => ({
        '@type': 'ListItem',
        position: i + 1,
        item: { '@type': 'Article', name: c.title, description: c.result, about: c.segment },
    })),
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
        <component :is="'script'" type="application/ld+json" v-html="itemListSchema" />
    </Head>

    <!-- HERO -->
        <section class="relative overflow-hidden bg-gradient-to-br from-zinc-950 via-zinc-900 to-zinc-950 py-24 text-white">
            <div class="absolute inset-0 opacity-30" style="background-image: radial-gradient(circle at 30% 20%, rgba(16, 185, 129, 0.4), transparent 50%), radial-gradient(circle at 70% 80%, rgba(99, 102, 241, 0.3), transparent 50%);"></div>
            <div class="relative mx-auto max-w-5xl px-4 text-center md:px-8">
                <Reveal>
                    <p class="mb-4 text-xs font-semibold uppercase tracking-[0.3em] text-[color:var(--color-brand-2)]">{{ copy.eyebrow }}</p>
                    <h1 class="font-display text-4xl font-bold leading-tight md:text-6xl">
                        {{ copy.heading }} <span class="gradient-text">{{ copy.highlight }}</span>
                    </h1>
                    <p class="mx-auto mt-6 max-w-3xl text-lg leading-relaxed text-zinc-300">{{ copy.description2 }}</p>
                </Reveal>
            </div>
        </section>

        <!-- CASES -->
        <section class="bg-white py-20">
            <div class="mx-auto max-w-6xl px-4 md:px-8">
                <div class="grid gap-8">
                    <Reveal v-for="(c, i) in cases" :key="c.slug" :delay="i * 100">
                        <article class="overflow-hidden rounded-3xl border border-zinc-200 bg-gradient-to-br from-white to-zinc-50 shadow-sm transition hover:-translate-y-1 hover:shadow-xl">
                            <div class="grid gap-0 lg:grid-cols-[280px_1fr]">
                                <!-- Metric -->
                                <div class="relative flex flex-col items-center justify-center bg-gradient-to-br from-[color:var(--color-brand)] to-[color:var(--color-brand-2)] p-8 text-white">
                                    <component :is="iconMap[c.icon]" class="h-10 w-10 opacity-80" />
                                    <p class="mt-4 font-display text-5xl font-bold md:text-6xl">{{ c.metric }}</p>
                                    <p class="mt-2 text-center text-sm font-medium uppercase tracking-wide text-white/90">{{ c.segment }}</p>
                                </div>

                                <!-- Body -->
                                <div class="p-6 md:p-8">
                                    <div class="flex flex-wrap gap-2">
                                        <span v-for="t in c.tags" :key="t" class="rounded-full bg-zinc-100 px-2.5 py-0.5 text-xs font-medium text-zinc-700">{{ t }}</span>
                                    </div>
                                    <h2 class="mt-3 font-display text-2xl font-bold text-zinc-900 md:text-3xl">{{ c.title }}</h2>
                                    <div class="mt-6 grid gap-4 md:grid-cols-3">
                                        <div>
                                            <p class="text-xs font-semibold uppercase tracking-wide text-rose-500">{{ copy.challenge }}</p>
                                            <p class="mt-1.5 text-sm leading-relaxed text-zinc-600">{{ c.challenge }}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs font-semibold uppercase tracking-wide text-amber-500">{{ copy.solution }}</p>
                                            <p class="mt-1.5 text-sm leading-relaxed text-zinc-600">{{ c.solution }}</p>
                                        </div>
                                        <div>
                                            <p class="flex items-center gap-1 text-xs font-semibold uppercase tracking-wide text-emerald-500">
                                                <CheckCircle2 class="h-3.5 w-3.5" /> {{ copy.result }}
                                            </p>
                                            <p class="mt-1.5 text-sm font-medium leading-relaxed text-zinc-800">{{ c.result }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </article>
                    </Reveal>
                </div>
            </div>
        </section>

        <!-- TESTIMONIALS -->
        <section class="bg-zinc-50 py-20">
            <div class="mx-auto max-w-6xl px-4 md:px-8">
                <SectionHeading :title="copy.testimonialsTitle + ' '" :highlight="copy.testimonialsHighlight" />
                <div class="mt-12 grid gap-6 md:grid-cols-3">
                    <Reveal v-for="(t, i) in testimonials" :key="t.name" :delay="i * 100">
                        <TestimonialCard v-bind="t" />
                    </Reveal>
                </div>
            </div>
        </section>

        <!-- CTA -->
        <section class="relative overflow-hidden bg-gradient-to-br from-[color:var(--color-brand)] via-[color:var(--color-brand-2)] to-[color:var(--color-brand)] py-20">
            <div class="relative mx-auto max-w-4xl px-4 text-center text-white md:px-8">
                <Reveal>
                    <h2 class="font-display text-3xl font-bold md:text-4xl">{{ copy.ctaTitle }}</h2>
                    <p class="mx-auto mt-4 max-w-2xl text-lg text-white/90">{{ copy.ctaDescription }}</p>
                    <div class="mt-8 flex flex-wrap justify-center gap-3">
                        <a :href="copy.whatsapp" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 rounded-lg bg-white px-6 py-3 text-sm font-semibold text-zinc-900 shadow-lg transition hover:-translate-y-0.5">
                            <MessageCircle class="h-5 w-5 text-[#25D366]" /> {{ copy.ctaButton }}
                        </a>
                        <Link :href="copy.contactPath" class="inline-flex items-center gap-2 rounded-lg border border-white/30 bg-white/10 px-6 py-3 text-sm font-semibold text-white backdrop-blur transition hover:bg-white/20">
                            {{ copy.ctaContact }} <ArrowRight class="h-4 w-4" />
                        </Link>
                    </div>
                </Reveal>
            </div>
        </section>
</template>
