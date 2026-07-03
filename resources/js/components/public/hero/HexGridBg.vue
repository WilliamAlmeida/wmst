<script setup lang="ts">
/**
 * Colmeia de hexágonos com células acendendo aleatoriamente em verde.
 * CSS puro — estilos em app.css (.hex-row / .hex-cell).
 */
const COLS = 30;
const ROWS = 10;

const rows = Array.from({ length: ROWS }, (_, row) => ({
    row,
    cells: Array.from({ length: COLS }, (_, col) => {
        const lit = Math.random() < 0.14;

        return {
            col,
            lit,
            delay: `${Math.random() * 8}s`,
            duration: `${3 + Math.random() * 5}s`,
        };
    }),
}));
</script>

<template>
    <div class="absolute inset-0 overflow-hidden">
        <div
            v-for="rowData in rows"
            :key="rowData.row"
            class="hex-row"
            :class="rowData.row % 2 === 1 ? 'hex-row-offset' : ''"
        >
            <span
                v-for="cell in rowData.cells"
                :key="cell.col"
                class="hex-cell"
                :class="cell.lit ? 'hex-cell-lit' : ''"
                :style="cell.lit ? { animationDelay: cell.delay, animationDuration: cell.duration } : undefined"
            />
        </div>
    </div>
</template>
