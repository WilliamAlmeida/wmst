<script setup lang="ts">
withDefaults(
    defineProps<{
        showWordmark?: boolean;
        size?: 'sm' | 'md' | 'lg';
    }>(),
    {
        showWordmark: true,
        size: 'md',
    },
);

const sizeMap = {
    sm: 'h-7 w-7',
    md: 'h-9 w-9',
    lg: 'h-12 w-12',
} as const;
</script>

<template>
    <span class="inline-flex items-center gap-2">
        <img
            src="/images/logotipo-wmst.png"
            alt="WMST"
            :class="sizeMap[size]"
            width="48"
            height="48"
            class="rounded-md object-contain"
        />
        <span v-if="showWordmark" class="font-display text-lg font-bold tracking-tight text-zinc-900 dark:text-white">
            WMST
        </span>
    </span>
</template>
