<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { home } from '@/routes';

defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <div
        class="relative flex min-h-svh flex-col items-center justify-center overflow-hidden bg-background p-6 md:p-10 [--primary:#16233f] [--primary-foreground:#ffffff] [--ring:#16233f]"
    >
        <!-- Fundo de marca (navy/verde radial + grid) -->
        <div class="pointer-events-none absolute inset-0 bg-brand-radial" />
        <div
            class="pointer-events-none absolute inset-0 bg-brand-grid opacity-[0.25] [mask-image:radial-gradient(ellipse_at_center,black,transparent_70%)]"
        />

        <div class="relative w-full max-w-sm">
            <div class="flex flex-col gap-8">
                <div class="flex flex-col items-center gap-4">
                    <Link
                        :href="home()"
                        class="flex flex-col items-center gap-2 font-medium"
                    >
                        <img
                            src="/images/logotipo-wmst.png"
                            alt="WMST"
                            class="h-14 w-14 object-contain drop-shadow-sm"
                            width="56"
                            height="56"
                        />
                        <span class="sr-only">{{ title }}</span>
                    </Link>
                    <div class="space-y-1.5 text-center">
                        <h1 class="font-display text-2xl font-bold tracking-tight text-foreground">
                            {{ title }}
                        </h1>
                        <p class="text-center text-sm text-muted-foreground">
                            {{ description }}
                        </p>
                    </div>
                </div>

                <div
                    class="rounded-2xl border border-border bg-card/90 p-6 shadow-xl backdrop-blur supports-[backdrop-filter]:bg-card/80 md:p-7"
                >
                    <slot />
                </div>

                <p class="text-center text-xs text-muted-foreground">
                    &copy; WMST &mdash; W.M Soluções Tecnológicas
                </p>
            </div>
        </div>
    </div>
</template>
