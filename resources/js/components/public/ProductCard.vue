<script setup lang="ts">
import { ArrowRight } from 'lucide-vue-next';

defineProps<{
    name: string;
    tagline: string;
    description: string;
    logo: string;
    bullets: string[];
    badge?: string;
    ctaUrl: string;
    ctaLabel?: string;
    reverse?: boolean;
}>();
</script>

<template>
    <article
        class="grid items-center gap-8 rounded-3xl border border-zinc-200 bg-white p-6 shadow-sm md:grid-cols-2 md:gap-12 md:p-10"
        :class="reverse ? 'md:[&>div:first-child]:order-2' : ''"
    >
        <div>
            <span
                v-if="badge"
                class="inline-flex rounded-full border border-[color:var(--color-brand)]/20 bg-[color:var(--color-brand-soft)] px-3 py-1 text-xs font-semibold text-[color:var(--color-brand)]"
            >
                {{ badge }}
            </span>
            <div class="mt-4 flex items-center gap-3">
                <img :src="logo" :alt="name" class="h-12 w-12 rounded-xl object-contain" width="48" height="48" />
                <div>
                    <h3 class="font-display text-2xl font-bold text-zinc-900">{{ name }}</h3>
                    <p class="text-sm text-zinc-500">{{ tagline }}</p>
                </div>
            </div>
            <p class="mt-4 text-base text-zinc-600">{{ description }}</p>
            <ul class="mt-5 grid gap-2 text-sm text-zinc-700">
                <li v-for="bullet in bullets" :key="bullet" class="flex items-start gap-2">
                    <span class="mt-2 inline-block h-1.5 w-1.5 shrink-0 rounded-full bg-gradient-to-r from-[color:var(--color-brand)] to-[color:var(--color-brand-2)]" />
                    <span>{{ bullet }}</span>
                </li>
            </ul>
            <a
                :href="ctaUrl"
                target="_blank"
                rel="noopener noreferrer"
                class="mt-6 inline-flex items-center gap-2 rounded-lg bg-zinc-900 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-zinc-700"
            >
                {{ ctaLabel ?? 'Conhecer' }}
                <ArrowRight class="h-4 w-4" />
            </a>
        </div>

        <div class="relative">
            <div class="absolute -inset-6 rounded-3xl bg-gradient-to-br from-[color:var(--color-brand-soft)] via-transparent to-[color:var(--color-brand-2-soft)] blur-2xl" />
            <div class="relative overflow-hidden rounded-2xl border border-zinc-200 bg-zinc-50 shadow-xl">
                <img
                    src="/images/mockup-dashboard.png"
                    :alt="`${name} mockup`"
                    class="h-full w-full object-cover"
                    loading="lazy"
                    width="800"
                    height="500"
                />
            </div>
        </div>
    </article>
</template>
