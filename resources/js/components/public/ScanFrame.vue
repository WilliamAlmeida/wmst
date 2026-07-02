<script setup lang="ts">
/**
 * ScanFrame — motivo de "mira" da marca WMST (4 colchetes verdes nos cantos).
 * Envolve imagens, mockups ou heros. Use com parcimônia: é um acento de marca.
 */
withDefaults(
    defineProps<{
        size?: 'sm' | 'md' | 'lg';
        inset?: boolean;
    }>(),
    {
        size: 'md',
        inset: false,
    },
);

// [ dimensão do L , espessura por lado ]
const dim = {
    sm: 'h-4 w-4',
    md: 'h-6 w-6',
    lg: 'h-9 w-9',
} as const;

const thickness = {
    sm: 'border-2',
    md: 'border-[3px]',
    lg: 'border-4',
} as const;

const offset = {
    sm: '-inset-1.5',
    md: '-inset-2.5',
    lg: '-inset-4',
} as const;
</script>

<template>
    <div class="relative">
        <slot />
        <div
            class="pointer-events-none absolute"
            :class="inset ? 'inset-0' : offset[size]"
        >
            <span :class="[dim[size], thickness[size], 'absolute left-0 top-0 rounded-tl border-b-0 border-r-0 border-brand-2']" />
            <span :class="[dim[size], thickness[size], 'absolute right-0 top-0 rounded-tr border-b-0 border-l-0 border-brand-2']" />
            <span :class="[dim[size], thickness[size], 'absolute bottom-0 left-0 rounded-bl border-r-0 border-t-0 border-brand-2']" />
            <span :class="[dim[size], thickness[size], 'absolute bottom-0 right-0 rounded-br border-l-0 border-t-0 border-brand-2']" />
        </div>
    </div>
</template>
