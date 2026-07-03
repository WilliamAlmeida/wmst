import { createInertiaApp } from '@inertiajs/vue3';
import { initializeTheme } from '@/composables/useAppearance';
import AppLayout from '@/layouts/AppLayout.vue';
import AuthLayout from '@/layouts/AuthLayout.vue';
import PublicLayout from '@/layouts/PublicLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    layout: (name) => {
        switch (true) {
            case name === 'Welcome':
                return null;
            // Páginas de chat têm layout próprio (foco total, sem header/footer).
            case name.startsWith('chat/'):
                return null;
            case name.startsWith('public/'):
                return PublicLayout;
            case name.startsWith('auth/'):
                return AuthLayout;
            case name.startsWith('settings/'):
                return [AppLayout, SettingsLayout];
            default:
                return AppLayout;
        }
    },
    progress: {
        color: '#4B5563',
    },
});

// Código só-de-browser: no SSR (Node) window/navigator não existem, então
// tudo que toca o DOM precisa ficar atrás desta guarda para não quebrar o render.
if (typeof window !== 'undefined') {
    // Define light/dark no carregamento.
    initializeTheme();

    // PWA: registra o service worker (só em produção, para não conflitar com o HMR do Vite).
    if (import.meta.env.PROD && 'serviceWorker' in navigator) {
        window.addEventListener('load', () => {
            void navigator.serviceWorker.register('/sw.js').catch(() => {
                // Falha no registro do SW não deve quebrar a aplicação.
            });
        });
    }
}
