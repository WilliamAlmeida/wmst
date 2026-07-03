<script setup lang="ts">
import AnimatedHeroBg from './AnimatedHeroBg.vue';

/**
 * Capa de fallback para posts sem foto: fundo navy escuro com um dos
 * backgrounds animados (sorteado) e o wordmark WMST por cima.
 */
withDefaults(defineProps<{ size?: 'sm' | 'lg' }>(), { size: 'sm' });
</script>

<template>
    <div class="relative h-full w-full overflow-hidden bg-hero-dark">
        <AnimatedHeroBg />
        <div class="relative flex h-full w-full items-center justify-center">
            <span
                class="font-display font-bold gradient-text-light"
                :class="size === 'lg' ? 'text-4xl md:text-5xl' : 'text-2xl'"
            >
                WMST
            </span>
        </div>
    </div>
</template>
