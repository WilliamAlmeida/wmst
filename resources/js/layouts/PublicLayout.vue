<script setup lang="ts">
import { Head, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import PublicHeader from '@/components/public/PublicHeader.vue';
import PublicFooter from '@/components/public/PublicFooter.vue';
import WhatsAppFloat from '@/components/public/WhatsAppFloat.vue';

const page = usePage<{ locale?: string; auth: { user?: { id: number } | null } }>();

const locale = computed(() => page.props.locale ?? 'pt_BR');
const homePath = computed(() => (locale.value === 'pt_BR' ? '/' : `/${locale.value}`));
const blogPath = computed(() => (locale.value === 'pt_BR' ? '/blog' : `/${locale.value}/blog`));
const productsPath = computed(() => (locale.value === 'pt_BR' ? '/produtos' : `/${locale.value}/produtos`));
const servicesPath = computed(() => (locale.value === 'pt_BR' ? '/solucoes' : `/${locale.value}/solucoes`));
const aboutPath = computed(() => (locale.value === 'pt_BR' ? '/sobre' : `/${locale.value}/sobre`));
const casesPath = computed(() => (locale.value === 'pt_BR' ? '/cases' : `/${locale.value}/cases`));
const contactPath = computed(() => (locale.value === 'pt_BR' ? '/contato' : `/${locale.value}/contato`));

const whatsappUrl = 'https://wa.me/5512982184879?text=Ol%C3%A1%2C%20gostaria%20de%20falar%20com%20a%20WMST';
</script>

<template>
    <Head>
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link rel="dns-prefetch" href="https://wa.me" />
        <link
            rel="stylesheet"
            href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Sora:wght@500;600;700;800&display=swap"
        />
        <meta name="theme-color" content="#16233f" />
        <meta name="format-detection" content="telephone=no" />
    </Head>

    <div class="min-h-screen bg-zinc-50 text-zinc-900 antialiased">
        <PublicHeader
            :home-path="homePath"
            :blog-path="blogPath"
            :products-path="productsPath"
            :services-path="servicesPath"
            :about-path="aboutPath"
            :cases-path="casesPath"
            :contact-path="contactPath"
            :is-authenticated="!!page.props.auth?.user"
            :whatsapp-url="whatsappUrl"
        />

        <main class="relative">
            <slot />
        </main>

        <PublicFooter :home-path="homePath" :blog-path="blogPath" :products-path="productsPath" :services-path="servicesPath" :about-path="aboutPath" :cases-path="casesPath" :contact-path="contactPath" />

        <WhatsAppFloat :href="whatsappUrl" />
    </div>
</template>
