<script setup lang="ts">
import type { Component } from 'vue';
import { onMounted, shallowRef } from 'vue';
import FallingBeamsBg from './FallingBeamsBg.vue';
import HexGridBg from './HexGridBg.vue';
import ParticleNetworkBg from './ParticleNetworkBg.vue';

/**
 * Sorteia um dos backgrounds animados a cada visita.
 * A escolha acontece no mount (client-side) para evitar mismatch de hidratação.
 */
const variants: Component[] = [ParticleNetworkBg, FallingBeamsBg, HexGridBg];

const chosen = shallowRef<Component | null>(null);

onMounted(() => {
    chosen.value = variants[Math.floor(Math.random() * variants.length)];
});
</script>

<template>
    <div class="pointer-events-none absolute inset-0" aria-hidden="true">
        <component :is="chosen" v-if="chosen" />
    </div>
</template>
