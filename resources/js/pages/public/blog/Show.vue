<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import BlogController from '@/actions/App/Http/Controllers/Public/BlogController';

type Locale = 'pt_BR' | 'es' | 'en';

interface Post {
    id: number;
    title: string;
    slug: string;
    excerpt: string | null;
    content: string;
    seo_title: string | null;
    seo_description: string | null;
    published_at: string | null;
    category?: { id: number; name: string; slug: string } | null;
    tags?: Array<{ id: number; name: string; slug: string }>;
    author?: { id: number; name: string } | null;
}

interface RelatedPost {
    id: number;
    title: string;
    slug: string;
    published_at: string | null;
}

const props = defineProps<{
    locale: Locale;
    post: Post;
    relatedPosts: RelatedPost[];
    canonicalUrl: string;
    alternateUrls: Record<Locale, string>;
    blogUrl: string;
}>();

const pageTitle = props.post.seo_title || props.post.title;
const pageDescription = props.post.seo_description || props.post.excerpt || props.post.title;

const articleSchema = JSON.stringify({
    '@context': 'https://schema.org',
    '@type': 'Article',
    headline: props.post.title,
    datePublished: props.post.published_at,
    author: {
        '@type': 'Person',
        name: props.post.author?.name ?? 'WMST',
    },
    publisher: {
        '@type': 'Organization',
        name: 'WMST',
    },
    description: pageDescription,
});

const relatedUrl = (slug: string): string => {
    if (props.locale === 'pt_BR') {
        return BlogController.show['/blog/{slug}'].url({ slug });
    }

    return BlogController.show['/{locale}/blog/{slug}'].url({ locale: props.locale, slug });
};
</script>

<template>
    <Head :title="pageTitle">
        <meta name="description" :content="pageDescription" />
        <link rel="canonical" :href="canonicalUrl" />
        <link rel="alternate" hreflang="pt-BR" :href="alternateUrls.pt_BR" />
        <link rel="alternate" hreflang="es" :href="alternateUrls.es" />
        <link rel="alternate" hreflang="en" :href="alternateUrls.en" />
        <link rel="alternate" hreflang="x-default" :href="alternateUrls.pt_BR" />
        <script type="application/ld+json" v-html="articleSchema" />
    </Head>

    <article class="mx-auto grid max-w-7xl gap-8 px-4 py-12 md:grid-cols-[1fr_320px] md:px-8 md:py-16">
        <div class="rounded-2xl border border-zinc-200 bg-white p-6 md:p-8">
            <Link :href="blogUrl" class="text-sm text-zinc-500 underline">← Blog</Link>
            <p class="mt-3 text-xs uppercase tracking-wide text-zinc-500">{{ post.category?.name ?? 'Sem categoria' }}</p>
            <h1 class="mt-2 text-3xl font-bold leading-tight">{{ post.title }}</h1>
            <p class="mt-3 text-sm text-zinc-600">{{ post.excerpt }}</p>

            <div class="prose prose-zinc mt-8 max-w-none">
                <p class="whitespace-pre-line leading-7 text-zinc-800">
                    {{ post.content }}
                </p>
            </div>

            <div class="mt-8 flex flex-wrap gap-2" v-if="post.tags?.length">
                <span
                    v-for="tag in post.tags"
                    :key="tag.id"
                    class="rounded-full border border-zinc-300 px-3 py-1 text-xs"
                >
                    {{ tag.name }}
                </span>
            </div>
        </div>

        <aside class="space-y-4">
            <section class="rounded-xl border border-zinc-200 bg-white p-4">
                <h2 class="text-sm font-semibold uppercase tracking-wide text-zinc-500">Relacionados</h2>
                <ul class="mt-3 grid gap-2">
                    <li v-for="item in relatedPosts" :key="item.id">
                        <Link :href="relatedUrl(item.slug)" class="text-sm font-medium text-zinc-800 hover:underline">
                            {{ item.title }}
                        </Link>
                    </li>
                </ul>
            </section>

            <section class="rounded-xl border border-zinc-200 bg-white p-4">
                <h2 class="text-sm font-semibold uppercase tracking-wide text-zinc-500">Fale com a WMST</h2>
                <p class="mt-2 text-sm text-zinc-600">Quer aplicar esse tema na sua operacao? Solicite um teste guiado com IA.</p>
                <Link :href="locale === 'pt_BR' ? '/' : `/${locale}`" class="mt-3 inline-block rounded-md bg-zinc-900 px-3 py-2 text-sm text-white">
                    Solicitar teste
                </Link>
            </section>
        </aside>
    </article>
</template>
