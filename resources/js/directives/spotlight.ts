import type { Directive } from 'vue';

/**
 * v-spotlight — rastreia o mouse dentro do elemento e expõe a posição
 * nas CSS vars --mx/--my. Combinar com as classes .spotlight-card
 * (borda iluminada) ou .spotlight-btn / .spotlight-btn-dark (fundo iluminado)
 * definidas em resources/css/app.css.
 */
const onMove = (event: MouseEvent): void => {
    const el = event.currentTarget as HTMLElement;
    const rect = el.getBoundingClientRect();
    el.style.setProperty('--mx', `${event.clientX - rect.left}px`);
    el.style.setProperty('--my', `${event.clientY - rect.top}px`);
};

export const vSpotlight: Directive<HTMLElement> = {
    mounted(el) {
        el.addEventListener('mousemove', onMove, { passive: true });
    },
    unmounted(el) {
        el.removeEventListener('mousemove', onMove);
    },
};
