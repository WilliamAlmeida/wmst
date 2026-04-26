<script setup lang="ts">
import type { Component } from 'vue';
import { ArrowRight } from 'lucide-vue-next';

defineProps<{
    icon: Component;
    title: string;
    highlight: string;
    description: string;
    badge: string;
    ctaLabel: string;
    ctaUrl: string;
}>();
</script>

<template>
    <article class="group relative flex h-full flex-col overflow-hidden rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:border-[color:var(--color-brand)]/40 hover:shadow-xl">
        <div class="mb-4 inline-flex h-11 w-11 items-center justify-center rounded-xl bg-gradient-to-br from-[color:var(--color-brand-soft)] to-[color:var(--color-brand-2-soft)] text-[color:var(--color-brand)] transition-transform group-hover:-rotate-6 group-hover:scale-110">
            <component :is="icon" class="h-5 w-5" />
        </div>
        <h3 class="font-display text-lg font-semibold text-zinc-900">{{ title }}</h3>
        <p class="mt-1 text-sm font-semibold text-[color:var(--color-brand)]">{{ highlight }}</p>
        <p class="mt-3 flex-1 text-sm leading-relaxed text-zinc-600">{{ description }}</p>
        <span class="mt-4 inline-flex w-fit rounded-md border border-[color:var(--color-brand)]/20 px-2 py-0.5 text-xs font-medium text-[color:var(--color-brand)]">
            {{ badge }}
        </span>
        <a
            :href="ctaUrl"
            target="_blank"
            rel="noopener noreferrer"
            class="mt-5 inline-flex items-center gap-2 text-sm font-medium text-zinc-900 transition hover:text-[color:var(--color-brand)]"
        >
            {{ ctaLabel }}
            <ArrowRight class="h-4 w-4 transition-transform group-hover:translate-x-1" />
        </a>
    </article>
</template>
