<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage<{ locale?: string; auth: { user?: { id: number } | null } }>();

const locale = computed(() => page.props.locale ?? 'pt_BR');
const homePath = computed(() => (locale.value === 'pt_BR' ? '/' : `/${locale.value}`));
const blogPath = computed(() => (locale.value === 'pt_BR' ? '/blog' : `/${locale.value}/blog`));
const dashboardPath = '/dashboard';
const loginPath = '/login';
</script>

<template>
    <div class="min-h-screen bg-zinc-50 text-zinc-900">
        <div class="pointer-events-none fixed inset-0 bg-[radial-gradient(circle_at_10%_10%,rgba(251,191,36,0.18),transparent_40%),radial-gradient(circle_at_90%_20%,rgba(14,165,233,0.18),transparent_35%)]" />

        <header class="sticky top-0 z-30 border-b border-zinc-200/80 bg-white/85 backdrop-blur">
            <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3 md:px-8">
                <Link :href="homePath" class="flex items-center gap-2">
                    <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-zinc-900 text-sm font-semibold text-white">W</span>
                    <span class="font-semibold tracking-wide">WMST</span>
                </Link>

                <nav class="flex items-center gap-4 text-sm">
                    <Link :href="homePath" class="hover:text-zinc-600">Home</Link>
                    <Link :href="blogPath" class="hover:text-zinc-600">Blog</Link>
                    <Link
                        :href="page.props.auth?.user ? dashboardPath : loginPath"
                        class="rounded-md border border-zinc-300 px-3 py-1.5 hover:bg-zinc-100"
                    >
                        {{ page.props.auth?.user ? 'Dashboard' : 'Entrar' }}
                    </Link>
                </nav>
            </div>
        </header>

        <main class="relative">
            <slot />
        </main>

        <footer class="relative border-t border-zinc-200 bg-white/90">
            <div class="mx-auto flex max-w-7xl flex-col gap-3 px-4 py-6 text-sm text-zinc-600 md:flex-row md:items-center md:justify-between md:px-8">
                <p>© {{ new Date().getFullYear() }} WMST. Solucoes digitais com foco em conversao.</p>
                <div class="flex items-center gap-3">
                    <Link :href="homePath" class="hover:text-zinc-900">Home</Link>
                    <Link :href="blogPath" class="hover:text-zinc-900">Blog</Link>
                </div>
            </div>
        </footer>
    </div>
</template>
