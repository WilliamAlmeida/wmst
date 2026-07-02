<script setup lang="ts">
withDefaults(
    defineProps<{
        eyebrow?: string;
        title: string;
        highlight?: string;
        description?: string;
        align?: 'center' | 'left';
    }>(),
    {
        align: 'center',
    },
);
</script>

<template>
    <div :class="['mx-auto max-w-3xl', align === 'center' ? 'text-center' : 'text-left']">
        <p
            v-if="eyebrow"
            class="mb-3 text-xs font-semibold uppercase tracking-[0.2em] text-(--wmst-green-700)"
        >
            {{ eyebrow }}
        </p>
        <h2 class="font-display text-3xl font-bold leading-tight text-zinc-900 md:text-4xl">
            {{ title }}
            <span v-if="highlight" class="gradient-text">{{ highlight }}</span>
        </h2>
        <p v-if="description" class="mt-4 text-base text-zinc-600 md:text-lg">
            {{ description }}
        </p>
    </div>
</template>
