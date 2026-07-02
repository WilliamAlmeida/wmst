<script setup lang="ts">
import type { Component } from 'vue';
import { vSpotlight } from '@/directives/spotlight';

defineProps<{
    icon: Component;
    title: string;
    highlight?: string;
    description: string;
}>();
</script>

<template>
    <div v-spotlight class="group spotlight-card relative flex h-full flex-col rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-xl">
        <div class="mb-5 inline-flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-[color:var(--color-brand-soft)] to-[color:var(--color-brand-2-soft)] text-[color:var(--color-brand)] transition-transform group-hover:scale-110">
            <component :is="icon" class="h-6 w-6" />
        </div>
        <h3 class="font-display text-lg font-semibold text-zinc-900">{{ title }}</h3>
        <p v-if="highlight" class="mt-1 text-sm font-semibold text-[color:var(--color-brand)]">{{ highlight }}</p>
        <p class="mt-3 text-sm leading-relaxed text-zinc-600">{{ description }}</p>
    </div>
</template>
