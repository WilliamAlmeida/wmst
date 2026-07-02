<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import { Search, Tag as TagIcon, Clock, ArrowRight, Sparkles } from 'lucide-vue-next';
import Reveal from '@/components/public/Reveal.vue';
import SectionHeading from '@/components/public/SectionHeading.vue';
import { vSpotlight } from '@/directives/spotlight';

type Locale = 'pt_BR' | 'es' | 'en';

interface PaginationLink {
    url: string | null;
    label: string;
    active: boolean;
}

interface Category {
    id: number;
    name: string;
    slug: string;
    posts_count?: number;
}

interface PostItem {
    id: number;
    title: string;
    slug: string;
    excerpt: string | null;
    published_at: string | null;
    published_at_human: string | null;
    reading_time_minutes: number;
    featured_image_url: string | null;
    category?: Category | null;
    author?: { id: number; name: string } | null;
}

interface Paginated<T> {
    data: T[];
    links: PaginationLink[];
}

const props = defineProps<{
    locale: Locale;
    posts: Paginated<PostItem>;
    featured: PostItem | null;
    categories: Category[];
    filters: { category: string | null; q: string | null };
    canonicalUrl: string;
    alternateUrls: Record<Locale, string>;
}>();

const titleByLocale: Record<Locale, string> = {
    pt_BR: 'Blog WMST - IA, automação e produto digital',
    es: 'Blog WMST - IA, automatizacion y producto digital',
    en: 'WMST Blog - AI, automation and digital product',
};

const descriptionByLocale: Record<Locale, string> = {
    pt_BR: 'Conteúdos sobre IA aplicada, produto digital, growth e automação de processos.',
    es: 'Contenidos sobre IA aplicada, producto digital, growth y automatizacion de procesos.',
    en: 'Content about applied AI, digital product, growth, and process automation.',
};

const labels: Record<Locale, { searchPh: string; all: string; readMore: string; minutes: string; featured: string; pageTitle: string; pageSubtitle: string; categories: string; noResults: string }> = {
    pt_BR: { searchPh: 'Buscar artigos...', all: 'Todas', readMore: 'Ler artigo', minutes: 'min de leitura', featured: 'Destaque', pageTitle: 'Insights para acelerar seu negócio', pageSubtitle: 'Estrategia, IA aplicada, automações e bastidores de quem entrega resultado.', categories: 'Categorias', noResults: 'Nenhum artigo encontrado.' },
    es: { searchPh: 'Buscar articulos...', all: 'Todas', readMore: 'Leer articulo', minutes: 'min de lectura', featured: 'Destacado', pageTitle: 'Insights para acelerar tu negócio', pageSubtitle: 'Estrategia, IA aplicada, automatizacion y backstage de quienes entregan resultados.', categories: 'Categorias', noResults: 'Ningun articulo encontrado.' },
    en: { searchPh: 'Search articles...', all: 'All', readMore: 'Read article', minutes: 'min read', featured: 'Featured', pageTitle: 'Insights to accelerate your business', pageSubtitle: 'Strategy, applied AI, automation and stories from those who deliver results.', categories: 'Categories', noResults: 'No articles found.' },
};

const search = ref(props.filters.q ?? '');

const postUrl = (slug: string): string => {
    return props.locale === 'pt_BR' ? `/blog/${slug}` : `/${props.locale}/blog/${slug}`;
};

const blogIndexUrl = (params: Record<string, string | null>): string => {
    const base = props.locale === 'pt_BR' ? '/blog' : `/${props.locale}/blog`;

    const cleaned: Record<string, string> = {};
    Object.entries(params).forEach(([k, v]) => { if (v) { cleaned[k] = v; } });
    const qs = new URLSearchParams(cleaned).toString();

    return qs ? `${base}?${qs}` : base;
};

let timer: ReturnType<typeof setTimeout> | null = null;
watch(search, (val) => {
    if (timer) { clearTimeout(timer); }
    timer = setTimeout(() => {
        router.visit(blogIndexUrl({ category: props.filters.category, q: val }), {
            preserveState: true,
            preserveScroll: true,
            replace: true,
            only: ['posts', 'featured', 'filters'],
        });
    }, 350);
});

const blogSchema = JSON.stringify({
    '@context': 'https://schema.org',
    '@type': 'Blog',
    name: 'WMST Blog',
    url: props.canonicalUrl,
    description: descriptionByLocale[props.locale],
});
</script>

<template>
    <Head :title="titleByLocale[locale]">
        <meta name="description" :content="descriptionByLocale[locale]" />
        <link rel="canonical" :href="canonicalUrl" />
        <link rel="alternate" hreflang="pt-BR" :href="alternateUrls.pt_BR" />
        <link rel="alternate" hreflang="es" :href="alternateUrls.es" />
        <link rel="alternate" hreflang="en" :href="alternateUrls.en" />
        <link rel="alternate" hreflang="x-default" :href="alternateUrls.pt_BR" />
        <meta property="og:type" content="website" />
        <meta property="og:title" :content="titleByLocale[locale]" />
        <meta property="og:description" :content="descriptionByLocale[locale]" />
        <meta property="og:url" :content="canonicalUrl" />
        <meta property="og:image" content="/images/logo-wmst.png" />
        <meta name="twitter:card" content="summary_large_image" />
        <component :is="'script'" type="application/ld+json" v-html="blogSchema" />
    </Head>

    <!-- HERO -->
    <section class="relative overflow-hidden bg-brand-radial">
        <div class="pointer-events-none absolute inset-0 bg-brand-grid opacity-30 [mask-image:radial-gradient(ellipse_at_center,black,transparent_70%)]" />
        <div class="relative mx-auto max-w-7xl px-4 pt-16 pb-10 md:px-8 md:pt-24 md:pb-14">
            <Reveal>
                <span class="inline-flex items-center gap-2 rounded-full border border-[color:var(--color-brand)]/20 bg-[color:var(--color-brand-soft)] px-3 py-1 text-xs font-semibold text-[color:var(--color-brand)]">
                    <Sparkles class="h-3.5 w-3.5" />
                    Blog WMST
                </span>
                <h1 class="mt-4 max-w-3xl font-display text-4xl font-bold leading-tight md:text-5xl">
                    {{ labels[locale].pageTitle }}
                </h1>
                <p class="mt-4 max-w-2xl text-lg text-zinc-600">{{ labels[locale].pageSubtitle }}</p>

                <div class="mt-8 flex flex-col gap-3 md:flex-row md:items-center">
                    <div class="relative flex-1 max-w-xl">
                        <Search class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-zinc-400" />
                        <input
                            v-model="search"
                            type="search"
                            :placeholder="labels[locale].searchPh"
                            class="h-11 w-full rounded-lg border border-zinc-300 bg-white pl-9 pr-3 text-sm shadow-sm focus:border-[color:var(--color-brand)] focus:outline-none focus:ring-2 focus:ring-[color:var(--color-brand)]/20"
                        />
                    </div>
                </div>

                <div class="mt-5 flex flex-wrap gap-2">
                    <Link
                        :href="blogIndexUrl({ category: null, q: filters.q })"
                        class="rounded-full border px-3 py-1 text-xs font-medium transition"
                        :class="!filters.category ? 'border-[color:var(--color-brand)] bg-[color:var(--color-brand)] text-white' : 'border-zinc-300 bg-white text-zinc-700 hover:border-[color:var(--color-brand)]'"
                    >
                        {{ labels[locale].all }}
                    </Link>
                    <Link
                        v-for="cat in categories"
                        :key="cat.id"
                        :href="blogIndexUrl({ category: cat.slug, q: filters.q })"
                        class="inline-flex items-center gap-1.5 rounded-full border px-3 py-1 text-xs font-medium transition"
                        :class="filters.category === cat.slug ? 'border-[color:var(--color-brand)] bg-[color:var(--color-brand)] text-white' : 'border-zinc-300 bg-white text-zinc-700 hover:border-[color:var(--color-brand)]'"
                    >
                        <TagIcon class="h-3 w-3" />
                        {{ cat.name }}
                        <span class="opacity-60">{{ cat.posts_count }}</span>
                    </Link>
                </div>
            </Reveal>
        </div>
    </section>

    <!-- FEATURED -->
    <section v-if="featured && !filters.category && !filters.q" class="bg-white pb-12 pt-4">
        <div class="mx-auto max-w-7xl px-4 md:px-8">
            <Reveal>
                <Link v-spotlight :href="postUrl(featured.slug)" class="group spotlight-card grid gap-6 overflow-hidden rounded-3xl border border-zinc-200 bg-white shadow-sm transition hover:shadow-xl md:grid-cols-2">
                    <div class="relative aspect-[16/10] overflow-hidden bg-zinc-100 md:aspect-auto">
                        <img
                            v-if="featured.featured_image_url"
                            :src="featured.featured_image_url"
                            :alt="featured.title"
                            class="h-full w-full object-cover transition duration-500 group-hover:scale-105"
                            loading="eager"
                        />
                        <div v-else class="flex h-full w-full items-center justify-center bg-gradient-to-br from-[color:var(--color-brand-soft)] to-[color:var(--color-brand-2-soft)]">
                            <span class="font-display text-4xl gradient-text">WMST</span>
                        </div>
                        <span class="absolute left-4 top-4 inline-flex items-center gap-1 rounded-full bg-white/95 px-3 py-1 text-xs font-semibold text-[color:var(--color-brand)] shadow">
                            <Sparkles class="h-3 w-3" />
                            {{ labels[locale].featured }}
                        </span>
                    </div>
                    <div class="flex flex-col justify-center p-6 md:p-10">
                        <p v-if="featured.category" class="text-xs font-semibold uppercase tracking-wide text-[color:var(--color-brand)]">
                            {{ featured.category.name }}
                        </p>
                        <h2 class="mt-2 font-display text-2xl font-bold leading-tight text-zinc-900 md:text-3xl">
                            {{ featured.title }}
                        </h2>
                        <p class="mt-3 text-base text-zinc-600">{{ featured.excerpt }}</p>
                        <div class="mt-5 flex flex-wrap items-center gap-4 text-xs text-zinc-500">
                            <span v-if="featured.author">por {{ featured.author.name }}</span>
                            <span v-if="featured.published_at_human">{{ featured.published_at_human }}</span>
                            <span class="inline-flex items-center gap-1">
                                <Clock class="h-3 w-3" />
                                {{ featured.reading_time_minutes }} {{ labels[locale].minutes }}
                            </span>
                        </div>
                        <span class="mt-6 inline-flex items-center gap-2 text-sm font-semibold text-zinc-900 group-hover:text-[color:var(--color-brand)]">
                            {{ labels[locale].readMore }}
                            <ArrowRight class="h-4 w-4 transition-transform group-hover:translate-x-1" />
                        </span>
                    </div>
                </Link>
            </Reveal>
        </div>
    </section>

    <!-- POSTS GRID -->
    <section class="bg-zinc-50 py-16">
        <div class="mx-auto max-w-7xl px-4 md:px-8">
            <SectionHeading
                v-if="filters.category || filters.q"
                align="left"
                :title="filters.q ? `Resultados para \&quot;${filters.q}\&quot;` : 'Categoria'"
                :description="filters.category ? `Filtrando por: ${filters.category}` : ''"
            />
            <div v-if="posts.data.length === 0" class="rounded-2xl border border-dashed border-zinc-300 bg-white p-12 text-center text-zinc-500">
                {{ labels[locale].noResults }}
            </div>
            <div v-else class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                <Reveal v-for="(post, i) in posts.data" :key="post.id" :delay="(i % 3) * 80">
                    <Link v-spotlight :href="postUrl(post.slug)" class="group spotlight-card flex h-full flex-col overflow-hidden rounded-2xl border border-zinc-200 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-xl">
                        <div class="relative aspect-[16/9] overflow-hidden bg-zinc-100">
                            <img
                                v-if="post.featured_image_url"
                                :src="post.featured_image_url"
                                :alt="post.title"
                                class="h-full w-full object-cover transition duration-500 group-hover:scale-105"
                                loading="lazy"
                            />
                            <div v-else class="flex h-full w-full items-center justify-center bg-gradient-to-br from-[color:var(--color-brand-soft)] to-[color:var(--color-brand-2-soft)]">
                                <span class="font-display text-2xl gradient-text">WMST</span>
                            </div>
                        </div>
                        <div class="flex flex-1 flex-col p-5">
                            <p v-if="post.category" class="text-xs font-semibold uppercase tracking-wide text-[color:var(--color-brand)]">
                                {{ post.category.name }}
                            </p>
                            <h2 class="mt-2 font-display text-lg font-semibold leading-tight text-zinc-900 group-hover:text-[color:var(--color-brand)]">
                                {{ post.title }}
                            </h2>
                            <p class="mt-2 line-clamp-3 text-sm text-zinc-600">{{ post.excerpt }}</p>
                            <div class="mt-4 flex items-center justify-between border-t border-zinc-100 pt-3 text-xs text-zinc-500">
                                <span>{{ post.published_at_human }}</span>
                                <span class="inline-flex items-center gap-1">
                                    <Clock class="h-3 w-3" />
                                    {{ post.reading_time_minutes }} min
                                </span>
                            </div>
                        </div>
                    </Link>
                </Reveal>
            </div>

            <div v-if="posts.links.length > 3" class="mt-10 flex flex-wrap justify-center gap-2">
                <button
                    v-for="link in posts.links"
                    :key="link.label"
                    :disabled="!link.url"
                    class="min-w-9 rounded-md border px-3 py-1.5 text-xs transition disabled:cursor-not-allowed disabled:opacity-40"
                    :class="link.active ? 'border-[color:var(--color-brand)] bg-[color:var(--color-brand)] text-white' : 'border-zinc-300 bg-white hover:border-[color:var(--color-brand)]'"
                    v-html="link.label"
                    @click="link.url && router.visit(link.url, { preserveScroll: true })"
                />
            </div>
        </div>
    </section>
</template>
