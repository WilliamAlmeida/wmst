<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { computed } from 'vue';
import { MessageCircle, Phone, Mail, MapPin, Clock, Send, CheckCircle2 } from 'lucide-vue-next';
import SectionHeading from '@/components/public/SectionHeading.vue';
import Reveal from '@/components/public/Reveal.vue';

interface Contact { whatsapp: string; phone: string; email: string; address: string; hours: string; }
interface Service { slug: string; title: string; whatsapp: string; }

const props = defineProps<{
    locale: string;
    contact: Contact;
    services: Service[];
    canonicalUrl: string;
    alternateUrls: Record<string, string>;
}>();

const copy = computed(() => {
    const map = {
        pt_BR: {
            title: 'Contato WMST | Fale com nosso time em ate 15 minutos',
            description: 'Fale com a WMST por WhatsApp, telefone ou e-mail. Diagnostico gratuito e proposta personalizada em ate 24h.',
            eyebrow: 'Fale com a gente',
            heading: 'Vamos conversar sobre',
            highlight: 'seu proximo projeto',
            description2: 'Resposta humana em ate 15 minutos via WhatsApp em horario comercial. Atendimento 24/7 para clientes ativos.',
            channelsTitle: 'Canais',
            channelsHighlight: 'de atendimento',
            whatsappLabel: 'WhatsApp Business',
            whatsappDesc: 'Resposta em ate 15 minutos em horario comercial.',
            phoneLabel: 'Telefone',
            phoneDesc: 'Ligacao direta para falar com nosso comercial.',
            emailLabel: 'E-mail',
            emailDesc: 'Para propostas, parcerias e duvidas tecnicas.',
            addressLabel: 'Endereco',
            hoursLabel: 'Horario',
            quickTitle: 'Acesso rapido',
            quickHighlight: 'por solucao',
            quickDescription: 'Quer falar diretamente sobre uma solucao especifica? Escolha abaixo e ja inicie a conversa.',
            startNow: 'Iniciar conversa',
            ctaWhatsapp: 'Iniciar conversa no WhatsApp',
        },
        es: {
            title: 'Contacto WMST | Habla con nuestro equipo en 15 minutos',
            description: 'Habla con WMST por WhatsApp, telefono o email. Diagnostico gratuito y propuesta personalizada en 24h.',
            eyebrow: 'Habla con nosotros',
            heading: 'Conversemos sobre',
            highlight: 'tu proximo proyecto',
            description2: 'Respuesta humana en 15 minutos via WhatsApp en horario comercial. Atencion 24/7 para clientes activos.',
            channelsTitle: 'Canales',
            channelsHighlight: 'de atencion',
            whatsappLabel: 'WhatsApp Business',
            whatsappDesc: 'Respuesta en hasta 15 minutos en horario comercial.',
            phoneLabel: 'Telefono',
            phoneDesc: 'Llamada directa para hablar con comercial.',
            emailLabel: 'Email',
            emailDesc: 'Para propuestas, alianzas y dudas tecnicas.',
            addressLabel: 'Direccion',
            hoursLabel: 'Horario',
            quickTitle: 'Acceso rapido',
            quickHighlight: 'por solucion',
            quickDescription: 'Quieres hablar directamente sobre una solucion especifica? Elige abajo y empieza la conversacion.',
            startNow: 'Iniciar conversacion',
            ctaWhatsapp: 'Iniciar conversacion por WhatsApp',
        },
        en: {
            title: 'Contact WMST | Talk to our team within 15 minutes',
            description: 'Reach WMST by WhatsApp, phone or email. Free diagnostic and tailored proposal within 24h.',
            eyebrow: 'Get in touch',
            heading: "Let's talk about",
            highlight: 'your next project',
            description2: 'Human response within 15 minutes on WhatsApp during business hours. 24/7 support for active clients.',
            channelsTitle: 'Contact',
            channelsHighlight: 'channels',
            whatsappLabel: 'WhatsApp Business',
            whatsappDesc: 'Reply within 15 minutes during business hours.',
            phoneLabel: 'Phone',
            phoneDesc: 'Direct call to reach our sales team.',
            emailLabel: 'Email',
            emailDesc: 'For proposals, partnerships and technical questions.',
            addressLabel: 'Address',
            hoursLabel: 'Hours',
            quickTitle: 'Quick access',
            quickHighlight: 'by solution',
            quickDescription: 'Want to talk directly about a specific solution? Pick one and start the conversation.',
            startNow: 'Start conversation',
            ctaWhatsapp: 'Start conversation on WhatsApp',
        },
    } as const;
    return map[props.locale as keyof typeof map] ?? map.pt_BR;
});

const orgSchema = computed(() => JSON.stringify({
    '@context': 'https://schema.org',
    '@type': 'Organization',
    name: 'WMST',
    url: props.alternateUrls.pt_BR,
    address: { '@type': 'PostalAddress', addressLocality: 'Lorena', addressRegion: 'SP', addressCountry: 'BR' },
    contactPoint: [
        { '@type': 'ContactPoint', telephone: props.contact.phone, email: props.contact.email, contactType: 'sales', areaServed: 'BR', availableLanguage: ['Portuguese', 'Spanish', 'English'] },
    ],
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
        <section class="relative overflow-hidden bg-gradient-to-br from-zinc-950 via-zinc-900 to-zinc-950 py-24 text-white">
            <div class="absolute inset-0 opacity-30" style="background-image: radial-gradient(circle at 25% 25%, rgba(37, 211, 102, 0.4), transparent 50%), radial-gradient(circle at 75% 75%, rgba(99, 102, 241, 0.3), transparent 50%);"></div>
            <div class="relative mx-auto max-w-5xl px-4 text-center md:px-8">
                <Reveal>
                    <p class="mb-4 text-xs font-semibold uppercase tracking-[0.3em] text-[color:var(--color-brand-2)]">{{ copy.eyebrow }}</p>
                    <h1 class="font-display text-4xl font-bold leading-tight md:text-6xl">
                        {{ copy.heading }} <span class="gradient-text">{{ copy.highlight }}</span>
                    </h1>
                    <p class="mx-auto mt-6 max-w-3xl text-lg leading-relaxed text-zinc-300">{{ copy.description2 }}</p>
                    <a :href="contact.whatsapp" target="_blank" rel="noopener noreferrer" class="mt-8 inline-flex items-center gap-2 rounded-lg bg-[#25D366] px-6 py-3 text-sm font-semibold text-white shadow-lg transition hover:-translate-y-0.5 hover:bg-[#1fb955]">
                        <MessageCircle class="h-5 w-5" /> {{ copy.ctaWhatsapp }}
                    </a>
                </Reveal>
            </div>
        </section>

        <!-- CHANNELS -->
        <section class="bg-white py-20">
            <div class="mx-auto max-w-6xl px-4 md:px-8">
                <SectionHeading :title="copy.channelsTitle + ' '" :highlight="copy.channelsHighlight" />
                <div class="mt-12 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                    <Reveal>
                        <a :href="contact.whatsapp" target="_blank" rel="noopener noreferrer" class="group flex h-full flex-col rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:border-[#25D366]/40 hover:shadow-xl">
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-[#25D366] text-white">
                                <MessageCircle class="h-6 w-6" />
                            </div>
                            <h3 class="mt-4 font-display text-lg font-semibold text-zinc-900">{{ copy.whatsappLabel }}</h3>
                            <p class="mt-2 flex-1 text-sm leading-relaxed text-zinc-600">{{ copy.whatsappDesc }}</p>
                            <p class="mt-4 text-sm font-semibold text-[#25D366] group-hover:underline">{{ contact.phone }}</p>
                        </a>
                    </Reveal>
                    <Reveal :delay="80">
                        <a :href="`tel:${contact.phone.replace(/\D/g, '')}`" class="group flex h-full flex-col rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:border-[color:var(--color-brand)]/40 hover:shadow-xl">
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-[color:var(--color-brand)] to-[color:var(--color-brand-2)] text-white">
                                <Phone class="h-6 w-6" />
                            </div>
                            <h3 class="mt-4 font-display text-lg font-semibold text-zinc-900">{{ copy.phoneLabel }}</h3>
                            <p class="mt-2 flex-1 text-sm leading-relaxed text-zinc-600">{{ copy.phoneDesc }}</p>
                            <p class="mt-4 text-sm font-semibold text-[color:var(--color-brand)] group-hover:underline">{{ contact.phone }}</p>
                        </a>
                    </Reveal>
                    <Reveal :delay="160">
                        <a :href="`mailto:${contact.email}`" class="group flex h-full flex-col rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:border-[color:var(--color-brand-2)]/40 hover:shadow-xl">
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-[color:var(--color-brand-2)] to-[color:var(--color-brand)] text-white">
                                <Mail class="h-6 w-6" />
                            </div>
                            <h3 class="mt-4 font-display text-lg font-semibold text-zinc-900">{{ copy.emailLabel }}</h3>
                            <p class="mt-2 flex-1 text-sm leading-relaxed text-zinc-600">{{ copy.emailDesc }}</p>
                            <p class="mt-4 text-sm font-semibold text-[color:var(--color-brand-2)] group-hover:underline">{{ contact.email }}</p>
                        </a>
                    </Reveal>
                </div>

                <div class="mt-8 grid gap-4 rounded-2xl border border-zinc-200 bg-zinc-50 p-6 md:grid-cols-2">
                    <div class="flex items-start gap-3">
                        <MapPin class="mt-0.5 h-5 w-5 text-[color:var(--color-brand)]" />
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide text-zinc-500">{{ copy.addressLabel }}</p>
                            <p class="mt-0.5 text-sm font-medium text-zinc-800">{{ contact.address }}</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <Clock class="mt-0.5 h-5 w-5 text-[color:var(--color-brand-2)]" />
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide text-zinc-500">{{ copy.hoursLabel }}</p>
                            <p class="mt-0.5 text-sm font-medium text-zinc-800">{{ contact.hours }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- QUICK ACCESS BY SERVICE -->
        <section class="bg-zinc-50 py-20">
            <div class="mx-auto max-w-6xl px-4 md:px-8">
                <SectionHeading :title="copy.quickTitle + ' '" :highlight="copy.quickHighlight" :description="copy.quickDescription" />
                <div class="mt-12 grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                    <Reveal v-for="(s, i) in services" :key="s.slug" :delay="i * 60">
                        <a :href="s.whatsapp" target="_blank" rel="noopener noreferrer" class="group flex h-full items-center justify-between gap-3 rounded-xl border border-zinc-200 bg-white p-4 shadow-sm transition hover:-translate-y-0.5 hover:border-[#25D366]/40 hover:shadow-lg">
                            <div class="flex items-start gap-3">
                                <CheckCircle2 class="mt-0.5 h-5 w-5 shrink-0 text-emerald-500" />
                                <div>
                                    <p class="text-sm font-semibold text-zinc-900">{{ s.title }}</p>
                                    <p class="mt-0.5 text-xs text-zinc-500">{{ copy.startNow }}</p>
                                </div>
                            </div>
                            <Send class="h-4 w-4 text-[#25D366] transition group-hover:translate-x-0.5" />
                        </a>
                    </Reveal>
                </div>
            </div>
        </section>
</template>
