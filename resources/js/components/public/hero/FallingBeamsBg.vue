<script setup lang="ts">
/**
 * Feixes de luz verticais caindo em velocidades diferentes (chuva neon),
 * na paleta verde/azul da marca. CSS puro — estilos em app.css (.beam-fall).
 */
const palette = ['#35c24a', '#7ae58c', '#2fd4a6', '#4a8ddc', '#8bd4ff'];

const beams = Array.from({ length: 26 }, (_, id) => ({
    id,
    left: `${Math.random() * 100}%`,
    height: `${8 + Math.random() * 10}rem`,
    width: Math.random() > 0.5 ? '3px' : '2px',
    color: palette[Math.floor(Math.random() * palette.length)],
    duration: `${4.5 + Math.random() * 6}s`,
    // Delay negativo: cada feixe já começa no meio do trajeto (tela cheia desde o 1º frame).
    delay: `${-Math.random() * 10}s`,
    opacity: 0.3 + Math.random() * 0.45,
}));
</script>

<template>
    <div class="absolute inset-0 overflow-hidden">
        <span
            v-for="beam in beams"
            :key="beam.id"
            class="beam-fall absolute rounded-full"
            :style="{
                left: beam.left,
                height: beam.height,
                width: beam.width,
                color: beam.color,
                opacity: beam.opacity,
                animationDuration: beam.duration,
                animationDelay: beam.delay,
            }"
        />
    </div>
</template>
