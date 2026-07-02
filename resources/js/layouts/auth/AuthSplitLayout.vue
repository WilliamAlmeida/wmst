<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { home } from '@/routes';

const page = usePage();
const name = page.props.name;

defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <div
        class="relative grid h-dvh flex-col items-center justify-center px-8 sm:px-0 lg:max-w-none lg:grid-cols-2 lg:px-0 [--primary:#16233f] [--primary-foreground:#ffffff] [--ring:#16233f]"
    >
        <div
            class="relative hidden h-full flex-col overflow-hidden p-10 text-white lg:flex"
        >
            <div class="absolute inset-0 bg-gradient-to-br from-(--wmst-navy) via-(--wmst-navy-700) to-(--wmst-navy-900)" />
            <div class="pointer-events-none absolute inset-0 bg-brand-grid opacity-[0.08]" />
            <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(60%_60%_at_20%_20%,rgba(53,194,74,0.18),transparent_60%)]" />
            <Link
                :href="home()"
                class="relative z-20 flex items-center gap-3 text-lg font-semibold"
            >
                <img src="/images/logotipo-wmst.png" alt="WMST" class="h-9 w-9 object-contain" width="36" height="36" />
                {{ name }}
            </Link>
            <div class="relative z-20 mt-auto">
                <img src="/images/logo-wmst.avif" alt="WMST — W.M Soluções Tecnológicas" class="w-56 rounded-2xl border border-white/10 bg-white/95 p-4 shadow-2xl" width="224" height="224" />
            </div>
        </div>
        <div class="lg:p-8">
            <div
                class="mx-auto flex w-full flex-col justify-center space-y-6 sm:w-[350px]"
            >
                <div class="flex flex-col space-y-2 text-center">
                    <h1 class="text-xl font-medium tracking-tight" v-if="title">
                        {{ title }}
                    </h1>
                    <p class="text-sm text-muted-foreground" v-if="description">
                        {{ description }}
                    </p>
                </div>
                <slot />
            </div>
        </div>
    </div>
</template>
