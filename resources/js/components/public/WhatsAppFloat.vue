<script setup lang="ts">
import { MessageCircle } from 'lucide-vue-next';

defineProps<{
    href: string;
}>();
</script>

<template>
    <a
        :href="href"
        target="_blank"
        rel="noopener noreferrer"
        aria-label="Fale no WhatsApp"
        class="fixed bottom-6 right-6 z-40 inline-flex h-14 w-14 items-center justify-center rounded-full bg-[#25D366] text-white shadow-2xl ring-4 ring-[#25D366]/20 transition hover:scale-110 hover:bg-[#1fb955]"
    >
        <MessageCircle class="h-6 w-6" />
        <span class="absolute inset-0 -z-10 animate-ping rounded-full bg-[#25D366] opacity-30" />
    </a>
</template>
