<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import BlogController from '@/actions/App/Http/Controllers/Public/BlogController';

type Locale = 'pt_BR' | 'es' | 'en';

interface PaginationLink {
    url: string | null;
    label: string;
    active: boolean;
}

interface PostItem {
    id: number;
    title: string;
    slug: string;
    excerpt: string | null;
    published_at: string | null;
    category?: { id: number; name: string; slug: string } | null;
}

interface Paginated<T> {
    data: T[];
    links: PaginationLink[];
}

const props = defineProps<{
    locale: Locale;
    posts: Paginated<PostItem>;
    canonicalUrl: string;
    alternateUrls: Record<Locale, string>;
}>();

const titleByLocale: Record<Locale, string> = {
    pt_BR: 'Blog WMST',
    es: 'Blog WMST',
    en: 'WMST Blog',
};

const descriptionByLocale: Record<Locale, string> = {
    pt_BR: 'Conteudos sobre IA aplicada, produto digital, growth e automacao de processos.',
    es: 'Contenidos sobre IA aplicada, producto digital, growth y automatizacion de procesos.',
    en: 'Content about applied AI, digital product, growth, and process automation.',
};

const postUrl = (slug: string): string => {
    if (props.locale === 'pt_BR') {
        return BlogController.show['/blog/{slug}'].url({ slug });
    }

    return BlogController.show['/{locale}/blog/{slug}'].url({ locale: props.locale, slug });
};
</script>

<template>
    <Head :title="titleByLocale[locale]">
        <meta name="description" :content="descriptionByLocale[locale]" />
        <link rel="canonical" :href="canonicalUrl" />
        <link rel="alternate" hreflang="pt-BR" :href="alternateUrls.pt_BR" />
        <link rel="alternate" hreflang="es" :href="alternateUrls.es" />
        <link rel="alternate" hreflang="en" :href="alternateUrls.en" />
        <link rel="alternate" hreflang="x-default" :href="alternateUrls.pt_BR" />
    </Head>

    <section class="mx-auto max-w-7xl px-4 py-12 md:px-8 md:py-16">
        <div class="mb-8 flex items-end justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold">{{ titleByLocale[locale] }}</h1>
                <p class="mt-2 text-zinc-600">{{ descriptionByLocale[locale] }}</p>
            </div>
            <Link :href="locale === 'pt_BR' ? '/' : `/${locale}`" class="rounded-md border border-zinc-300 px-3 py-2 text-sm hover:bg-white">
                Voltar
            </Link>
        </div>

        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
            <article
                v-for="post in posts.data"
                :key="post.id"
                class="flex h-full flex-col rounded-xl border border-zinc-200 bg-white p-5 shadow-sm"
            >
                <p class="mb-2 text-xs uppercase tracking-wide text-zinc-500">{{ post.category?.name ?? 'Sem categoria' }}</p>
                <h2 class="text-lg font-semibold leading-tight">{{ post.title }}</h2>
                <p class="mt-2 line-clamp-3 text-sm text-zinc-600">{{ post.excerpt ?? '' }}</p>
                <Link :href="postUrl(post.slug)" class="mt-4 text-sm font-medium text-zinc-900 underline">
                    Ler artigo
                </Link>
            </article>
        </div>

        <div class="mt-8 flex flex-wrap gap-2">
            <button
                v-for="link in posts.links"
                :key="link.label"
                :disabled="!link.url"
                class="rounded border px-3 py-1 text-xs"
                :class="link.active ? 'border-zinc-900 bg-zinc-900 text-white' : 'border-zinc-300 bg-white'"
                v-html="link.label"
                @click="link.url && router.visit(link.url)"
            />
        </div>
    </section>
</template>
