<script setup lang="ts">
import { Star, Quote } from 'lucide-vue-next';

defineProps<{
    name: string;
    role: string;
    company: string;
    testimonial: string;
}>();
</script>

<template>
    <figure class="flex h-full flex-col rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:border-[color:var(--color-brand)]/40 hover:shadow-xl">
        <Quote class="h-7 w-7 text-[color:var(--color-brand)]/40" />
        <blockquote class="mt-4 flex-1 text-sm italic leading-relaxed text-zinc-700">
            &ldquo;{{ testimonial }}&rdquo;
        </blockquote>
        <div class="mt-5 flex items-center gap-3 border-t border-zinc-100 pt-4">
            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-[color:var(--color-brand)] to-[color:var(--color-brand-2)] text-sm font-semibold text-white">
                {{ name.charAt(0) }}
            </div>
            <figcaption>
                <p class="text-sm font-semibold text-zinc-900">{{ name }}</p>
                <p class="text-xs text-zinc-500">{{ role }} - {{ company }}</p>
            </figcaption>
            <div class="ml-auto flex gap-0.5">
                <Star v-for="i in 5" :key="i" class="h-3.5 w-3.5 fill-[color:var(--color-brand)] text-[color:var(--color-brand)]" />
            </div>
        </div>
    </figure>
</template>
