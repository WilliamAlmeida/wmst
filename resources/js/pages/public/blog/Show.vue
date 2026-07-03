<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { computed, onMounted, ref } from 'vue';
import { Clock, Calendar, ArrowLeft, ArrowRight, Share2, Sparkles, MessageCircle, ListTree, Eye, Pencil, Volume2, Pause, Play, Square } from 'lucide-vue-next';
import AnimatedHeroBg from '@/components/public/hero/AnimatedHeroBg.vue';
import PostCoverFallback from '@/components/public/hero/PostCoverFallback.vue';
import Reveal from '@/components/public/Reveal.vue';
import { useSeo } from '@/composables/useSeo';
import { useTextToSpeech } from '@/composables/useTextToSpeech';
import { vSpotlight } from '@/directives/spotlight';

type Locale = 'pt_BR' | 'es' | 'en';

interface TocItem {
    id: string;
    text: string;
    level: number;
}

interface Post {
    id: number;
    title: string;
    slug: string;
    excerpt: string | null;
    content_html: string;
    toc: TocItem[];
    reading_time_minutes: number;
    seo_title: string | null;
    seo_description: string | null;
    featured_image_url: string | null;
    published_at: string | null;
    published_at_human: string | null;
    category?: { id: number; name: string; slug: string } | null;
    tags?: Array<{ id: number; name: string; slug: string }>;
    author?: { id: number; name: string } | null;
}

interface RelatedPost {
    id: number;
    title: string;
    slug: string;
    excerpt: string | null;
    published_at_human: string | null;
    reading_time_minutes: number;
    featured_image_url: string | null;
    category?: { id: number; name: string; slug: string } | null;
}

const props = withDefaults(
    defineProps<{
        locale: Locale;
        post: Post;
        relatedPosts: RelatedPost[];
        canonicalUrl: string;
        alternateUrls: Record<Locale, string>;
        blogUrl: string;
        isPreview?: boolean;
    }>(),
    { isPreview: false },
);

const pageTitle = computed(() => props.post.seo_title || props.post.title);
const pageDescription = computed(() => props.post.seo_description || props.post.excerpt || props.post.title);

// Article e BreadcrumbList são renderizados server-side no blade (App\Support\Seo
// via BlogController), fora daqui para não duplicar o structured data.
const { absoluteImage } = useSeo();
const ogImage = computed(() => absoluteImage(props.post.featured_image_url));

const relatedUrl = (slug: string): string => {
    return props.locale === 'pt_BR' ? `/blog/${slug}` : `/${props.locale}/blog/${slug}`;
};

const activeAnchor = ref<string>('');

onMounted(() => {
    if (typeof IntersectionObserver === 'undefined') { return; }
    const headings = document.querySelectorAll<HTMLElement>('article h2[id], article h3[id]');
    const obs = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                activeAnchor.value = entry.target.id;
            }
        });
    }, { rootMargin: '-30% 0px -60% 0px', threshold: 0 });
    headings.forEach((h) => obs.observe(h));

    registerView();
});

// Registra 1 view por visitante a cada 24h (dedup via localStorage), nunca no preview.
const registerView = (): void => {
    if (props.isPreview || typeof window === 'undefined') { return; }

    const key = `wmst_post_view_${props.post.id}`;
    const dayMs = 24 * 60 * 60 * 1000;

    try {
        const last = Number(window.localStorage.getItem(key) ?? '0');
        if (Date.now() - last < dayMs) { return; }
    } catch {
        // localStorage indisponível (modo privado): conta a view mesmo assim.
    }

    const csrf = (document.querySelector('meta[name="csrf-token"]') as HTMLMetaElement | null)?.content ?? '';
    void fetch(`/blog/${props.post.id}/view`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': csrf, 'X-Requested-With': 'XMLHttpRequest' },
        credentials: 'same-origin',
        keepalive: true,
    })
        .then(() => {
            try { window.localStorage.setItem(key, String(Date.now())); } catch { /* ignora */ }
        })
        .catch(() => { /* silencioso: contagem de view não deve afetar a página */ });
};

// Leitura em voz alta (Web Speech API, grátis no navegador).
const speechLangs: Record<Locale, string> = { pt_BR: 'pt-BR', es: 'es-ES', en: 'en-US' };
const tts = useTextToSpeech(speechLangs[props.locale]);

const articleText = (): string => {
    const el = document.createElement('div');
    el.innerHTML = props.post.content_html;

    return [props.post.title, props.post.excerpt ?? '', el.textContent ?? ''].join('. ');
};

const toggleListen = (): void => tts.toggle(articleText());

const listenLabel = computed(() => {
    if (tts.status.value === 'playing') { return 'Pausar'; }
    if (tts.status.value === 'paused') { return 'Continuar'; }

    return 'Ouvir artigo';
});

const share = async (): Promise<void> => {
    if (typeof navigator !== 'undefined' && navigator.share) {
        await navigator.share({ title: props.post.title, url: props.canonicalUrl });
    } else if (typeof navigator !== 'undefined' && navigator.clipboard) {
        await navigator.clipboard.writeText(props.canonicalUrl);
    }
};
</script>

<template>
    <Head :title="isPreview ? `[Prévia] ${pageTitle}` : pageTitle">
        <meta v-if="isPreview" name="robots" content="noindex, nofollow" />
        <meta name="description" :content="pageDescription" />
        <link rel="canonical" :href="canonicalUrl" />
        <link rel="alternate" hreflang="pt-BR" :href="alternateUrls.pt_BR" />
        <link rel="alternate" hreflang="es" :href="alternateUrls.es" />
        <link rel="alternate" hreflang="en" :href="alternateUrls.en" />
        <link rel="alternate" hreflang="x-default" :href="alternateUrls.pt_BR" />
        <meta property="og:type" content="article" />
        <meta property="og:title" :content="pageTitle" />
        <meta property="og:description" :content="pageDescription" />
        <meta property="og:url" :content="canonicalUrl" />
        <meta property="og:image" :content="ogImage" />
        <meta property="og:site_name" content="WMST" />
        <meta name="twitter:card" content="summary_large_image" />
        <meta name="twitter:image" :content="ogImage" />
        <meta v-if="post.published_at" property="article:published_time" :content="post.published_at" />
    </Head>

    <!-- PREVIEW BANNER -->
    <div
        v-if="isPreview"
        class="sticky top-0 z-50 flex flex-wrap items-center justify-center gap-x-4 gap-y-1 border-b border-amber-300 bg-amber-100 px-4 py-2 text-sm text-amber-900"
    >
        <span class="inline-flex items-center gap-1.5 font-semibold">
            <Eye class="h-4 w-4" />
            Modo prévia — este post ainda não está publicado.
        </span>
        <a
            :href="`/dashboard/blog-posts/${post.id}/edit`"
            class="inline-flex items-center gap-1.5 rounded-md border border-amber-400 bg-white/70 px-2.5 py-1 text-xs font-medium hover:bg-white"
        >
            <Pencil class="h-3.5 w-3.5" />
            Editar post
        </a>
    </div>

    <!-- HEADER / COVER -->
    <section class="relative overflow-hidden bg-hero-dark text-white">
        <div class="pointer-events-none absolute inset-0 bg-brand-grid-light [mask-image:radial-gradient(ellipse_at_center,black,transparent_70%)]" />

        <!-- Background animado sorteado a cada visita (partículas / feixes / hexágonos) -->
        <AnimatedHeroBg />
        <div class="relative mx-auto max-w-4xl px-4 pt-12 pb-8 md:px-8 md:pt-20 md:pb-12">
            <Link :href="blogUrl" class="inline-flex items-center gap-1 text-sm text-zinc-300 hover:text-white">
                <ArrowLeft class="h-4 w-4" />
                Voltar para o blog
            </Link>
            <Reveal>
                <p v-if="post.category" class="mt-6 text-xs font-semibold uppercase tracking-[0.2em] text-brand-2">
                    {{ post.category.name }}
                </p>
                <h1 class="mt-3 font-display text-3xl font-bold leading-tight text-white md:text-5xl">
                    {{ post.title }}
                </h1>
                <p v-if="post.excerpt" class="mt-4 max-w-3xl text-lg text-zinc-300">{{ post.excerpt }}</p>

                <div class="mt-6 flex flex-wrap items-center gap-5 text-sm text-zinc-300">
                    <span v-if="post.author" class="inline-flex items-center gap-2">
                        <span class="flex h-8 w-8 items-center justify-center rounded-full bg-gradient-to-br from-[color:var(--color-brand-2)] to-[color:var(--color-brand)] text-xs font-semibold text-white">
                            {{ post.author.name.charAt(0) }}
                        </span>
                        <span class="font-medium text-white">{{ post.author.name }}</span>
                    </span>
                    <span class="inline-flex items-center gap-1.5">
                        <Calendar class="h-4 w-4" />
                        {{ post.published_at_human }}
                    </span>
                    <span class="inline-flex items-center gap-1.5">
                        <Clock class="h-4 w-4" />
                        {{ post.reading_time_minutes }} min de leitura
                    </span>
                    <span class="ml-auto inline-flex items-center gap-2">
                        <button
                            v-if="tts.supported.value"
                            type="button"
                            class="inline-flex items-center gap-1.5 rounded-md border border-white/20 bg-white/10 px-3 py-1.5 text-xs text-white backdrop-blur hover:border-[color:var(--color-brand-2)]"
                            :class="tts.status.value !== 'idle' ? 'border-[color:var(--color-brand-2)] text-[color:var(--color-brand-2)]' : ''"
                            @click="toggleListen"
                        >
                            <Pause v-if="tts.status.value === 'playing'" class="h-3.5 w-3.5" />
                            <Play v-else-if="tts.status.value === 'paused'" class="h-3.5 w-3.5" />
                            <Volume2 v-else class="h-3.5 w-3.5" />
                            {{ listenLabel }}
                        </button>
                        <button
                            v-if="tts.supported.value && tts.status.value !== 'idle'"
                            type="button"
                            title="Parar leitura"
                            class="inline-flex items-center rounded-md border border-white/20 bg-white/10 p-1.5 text-white backdrop-blur hover:border-red-400 hover:text-red-400"
                            @click="tts.stop"
                        >
                            <Square class="h-3.5 w-3.5" />
                        </button>
                        <button type="button" class="inline-flex items-center gap-1.5 rounded-md border border-white/20 bg-white/10 px-3 py-1.5 text-xs text-white backdrop-blur hover:border-[color:var(--color-brand-2)]" @click="share">
                            <Share2 class="h-3.5 w-3.5" />
                            Compartilhar
                        </button>
                    </span>
                </div>
            </Reveal>
        </div>

        <div v-if="post.featured_image_url" class="mx-auto max-w-5xl px-4 pb-10 md:px-8">
            <div class="relative overflow-hidden rounded-3xl border border-white/10 bg-white shadow-2xl">
                <img :src="post.featured_image_url" :alt="post.title" class="aspect-[16/9] w-full object-cover" loading="eager" fetchpriority="high" decoding="async" />
            </div>
        </div>
    </section>

    <!-- BODY + SIDEBAR -->
    <section class="bg-white py-12 md:py-16">
        <div class="mx-auto grid max-w-7xl gap-10 px-4 md:grid-cols-[1fr_280px] md:px-8">
            <article class="prose prose-zinc max-w-none prose-headings:font-display prose-headings:text-zinc-900 prose-h2:text-2xl prose-h3:text-xl prose-a:text-[color:var(--color-brand)] prose-a:no-underline hover:prose-a:underline prose-img:rounded-2xl prose-img:shadow-lg prose-pre:rounded-xl prose-pre:bg-zinc-900 prose-blockquote:border-l-4 prose-blockquote:border-[color:var(--color-brand)] prose-blockquote:not-italic prose-blockquote:bg-zinc-50 prose-blockquote:py-1 prose-blockquote:px-4 prose-blockquote:rounded-r-lg" v-html="post.content_html" />

            <aside class="space-y-6 md:sticky md:top-24 md:self-start">
                <section v-if="post.toc.length > 0" class="rounded-2xl border border-zinc-200 bg-white p-5 shadow-sm">
                    <h2 class="flex items-center gap-2 text-xs font-semibold uppercase tracking-[0.2em] text-zinc-500">
                        <ListTree class="h-3.5 w-3.5" />
                        Sumário
                    </h2>
                    <ul class="mt-3 space-y-1 text-sm">
                        <li v-for="item in post.toc" :key="item.id" :style="{ paddingLeft: `${(item.level - 2) * 12}px` }">
                            <a
                                :href="`#${item.id}`"
                                class="block border-l-2 py-1 pl-3 transition"
                                :class="activeAnchor === item.id ? 'border-[color:var(--color-brand)] font-semibold text-[color:var(--color-brand)]' : 'border-transparent text-zinc-600 hover:text-zinc-900'"
                            >
                                {{ item.text }}
                            </a>
                        </li>
                    </ul>
                </section>

                <section v-if="post.tags?.length" class="rounded-2xl border border-zinc-200 bg-white p-5 shadow-sm">
                    <h2 class="text-xs font-semibold uppercase tracking-[0.2em] text-zinc-500">Tags</h2>
                    <div class="mt-3 flex flex-wrap gap-2">
                        <span
                            v-for="tag in post.tags"
                            :key="tag.id"
                            class="rounded-full border border-brand/15 bg-brand-soft px-3 py-1 text-xs font-medium text-brand"
                        >
                            {{ tag.name }}
                        </span>
                    </div>
                </section>

                <section class="overflow-hidden rounded-2xl bg-gradient-to-br from-brand via-(--wmst-navy-700) to-brand-2 p-5 text-white shadow-xl">
                    <div class="flex items-center gap-2 text-xs font-semibold uppercase tracking-[0.2em] text-white/80">
                        <Sparkles class="h-3.5 w-3.5" />
                        Fale com a WMST
                    </div>
                    <p class="mt-3 text-sm text-white/90">Quer aplicar esse tema na sua operação? Solicite um teste guiado com IA.</p>
                    <Link
                        :href="locale === 'pt_BR' ? '/#contato' : `/${locale}#contato`"
                        class="mt-4 inline-flex items-center gap-2 rounded-lg bg-white px-3 py-2 text-xs font-semibold text-zinc-900 transition hover:bg-zinc-100"
                    >
                        <MessageCircle class="h-3.5 w-3.5" />
                        Solicitar teste
                    </Link>
                </section>
            </aside>
        </div>
    </section>

    <!-- AUTHOR -->
    <section v-if="post.author" class="bg-zinc-50 py-12">
        <div class="mx-auto max-w-4xl px-4 md:px-8">
            <div class="flex flex-col items-center gap-4 rounded-3xl border border-zinc-200 bg-white p-8 text-center shadow-sm md:flex-row md:items-start md:text-left">
                <span class="flex h-16 w-16 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-[color:var(--color-brand)] to-[color:var(--color-brand-2)] text-2xl font-semibold text-white">
                    {{ post.author.name.charAt(0) }}
                </span>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-zinc-500">Autor</p>
                    <p class="mt-1 font-display text-xl font-semibold text-zinc-900">{{ post.author.name }}</p>
                    <p class="mt-2 text-sm text-zinc-600">Especialista da WMST em IA aplicada e automação de processos.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- RELATED -->
    <section v-if="relatedPosts.length" class="bg-white py-16">
        <div class="mx-auto max-w-7xl px-4 md:px-8">
            <h2 class="font-display text-2xl font-bold text-zinc-900 md:text-3xl">Continue lendo</h2>
            <div class="mt-8 grid gap-6 md:grid-cols-3">
                <Link
                    v-for="related in relatedPosts"
                    v-spotlight
                    :key="related.id"
                    :href="relatedUrl(related.slug)"
                    class="group spotlight-card flex h-full flex-col overflow-hidden rounded-2xl border border-zinc-200 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-xl"
                >
                    <div class="relative aspect-[16/9] overflow-hidden bg-zinc-100">
                        <img v-if="related.featured_image_url" :src="related.featured_image_url" :alt="related.title" class="h-full w-full object-cover transition group-hover:scale-105" loading="lazy" decoding="async" />
                        <PostCoverFallback v-else />
                    </div>
                    <div class="flex flex-1 flex-col p-5">
                        <p v-if="related.category" class="text-xs font-semibold uppercase tracking-wide text-[color:var(--color-brand)]">{{ related.category.name }}</p>
                        <h3 class="mt-2 font-display text-base font-semibold leading-tight text-zinc-900 group-hover:text-[color:var(--color-brand)]">{{ related.title }}</h3>
                        <p class="mt-2 line-clamp-2 text-sm text-zinc-600">{{ related.excerpt }}</p>
                        <span class="mt-4 inline-flex items-center gap-1 text-xs font-semibold text-zinc-700 group-hover:text-[color:var(--color-brand)]">
                            Ler artigo
                            <ArrowRight class="h-3.5 w-3.5" />
                        </span>
                    </div>
                </Link>
            </div>
        </div>
    </section>
</template>
