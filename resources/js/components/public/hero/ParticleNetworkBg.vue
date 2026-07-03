<script setup lang="ts">
import { onBeforeUnmount, onMounted, ref } from 'vue';

/**
 * Rede de partículas (constelação): pontos que flutuam e se conectam com
 * linhas quando próximos. Canvas puro, sem dependências.
 *
 * variant: 'dark' (verde sobre hero escuro) | 'light' (navy sobre fundo claro).
 */
const props = withDefaults(defineProps<{ variant?: 'dark' | 'light' }>(), { variant: 'dark' });

const palette = {
    dark: { dot: '122, 229, 140', line: '53, 194, 74', dotAlpha: 0.85, lineAlpha: 0.28 },
    light: { dot: '22, 35, 63', line: '31, 51, 88', dotAlpha: 0.5, lineAlpha: 0.16 },
} as const;

const canvas = ref<HTMLCanvasElement | null>(null);

interface Particle {
    x: number;
    y: number;
    vx: number;
    vy: number;
    r: number;
}

let raf = 0;
let observer: ResizeObserver | null = null;

onMounted(() => {
    const el = canvas.value;
    const ctx = el?.getContext('2d');

    if (!el || !ctx) {
        return;
    }

    const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const dpr = Math.min(window.devicePixelRatio || 1, 2);
    const linkDistance = 130;
    const colors = palette[props.variant];

    let width = 0;
    let height = 0;
    let particles: Particle[] = [];

    const resize = (): void => {
        const rect = el.parentElement?.getBoundingClientRect();
        width = rect?.width ?? window.innerWidth;
        height = rect?.height ?? 480;
        el.width = width * dpr;
        el.height = height * dpr;
        ctx.setTransform(dpr, 0, 0, dpr, 0, 0);

        const count = Math.min(90, Math.max(35, Math.round((width * height) / 16000)));
        particles = Array.from({ length: count }, () => ({
            x: Math.random() * width,
            y: Math.random() * height,
            vx: (Math.random() - 0.5) * 0.35,
            vy: (Math.random() - 0.5) * 0.35,
            r: 1.2 + Math.random() * 2.2,
        }));
    };

    const draw = (): void => {
        ctx.clearRect(0, 0, width, height);

        for (const p of particles) {
            p.x += p.vx;
            p.y += p.vy;

            if (p.x < -20) { p.x = width + 20; } else if (p.x > width + 20) { p.x = -20; }
            if (p.y < -20) { p.y = height + 20; } else if (p.y > height + 20) { p.y = -20; }
        }

        for (let i = 0; i < particles.length; i++) {
            for (let j = i + 1; j < particles.length; j++) {
                const a = particles[i];
                const b = particles[j];
                const dist = Math.hypot(a.x - b.x, a.y - b.y);

                if (dist < linkDistance) {
                    ctx.strokeStyle = `rgba(${colors.line}, ${(1 - dist / linkDistance) * colors.lineAlpha})`;
                    ctx.lineWidth = 1;
                    ctx.beginPath();
                    ctx.moveTo(a.x, a.y);
                    ctx.lineTo(b.x, b.y);
                    ctx.stroke();
                }
            }
        }

        for (const p of particles) {
            ctx.fillStyle = `rgba(${colors.dot}, ${colors.dotAlpha})`;
            ctx.beginPath();
            ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
            ctx.fill();
        }
    };

    const loop = (): void => {
        draw();
        raf = requestAnimationFrame(loop);
    };

    resize();
    observer = new ResizeObserver(resize);

    if (el.parentElement) {
        observer.observe(el.parentElement);
    }

    if (reducedMotion) {
        draw();
    } else {
        loop();
    }
});

onBeforeUnmount(() => {
    cancelAnimationFrame(raf);
    observer?.disconnect();
});
</script>

<template>
    <canvas ref="canvas" class="absolute inset-0 h-full w-full" />
</template>
