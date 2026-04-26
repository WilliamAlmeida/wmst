<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import { Menu, X, MessageCircle } from 'lucide-vue-next';
import BrandMark from './BrandMark.vue';

const props = defineProps<{
    homePath: string;
    blogPath: string;
    productsPath: string;
    servicesPath: string;
    aboutPath: string;
    casesPath: string;
    contactPath: string;
    isAuthenticated: boolean;
    whatsappUrl: string;
}>();

const open = ref(false);

const navLinks = computed(() => [
    { label: 'Produtos', href: props.productsPath, isLink: true },
    { label: 'Solucoes', href: props.servicesPath, isLink: true },
    { label: 'Cases', href: props.casesPath, isLink: true },
    { label: 'Sobre', href: props.aboutPath, isLink: true },
    { label: 'Blog', href: props.blogPath, isLink: true },
    { label: 'Contato', href: props.contactPath, isLink: true },
]);
</script>

<template>
    <header class="sticky top-0 z-40 border-b border-zinc-200/60 bg-white/85 backdrop-blur supports-[backdrop-filter]:bg-white/70">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3 md:px-8">
            <Link :href="homePath" class="flex items-center gap-3">
                <BrandMark size="md" />
                <span class="hidden text-xs text-zinc-500 sm:block">Software House &amp; Automacoes IA</span>
            </Link>

            <nav class="hidden items-center gap-7 md:flex">
                <template v-for="link in navLinks" :key="link.label">
                    <Link
                        v-if="link.isLink"
                        :href="link.href"
                        class="text-sm font-medium text-zinc-700 transition hover:text-[color:var(--color-brand)]"
                    >
                        {{ link.label }}
                    </Link>
                    <a
                        v-else
                        :href="link.href"
                        class="text-sm font-medium text-zinc-700 transition hover:text-[color:var(--color-brand)]"
                    >
                        {{ link.label }}
                    </a>
                </template>
            </nav>

            <div class="hidden items-center gap-3 md:flex">
                <Link
                    :href="isAuthenticated ? '/dashboard' : '/login'"
                    class="text-sm font-medium text-zinc-700 transition hover:text-[color:var(--color-brand)]"
                >
                    {{ isAuthenticated ? 'Dashboard' : 'Entrar' }}
                </Link>
                <a
                    :href="whatsappUrl"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="inline-flex items-center gap-2 rounded-lg bg-[#25D366] px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-[#1fb955]"
                >
                    <MessageCircle class="h-4 w-4" />
                    Fale no WhatsApp
                </a>
            </div>

            <button
                type="button"
                class="inline-flex h-10 w-10 items-center justify-center rounded-md border border-zinc-200 md:hidden"
                aria-label="Abrir menu"
                @click="open = !open"
            >
                <component :is="open ? X : Menu" class="h-5 w-5" />
            </button>
        </div>

        <div v-if="open" class="border-t border-zinc-200 bg-white md:hidden">
            <div class="mx-auto grid max-w-7xl gap-2 px-4 py-4">
                <template v-for="link in navLinks" :key="`m-${link.label}`">
                    <Link
                        v-if="link.isLink"
                        :href="link.href"
                        class="rounded-md px-3 py-2 text-sm font-medium text-zinc-700 hover:bg-zinc-100"
                        @click="open = false"
                    >
                        {{ link.label }}
                    </Link>
                    <a
                        v-else
                        :href="link.href"
                        class="rounded-md px-3 py-2 text-sm font-medium text-zinc-700 hover:bg-zinc-100"
                        @click="open = false"
                    >
                        {{ link.label }}
                    </a>
                </template>
                <Link
                    :href="isAuthenticated ? '/dashboard' : '/login'"
                    class="rounded-md px-3 py-2 text-sm font-medium text-zinc-700 hover:bg-zinc-100"
                    @click="open = false"
                >
                    {{ isAuthenticated ? 'Dashboard' : 'Entrar' }}
                </Link>
                <a
                    :href="whatsappUrl"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="mt-2 inline-flex items-center justify-center gap-2 rounded-lg bg-[#25D366] px-4 py-2.5 text-sm font-semibold text-white"
                >
                    <MessageCircle class="h-4 w-4" />
                    Fale no WhatsApp
                </a>
            </div>
        </div>
    </header>
</template>
