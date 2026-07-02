import type { Directive } from 'vue';

interface TiltEl extends HTMLElement {
    _tiltMove?: (e: MouseEvent) => void;
    _tiltLeave?: () => void;
}

interface TiltOptions {
    max?: number; // grau máximo de inclinação
    scale?: number; // leve zoom no hover
    perspective?: number;
}

/**
 * v-tilt — inclina o elemento em 3D seguindo o cursor (efeito de perspectiva).
 * Ex.: v-tilt ou v-tilt="{ max: 8, scale: 1.04 }".
 * Respeita prefers-reduced-motion (não ativa quando o usuário pede menos movimento).
 */
export const vTilt: Directive<TiltEl, TiltOptions | undefined> = {
    mounted(el, binding) {
        const reduce =
            typeof window !== 'undefined' &&
            window.matchMedia?.('(prefers-reduced-motion: reduce)').matches;
        if (reduce) return;

        const max = binding.value?.max ?? 9;
        const scale = binding.value?.scale ?? 1.03;
        const perspective = binding.value?.perspective ?? 900;

        el.style.transition = 'transform 300ms cubic-bezier(0.22, 1, 0.36, 1)';
        el.style.transformStyle = 'preserve-3d';
        el.style.willChange = 'transform';

        const move = (e: MouseEvent): void => {
            const rect = el.getBoundingClientRect();
            const px = (e.clientX - rect.left) / rect.width; // 0..1
            const py = (e.clientY - rect.top) / rect.height; // 0..1
            const rotateY = (px - 0.5) * 2 * max;
            const rotateX = -(py - 0.5) * 2 * max;
            el.style.transform = `perspective(${perspective}px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale(${scale})`;
        };
        const leave = (): void => {
            el.style.transform = `perspective(${perspective}px) rotateX(0deg) rotateY(0deg) scale(1)`;
        };

        el.addEventListener('mousemove', move, { passive: true });
        el.addEventListener('mouseleave', leave);
        el._tiltMove = move;
        el._tiltLeave = leave;
    },
    unmounted(el) {
        if (el._tiltMove) el.removeEventListener('mousemove', el._tiltMove);
        if (el._tiltLeave) el.removeEventListener('mouseleave', el._tiltLeave);
    },
};
