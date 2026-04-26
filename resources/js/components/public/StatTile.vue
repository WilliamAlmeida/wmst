<script setup lang="ts">
defineProps<{
    value: string;
    label: string;
}>();
</script>

<template>
    <div class="rounded-xl border border-zinc-200 bg-white/80 p-4 shadow-sm backdrop-blur">
        <p class="font-display text-2xl font-bold text-zinc-900 md:text-3xl">
            <span class="gradient-text">{{ value }}</span>
        </p>
        <p class="mt-1 text-xs font-medium uppercase tracking-wide text-zinc-500">{{ label }}</p>
    </div>
</template>
