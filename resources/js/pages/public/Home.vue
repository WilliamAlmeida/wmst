<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import {
    Brain,
    MessageCircle,
    Workflow,
    Shield,
    Instagram,
    Database,
    Globe,
    Sparkles,
    ArrowRight,
    PlayCircle,
    TrendingUp,
    Clock,
    Target,
    CheckCircle2,
} from 'lucide-vue-next';

import Reveal from '@/components/public/Reveal.vue';
import SectionHeading from '@/components/public/SectionHeading.vue';
import StatTile from '@/components/public/StatTile.vue';
import FeatureCard from '@/components/public/FeatureCard.vue';
import ProductCard from '@/components/public/ProductCard.vue';
import ServiceCard from '@/components/public/ServiceCard.vue';
import CaseHighlight from '@/components/public/CaseHighlight.vue';
import TestimonialCard from '@/components/public/TestimonialCard.vue';
import TrialModal from '@/components/public/TrialModal.vue';

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
    whatsappBase: string;
    whatsappUrl: string;
}>();

const trialOpen = ref(false);

const wpp = (text: string): string => `${props.whatsappBase}${encodeURIComponent(text)}`;

const copy = computed(() => {
    const map: Record<Locale, { eyebrow: string; titleA: string; titleB: string; subtitle: string; ctaTrial: string; ctaWhats: string; blog: string }> = {
        pt_BR: {
            eyebrow: '+15 anos transformando negocios com tecnologia',
            titleA: 'Automatize, escale e venda mais com',
            titleB: 'IA aplicada',
            subtitle:
                'Especialistas em IA, automacoes de WhatsApp, Instagram e sistemas sob medida. Transformamos processos manuais em solucoes que economizam tempo e aumentam lucros.',
            ctaTrial: 'Testar agente IA',
            ctaWhats: 'Falar com a WMST',
            blog: 'Ver o blog',
        },
        es: {
            eyebrow: '+15 anos transformando negocios con tecnologia',
            titleA: 'Automatiza, escala y vende mas con',
            titleB: 'IA aplicada',
            subtitle:
                'Especialistas en IA, automatizacion de WhatsApp, Instagram y sistemas a medida. Transformamos procesos manuales en soluciones que ahorran tiempo y aumentan ganancias.',
            ctaTrial: 'Probar agente IA',
            ctaWhats: 'Hablar con WMST',
            blog: 'Ver el blog',
        },
        en: {
            eyebrow: '15+ years transforming businesses with technology',
            titleA: 'Automate, scale and sell more with',
            titleB: 'applied AI',
            subtitle:
                'Specialists in AI, WhatsApp and Instagram automation, and tailor-made systems. We turn manual processes into solutions that save time and increase profits.',
            ctaTrial: 'Try AI agent',
            ctaWhats: 'Talk to WMST',
            blog: 'Visit the blog',
        },
    };

    return map[props.locale];
});

const features = [
    { icon: Brain, title: 'IA Aplicada', highlight: '300% ROI medio', description: 'Inteligencia Artificial focada em resolver problemas reais do seu negocio.' },
    { icon: MessageCircle, title: 'Automacao Total', highlight: '85% menos trabalho manual', description: 'WhatsApp, Telegram e Instagram conectados ao seu cliente 24/7.' },
    { icon: Workflow, title: 'Processos Otimizados', highlight: '40h economizadas/semana', description: 'Fluxos inteligentes que eliminam gargalos operacionais.' },
    { icon: Shield, title: 'Tecnologia Robusta', highlight: '99.9% uptime garantido', description: 'Arquitetura escalavel, segura e pronta para crescer com voce.' },
];

const products = [
    {
        name: 'Agenda Clinic',
        tagline: 'Gestao para clinicas e operacao de atendimento',
        description: 'Sistema completo de agendamento com IA, lembretes automaticos, prontuario e gestao financeira para clinicas.',
        logo: '/images/logotipo-agendaclinic.png',
        bullets: ['Agenda inteligente com IA', 'Lembretes via WhatsApp', 'Prontuario eletronico', 'Financeiro integrado'],
        badge: 'Health-Tech',
        ctaUrl: 'https://wa.me/5512982184879?text=' + encodeURIComponent('Olá, quero conhecer o Agenda Clinic!'),
    },
    {
        name: 'IBOX Delivery',
        tagline: 'Pedidos, entrega e retencao para restaurantes',
        description: 'Plataforma white-label de delivery: cardapio digital, app de motoboy, painel de gestao e integracao com WhatsApp.',
        logo: '/images/logotipo-iboxdelivery.jpg',
        bullets: ['Cardapio digital responsivo', 'App de motoboy nativo', 'Pagamentos integrados', 'Programa de fidelidade'],
        badge: 'Food-Tech',
        ctaUrl: 'https://wa.me/5512982184879?text=' + encodeURIComponent('Olá, quero conhecer o IBOX Delivery!'),
    },
    {
        name: 'Conecta',
        tagline: 'Automacao de marketing, CRM e social selling',
        description: 'Centralize Instagram, WhatsApp e e-mail em um unico CRM com automacoes de IA para qualificar leads e vender mais.',
        logo: '/images/logotipo-conecta.png',
        bullets: ['Inbox unificado multicanal', 'Funis automaticos com IA', 'Disparos em massa', 'Relatorios em tempo real'],
        badge: 'Marketing-Tech',
        ctaUrl: 'https://wa.me/5512982184879?text=' + encodeURIComponent('Olá, quero conhecer o Conecta!'),
    },
];

const services = [
    { icon: MessageCircle, title: 'Automacao WhatsApp Business', highlight: '300% mais conversoes', description: 'Atendimento 24/7, qualificacao de leads e vendas sem intervencao humana.', badge: 'WhatsApp API + IA + N8N', ctaLabel: 'Quero turbinar meu WhatsApp', message: 'Olá, gostaria de saber mais sobre a Automação WhatsApp Business' },
    { icon: Instagram, title: 'Automacao Instagram', highlight: '500% mais leads qualificados', description: 'Captura de leads, respostas inteligentes e engajamento automatizado.', badge: 'Instagram API + IA', ctaLabel: 'Quero impulsionar meu Instagram', message: 'Olá, gostaria de saber mais sobre a Automação Instagram' },
    { icon: Workflow, title: 'Automacao de Processos', highlight: '40h economizadas/semana', description: 'Aprovacoes automaticas, integracoes e relatorios em tempo real.', badge: 'N8N + APIs + IA', ctaLabel: 'Quero automatizar meus processos', message: 'Olá, gostaria de saber mais sobre Automação de Processos Internos' },
    { icon: Database, title: 'Integracao de Sistemas Legados', highlight: 'Zero downtime', description: 'Conectamos sistemas antigos com novas tecnologias sem interromper operacoes.', badge: 'Laravel + REST + Microservicos', ctaLabel: 'Quero integrar meus sistemas', message: 'Olá, gostaria de saber mais sobre Integração de Sistemas' },
    { icon: Brain, title: 'IA para Analise Preditiva', highlight: '85% precisao em previsoes', description: 'Insights automaticos, previsoes precisas e decisoes baseadas em dados.', badge: 'OpenAI / Claude / Gemini', ctaLabel: 'Quero prever meus resultados', message: 'Olá, gostaria de saber mais sobre IA para Análise Preditiva' },
    { icon: Globe, title: 'Sistemas Web Sob Medida', highlight: '100% adequacao ao negocio', description: 'Plataformas customizadas, dashboards e portais de cliente completos.', badge: 'Laravel + Inertia + Vue', ctaLabel: 'Quero um sistema sob medida', message: 'Olá, gostaria de saber mais sobre Sistemas Web Sob Medida' },
];

const cases = [
    { icon: TrendingUp, metric: '300%', title: 'Aumento em conversoes', description: 'Chatbot IA qualifica leads e fecha vendas via WhatsApp.', client: 'E-commerce de Moda' },
    { icon: Clock, metric: '85%', title: 'Reducao no tempo de agendamentos', description: 'IA agenda consultas, envia lembretes e gerencia cancelamentos.', client: 'Clinica Medica' },
    { icon: Target, metric: '500%', title: 'ROI em automacoes', description: 'Eliminacao de 40h semanais de trabalho manual.', client: 'Empresa de Logistica' },
];

const testimonials = [
    { name: 'Carlos Silva', role: 'CEO', company: 'TechStart Solucoes', testimonial: 'A automacao de WhatsApp da WMST revolucionou nosso atendimento. Vendemos 24/7 sem precisar de equipe gigante. Recuperamos o investimento em 2 meses!' },
    { name: 'Dra. Ana Santos', role: 'Diretora', company: 'Clinica Vida & Saude', testimonial: 'O Agenda Clinic eliminou nossa fila de espera. A IA agenda tudo automaticamente e os pacientes adoram a praticidade.' },
    { name: 'Roberto Lima', role: 'Proprietario', company: 'Delivery Express', testimonial: 'O IBOX Delivery nos colocou no mesmo nivel dos grandes players. Sistema robusto, suporte excepcional e resultados desde o primeiro dia.' },
];

const organizationSchema = JSON.stringify({
    '@context': 'https://schema.org',
    '@type': 'Organization',
    name: 'WMST',
    url: props.alternateUrls.pt_BR,
    sameAs: [props.alternateUrls.pt_BR],
    description: copy.value.subtitle,
    contactPoint: {
        '@type': 'ContactPoint',
        telephone: '+55-12-98218-4879',
        contactType: 'sales',
        areaServed: 'BR',
        availableLanguage: ['pt-BR', 'es', 'en'],
    },
});

const serviceSchema = JSON.stringify({
    '@context': 'https://schema.org',
    '@type': 'Service',
    serviceType: 'Desenvolvimento de software, automacao e IA aplicada',
    provider: { '@type': 'Organization', name: 'WMST' },
});
</script>

<template>
    <Head :title="`WMST - ${copy.titleA} ${copy.titleB}`">
        <meta name="description" :content="copy.subtitle" />
        <link rel="canonical" :href="alternateUrls[locale]" />
        <link rel="alternate" hreflang="pt-BR" :href="alternateUrls.pt_BR" />
        <link rel="alternate" hreflang="es" :href="alternateUrls.es" />
        <link rel="alternate" hreflang="en" :href="alternateUrls.en" />
        <link rel="alternate" hreflang="x-default" :href="alternateUrls.pt_BR" />
        <meta property="og:type" content="website" />
        <meta property="og:title" :content="`WMST - ${copy.titleA} ${copy.titleB}`" />
        <meta property="og:description" :content="copy.subtitle" />
        <meta property="og:url" :content="alternateUrls[locale]" />
        <meta property="og:image" content="/images/logo-wmst.png" />
        <meta name="twitter:card" content="summary_large_image" />
        <component :is="'script'" type="application/ld+json" v-html="organizationSchema" />
        <component :is="'script'" type="application/ld+json" v-html="serviceSchema" />
    </Head>

    <!-- HERO -->
    <section class="relative overflow-hidden bg-brand-radial">
        <div class="pointer-events-none absolute inset-0 bg-brand-grid opacity-[0.35] [mask-image:radial-gradient(ellipse_at_center,black,transparent_70%)]" />
        <div class="relative mx-auto grid max-w-7xl items-center gap-12 px-4 py-16 md:grid-cols-2 md:gap-16 md:px-8 md:py-24 lg:py-32">
            <Reveal>
                <span class="inline-flex items-center gap-2 rounded-full border border-[color:var(--color-brand)]/20 bg-[color:var(--color-brand-soft)] px-3 py-1 text-xs font-semibold text-[color:var(--color-brand)]">
                    <Sparkles class="h-3.5 w-3.5" />
                    {{ copy.eyebrow }}
                </span>
                <h1 class="mt-5 font-display text-4xl font-bold leading-tight text-zinc-900 md:text-5xl lg:text-6xl">
                    {{ copy.titleA }}
                    <span class="gradient-text">{{ copy.titleB }}</span>
                </h1>
                <p class="mt-5 max-w-xl text-base text-zinc-600 md:text-lg">{{ copy.subtitle }}</p>

                <div class="mt-8 flex flex-wrap gap-3">
                    <button
                        type="button"
                        class="inline-flex items-center gap-2 rounded-lg bg-gradient-to-r from-[color:var(--color-brand)] to-[color:var(--color-brand-2)] px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-[color:var(--color-brand)]/20 transition hover:opacity-90"
                        @click="trialOpen = true"
                    >
                        <PlayCircle class="h-5 w-5" />
                        {{ copy.ctaTrial }}
                    </button>
                    <a
                        :href="whatsappUrl"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="inline-flex items-center gap-2 rounded-lg border border-zinc-300 bg-white px-5 py-3 text-sm font-semibold text-zinc-900 transition hover:border-[color:var(--color-brand)] hover:text-[color:var(--color-brand)]"
                    >
                        <MessageCircle class="h-5 w-5" />
                        {{ copy.ctaWhats }}
                    </a>
                    <Link
                        :href="blogUrls[locale]"
                        class="inline-flex items-center gap-2 px-2 py-3 text-sm font-medium text-zinc-700 underline-offset-4 hover:text-zinc-900 hover:underline"
                    >
                        {{ copy.blog }}
                        <ArrowRight class="h-4 w-4" />
                    </Link>
                </div>

                <div class="mt-10 grid grid-cols-2 gap-3 sm:grid-cols-4">
                    <StatTile v-for="m in metrics" :key="m.label" :value="m.value" :label="m.label" />
                </div>
            </Reveal>

            <Reveal class="relative" :delay="200">
                <div class="absolute -inset-6 rounded-3xl bg-gradient-to-br from-[color:var(--color-brand)]/30 via-transparent to-[color:var(--color-brand-2)]/30 blur-3xl" />
                <div class="relative overflow-hidden rounded-3xl border border-zinc-200 bg-white shadow-2xl">
                    <img
                        src="/images/mockup-dashboard.png"
                        alt="WMST dashboard mockup"
                        class="h-full w-full object-cover"
                        width="900"
                        height="600"
                        loading="eager"
                        fetchpriority="high"
                    />
                </div>
                <div class="absolute -bottom-4 -left-4 hidden rounded-2xl border border-zinc-200 bg-white px-4 py-3 shadow-xl md:block">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-emerald-100 text-emerald-700">
                            <CheckCircle2 class="h-5 w-5" />
                        </div>
                        <div>
                            <p class="text-xs text-zinc-500">+12 leads novos</p>
                            <p class="text-sm font-semibold text-zinc-900">nas ultimas 2h</p>
                        </div>
                    </div>
                </div>
            </Reveal>
        </div>
    </section>

    <!-- WHY US -->
    <section class="bg-white py-20">
        <div class="mx-auto max-w-7xl px-4 md:px-8">
            <Reveal>
                <SectionHeading
                    eyebrow="Por que escolher a WMST"
                    title="Resultados reais, "
                    highlight="nao apenas tecnologia"
                    description="Mais de 15 anos transformando ideias em solucoes que geram impacto mensuravel para nossos clientes."
                />
            </Reveal>
            <div class="mt-12 grid gap-6 md:grid-cols-2 lg:grid-cols-4">
                <Reveal v-for="(f, i) in features" :key="f.title" :delay="i * 100">
                    <FeatureCard v-bind="f" />
                </Reveal>
            </div>
        </div>
    </section>

    <!-- PRODUTOS / SISTEMAS -->
    <section id="sistemas" class="bg-zinc-50 py-20">
        <div class="mx-auto max-w-7xl px-4 md:px-8">
            <Reveal>
                <SectionHeading
                    eyebrow="Nossos sistemas"
                    title="Plataformas prontas para "
                    highlight="acelerar seu negocio"
                    description="Cada sistema foi desenvolvido para resolver problemas especificos e gerar resultados mensuraveis."
                />
            </Reveal>
            <div class="mt-14 space-y-12">
                <Reveal v-for="(p, i) in products" :key="p.name" :delay="i * 100">
                    <ProductCard v-bind="p" :reverse="i % 2 === 1" cta-label="Conhecer" />
                </Reveal>
            </div>
        </div>
    </section>

    <!-- SOLUCOES -->
    <section id="solucoes" class="bg-white py-20">
        <div class="mx-auto max-w-7xl px-4 md:px-8">
            <Reveal>
                <SectionHeading
                    eyebrow="Nossas solucoes"
                    title="Servicos que "
                    highlight="transformam negocios"
                    description="Nao vendemos tecnologia, entregamos solucoes completas que resolvem problemas reais."
                />
            </Reveal>
            <div class="mt-12 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                <Reveal v-for="(s, i) in services" :key="s.title" :delay="i * 80">
                    <ServiceCard
                        :icon="s.icon"
                        :title="s.title"
                        :highlight="s.highlight"
                        :description="s.description"
                        :badge="s.badge"
                        :cta-label="s.ctaLabel"
                        :cta-url="wpp(s.message)"
                    />
                </Reveal>
            </div>
        </div>
    </section>

    <!-- CASES -->
    <section id="cases" class="bg-zinc-50 py-20">
        <div class="mx-auto max-w-7xl px-4 md:px-8">
            <Reveal>
                <SectionHeading
                    eyebrow="Cases de sucesso"
                    title="Resultados reais para "
                    highlight="clientes reais"
                    description="Veja como nossas automacoes transformaram operacoes inteiras."
                />
            </Reveal>
            <div class="mt-12 grid gap-6 md:grid-cols-3">
                <Reveal v-for="(c, i) in cases" :key="c.title" :delay="i * 100">
                    <CaseHighlight v-bind="c" />
                </Reveal>
            </div>
            <div class="mt-12 grid gap-6 md:grid-cols-3">
                <Reveal v-for="(t, i) in testimonials" :key="t.name" :delay="i * 100">
                    <TestimonialCard v-bind="t" />
                </Reveal>
            </div>
        </div>
    </section>

    <!-- CTA TRIAL -->
    <section id="contato" class="relative overflow-hidden py-24">
        <div class="absolute inset-0 bg-gradient-to-br from-[color:var(--color-brand)] via-[#7a3ad6] to-[color:var(--color-brand-2)]" />
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_30%_30%,white,transparent_60%)] opacity-20" />
        <div class="relative mx-auto max-w-4xl px-4 text-center text-white md:px-8">
            <Reveal>
                <span class="inline-flex items-center gap-2 rounded-full border border-white/30 bg-white/10 px-3 py-1 text-xs font-semibold text-white">
                    <Sparkles class="h-3.5 w-3.5" />
                    Teste em 30 segundos
                </span>
                <h2 class="mt-5 font-display text-3xl font-bold leading-tight md:text-5xl">
                    Pronto para automatizar seu negocio com IA?
                </h2>
                <p class="mx-auto mt-4 max-w-2xl text-base text-white/85 md:text-lg">
                    Solicite um link expiravel de teste e converse com um dos nossos agentes IA em producao agora mesmo.
                </p>
                <div class="mt-8 flex flex-wrap items-center justify-center gap-3">
                    <button
                        type="button"
                        class="inline-flex items-center gap-2 rounded-lg bg-white px-5 py-3 text-sm font-semibold text-zinc-900 shadow-xl transition hover:bg-zinc-100"
                        @click="trialOpen = true"
                    >
                        <PlayCircle class="h-5 w-5" />
                        {{ copy.ctaTrial }}
                    </button>
                    <a
                        :href="whatsappUrl"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="inline-flex items-center gap-2 rounded-lg border border-white/40 bg-white/10 px-5 py-3 text-sm font-semibold text-white backdrop-blur transition hover:bg-white/20"
                    >
                        <MessageCircle class="h-5 w-5" />
                        {{ copy.ctaWhats }}
                    </a>
                </div>
            </Reveal>
        </div>
    </section>

    <TrialModal :open="trialOpen" :locale="locale" :active-agents="activeAgents" @close="trialOpen = false" />
</template>
