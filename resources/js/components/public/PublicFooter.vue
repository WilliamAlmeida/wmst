<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { Mail, Phone, MapPin, Instagram, Facebook, Linkedin } from 'lucide-vue-next';
import BrandMark from './BrandMark.vue';

defineProps<{
    homePath: string;
    blogPath: string;
    productsPath: string;
    servicesPath: string;
    aboutPath: string;
    casesPath: string;
    contactPath: string;
}>();

const year = new Date().getFullYear();
</script>

<template>
    <footer class="relative mt-20 border-t border-zinc-200 bg-zinc-950 text-zinc-300">
        <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_20%_0%,rgba(0,102,204,0.18),transparent_45%),radial-gradient(circle_at_85%_15%,rgba(204,0,102,0.18),transparent_40%)]" />
        <div class="relative mx-auto grid max-w-7xl gap-10 px-4 py-14 md:grid-cols-4 md:px-8">
            <div>
                <BrandMark size="md" class="bg-white p-1 rounded-tl-lg rounded-br-lg" />
                <p class="mt-4 text-sm text-zinc-400">
                    Mais de 15 anos transformando negócios com IA aplicada, automações e sistemas sob medida.
                </p>
                <div class="mt-5 flex gap-3">
                    <a href="https://instagram.com" target="_blank" rel="noopener noreferrer" aria-label="Instagram" class="rounded-md border border-white/10 p-2 hover:bg-white/10">
                        <Instagram class="h-4 w-4" />
                    </a>
                    <a href="https://facebook.com" target="_blank" rel="noopener noreferrer" aria-label="Facebook" class="rounded-md border border-white/10 p-2 hover:bg-white/10">
                        <Facebook class="h-4 w-4" />
                    </a>
                    <a href="https://linkedin.com" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn" class="rounded-md border border-white/10 p-2 hover:bg-white/10">
                        <Linkedin class="h-4 w-4" />
                    </a>
                </div>
            </div>

            <div>
                <h3 class="text-xs font-semibold uppercase tracking-[0.2em] text-zinc-400">Produtos</h3>
                <ul class="mt-4 space-y-2 text-sm">
                    <li><Link :href="`${productsPath}/agenda-clinic`" class="hover:text-white">Agenda Clinic</Link></li>
                    <li><Link :href="`${productsPath}/ibox-delivery`" class="hover:text-white">IBOX Delivery</Link></li>
                    <li><Link :href="`${productsPath}/conecta`" class="hover:text-white">Conecta</Link></li>
                </ul>
            </div>

            <div>
                <h3 class="text-xs font-semibold uppercase tracking-[0.2em] text-zinc-400">Empresa</h3>
                <ul class="mt-4 space-y-2 text-sm">
                    <li><Link :href="homePath" class="hover:text-white">Home</Link></li>
                    <li><Link :href="aboutPath" class="hover:text-white">Sobre</Link></li>
                    <li><Link :href="casesPath" class="hover:text-white">Cases</Link></li>
                    <li><Link :href="servicesPath" class="hover:text-white">Solucoes</Link></li>
                    <li><Link :href="blogPath" class="hover:text-white">Blog</Link></li>
                    <li><Link :href="contactPath" class="hover:text-white">Contato</Link></li>
                </ul>
            </div>

            <div>
                <h3 class="text-xs font-semibold uppercase tracking-[0.2em] text-zinc-400">Contato</h3>
                <ul class="mt-4 space-y-3 text-sm">
                    <li class="flex items-center gap-2">
                        <Phone class="h-4 w-4 text-[color:var(--color-brand)]" />
                        <a href="tel:+5512982184879" class="hover:text-white">(12) 98218-4879</a>
                    </li>
                    <li class="flex items-center gap-2">
                        <Mail class="h-4 w-4 text-[color:var(--color-brand)]" />
                        <a href="mailto:contato@wmst.com.br" class="hover:text-white">contato@wmst.com.br</a>
                    </li>
                    <li class="flex items-center gap-2">
                        <MapPin class="h-4 w-4 text-[color:var(--color-brand)]" />
                        <span>Seg a Sex - 8h as 18h</span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="relative border-t border-white/10">
            <div class="mx-auto flex max-w-7xl flex-col items-center justify-between gap-3 px-4 py-5 text-xs text-zinc-500 md:flex-row md:px-8">
                <p>(c) {{ year }} WMST - W.M. Solucoes Tecnologicas. Todos os direitos reservados.</p>
                <div class="flex gap-4">
                    <a href="#" class="hover:text-zinc-200">Politica de Privacidade</a>
                    <a href="#" class="hover:text-zinc-200">Termos de Uso</a>
                </div>
            </div>
        </div>
    </footer>
</template>
