<script setup lang="ts">
import type { Component } from 'vue';

defineProps<{
    icon: Component;
    metric: string;
    title: string;
    description: string;
    client: string;
}>();
</script>

<template>
    <div class="flex h-full flex-col rounded-2xl border border-zinc-200 bg-white p-6 text-center shadow-sm transition hover:-translate-y-1 hover:border-[color:var(--color-brand)]/40 hover:shadow-xl">
        <div class="mx-auto mb-4 inline-flex h-14 w-14 items-center justify-center rounded-full bg-gradient-to-br from-[color:var(--color-brand-soft)] to-[color:var(--color-brand-2-soft)] text-[color:var(--color-brand)]">
            <component :is="icon" class="h-7 w-7" />
        </div>
        <p class="font-display text-4xl font-bold">
            <span class="gradient-text">{{ metric }}</span>
        </p>
        <p class="mt-2 text-base font-semibold text-zinc-900">{{ title }}</p>
        <p class="mt-1 text-xs font-semibold uppercase tracking-wide text-[color:var(--color-brand)]">
            {{ client }}
        </p>
        <p class="mt-3 text-sm text-zinc-600">{{ description }}</p>
    </div>
</template>
