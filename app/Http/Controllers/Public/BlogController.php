<?php

namespace App\Http\Controllers\Public;

use App\Enums\BlogPostStatus;
use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Support\Seo;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class BlogController extends Controller
{
    public function index(Request $request, ?string $locale = null): InertiaResponse
    {
        $activeLocale = $this->resolveLocale($locale);

        app()->setLocale($activeLocale);

        $categorySlug = $request->string('category')->toString() ?: null;
        $search = $request->string('q')->toString() ?: null;

        $baseQuery = BlogPost::query()
            ->published()
            ->where('locale', $activeLocale);

        if ($categorySlug !== null) {
            $baseQuery->whereHas('category', fn ($q) => $q->where('slug', $categorySlug));
        }

        if ($search !== null && $search !== '') {
            $term = '%'.$search.'%';
            $baseQuery->where(function ($q) use ($term): void {
                $q->where('title', 'like', $term)
                    ->orWhere('excerpt', 'like', $term);
            });
        }

        $featured = (clone $baseQuery)
            ->with(['category:id,name,slug', 'author:id,name'])
            ->orderByDesc('published_at')
            ->first();

        $postsQuery = (clone $baseQuery)
            ->with(['category:id,name,slug'])
            ->orderByDesc('published_at');

        if ($featured !== null && $request->integer('page', 1) === 1 && $search === null && $categorySlug === null) {
            $postsQuery->whereKeyNot($featured->id);
        }

        $posts = $postsQuery
            ->paginate(9)
            ->withQueryString()
            ->through(fn (BlogPost $post): array => $this->transformCard($post));

        $categories = BlogCategory::query()
            ->withCount(['posts' => fn ($q) => $q->published()->where('locale', $activeLocale)])
            ->orderBy('name')
            ->get(['id', 'name', 'slug'])
            ->filter(fn (BlogCategory $cat): bool => ($cat->posts_count ?? 0) > 0)
            ->values();

        $blogUrl = $activeLocale === 'pt_BR' ? url('/blog') : url('/'.$activeLocale.'/blog');

        return Inertia::render('public/blog/Index', [
            'locale' => $activeLocale,
            'seo' => Seo::make([
                'title' => 'Blog WMST — IA, automação e produto digital',
                'description' => 'Conteúdos sobre IA aplicada, produto digital, growth e automação de processos para PMEs.',
                'url' => $blogUrl,
            ]),
            'posts' => $posts,
            'featured' => $featured ? $this->transformCard($featured, withAuthor: true) : null,
            'categories' => $categories,
            'filters' => [
                'category' => $categorySlug,
                'q' => $search,
            ],
            'canonicalUrl' => $blogUrl,
            'alternateUrls' => [
                'pt_BR' => url('/blog'),
                'es' => url('/es/blog'),
                'en' => url('/en/blog'),
            ],
        ]);
    }

    public function show(Request $request, string $localeOrSlug, ?string $slug = null): InertiaResponse
    {
        $activeLocale = $slug === null ? 'pt_BR' : $this->resolveLocale($localeOrSlug);
        $resolvedSlug = $slug ?? $localeOrSlug;

        app()->setLocale($activeLocale);

        $post = BlogPost::query()
            ->published()
            ->where('locale', $activeLocale)
            ->where('slug', $resolvedSlug)
            ->with(['author:id,name', 'category:id,name,slug', 'tags:id,name,slug'])
            ->firstOrFail();

        return $this->renderPostPage($post, $activeLocale, isPreview: false);
    }

    /**
     * Pré-visualização (somente para usuários autenticados do painel):
     * renderiza a página pública do post ignorando o escopo published,
     * permitindo revisar rascunhos e agendados antes de publicar.
     */
    public function preview(BlogPost $blogPost): InertiaResponse
    {
        $activeLocale = $this->resolveLocale($blogPost->locale);

        app()->setLocale($activeLocale);

        $blogPost->load(['author:id,name', 'category:id,name,slug', 'tags:id,name,slug']);

        return $this->renderPostPage($blogPost, $activeLocale, isPreview: true);
    }

    /**
     * Contabiliza 1 visualização do post. A deduplicação por visitante
     * (mesma pessoa não conta 2x em 24h) é feita no cliente via localStorage;
     * aqui apenas incrementamos, e somente para posts publicados.
     */
    public function registerView(BlogPost $blogPost): Response
    {
        if ($blogPost->status === BlogPostStatus::Published && $blogPost->published_at !== null) {
            $blogPost->increment('views_count');
        }

        return response()->noContent();
    }

    private function renderPostPage(BlogPost $post, string $activeLocale, bool $isPreview): InertiaResponse
    {
        [$contentHtml, $toc] = $this->renderContent($post->content);

        $relatedPosts = BlogPost::query()
            ->published()
            ->where('locale', $activeLocale)
            ->whereKeyNot($post->id)
            ->when(
                $post->blog_category_id !== null,
                fn ($q) => $q->where('blog_category_id', $post->blog_category_id),
            )
            ->with(['category:id,name,slug'])
            ->orderByDesc('published_at')
            ->limit(3)
            ->get(['id', 'title', 'slug', 'excerpt', 'content', 'published_at', 'featured_image_path', 'blog_category_id'])
            ->map(fn (BlogPost $p): array => $this->transformCard($p));

        $path = '/blog/'.$post->slug;
        $blogPath = '/blog';

        // Rascunho ainda não tem published_at; para o preview mostramos uma
        // data de referência (agendamento ou última edição) só para exibição.
        $displayDate = $post->published_at
            ?? ($isPreview ? ($post->scheduled_for ?? $post->updated_at) : null);

        $canonicalUrl = $activeLocale === 'pt_BR' ? url($path) : url('/'.$activeLocale.$path);
        $siteOrigin = rtrim(url('/'), '/');

        $ogImage = $post->featured_image_url;
        if (! $ogImage) {
            $ogImage = $siteOrigin.'/images/og-default.png';
        } elseif (! str_starts_with($ogImage, 'http://') && ! str_starts_with($ogImage, 'https://')) {
            $ogImage = $siteOrigin.'/'.ltrim($ogImage, '/');
        }

        $homeUrl = $activeLocale === 'pt_BR' ? $siteOrigin : $siteOrigin.'/'.$activeLocale;

        $schemas = [
            [
                '@context' => 'https://schema.org',
                '@type' => 'Article',
                'headline' => $post->title,
                'image' => [$ogImage],
                'datePublished' => $post->published_at?->toIso8601String(),
                'dateModified' => $post->updated_at?->toIso8601String(),
                'author' => ['@type' => 'Person', 'name' => $post->author?->name ?? 'WMST'],
                'publisher' => [
                    '@type' => 'Organization',
                    'name' => 'WMST',
                    'logo' => ['@type' => 'ImageObject', 'url' => $siteOrigin.'/images/logo-wmst.png'],
                ],
                'description' => $post->seo_description ?: $post->excerpt,
                'mainEntityOfPage' => ['@type' => 'WebPage', '@id' => $canonicalUrl],
            ],
            [
                '@context' => 'https://schema.org',
                '@type' => 'BreadcrumbList',
                'itemListElement' => [
                    ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => $homeUrl],
                    ['@type' => 'ListItem', 'position' => 2, 'name' => 'Blog', 'item' => $activeLocale === 'pt_BR' ? $siteOrigin.'/blog' : $siteOrigin.'/'.$activeLocale.'/blog'],
                    ['@type' => 'ListItem', 'position' => 3, 'name' => $post->title, 'item' => $canonicalUrl],
                ],
            ],
        ];

        return Inertia::render('public/blog/Show', [
            'locale' => $activeLocale,
            'isPreview' => $isPreview,
            'seo' => Seo::make([
                // seo_title já costuma vir com a marca; só adiciona no fallback.
                'title' => $post->seo_title ?: $post->title.' | WMST',
                'description' => $post->seo_description ?: $post->excerpt,
                'image' => $ogImage,
                'url' => $canonicalUrl,
                'type' => 'article',
                'publishedTime' => $post->published_at?->toIso8601String(),
                'schemas' => $isPreview ? [] : $schemas,
            ]),
            'post' => [
                'id' => $post->id,
                'title' => $post->title,
                'slug' => $post->slug,
                'excerpt' => $post->excerpt,
                'content_html' => $contentHtml,
                'toc' => $toc,
                'reading_time_minutes' => $post->reading_time_minutes,
                'seo_title' => $post->seo_title,
                'seo_description' => $post->seo_description,
                'featured_image_url' => $post->featured_image_url,
                'published_at' => $displayDate?->toIso8601String(),
                'published_at_human' => $displayDate?->translatedFormat('d \d\e F \d\e Y'),
                'category' => $post->category,
                'tags' => $post->tags,
                'author' => $post->author,
            ],
            'relatedPosts' => $relatedPosts,
            'canonicalUrl' => $canonicalUrl,
            'alternateUrls' => [
                'pt_BR' => url($path),
                'es' => url('/es'.$path),
                'en' => url('/en'.$path),
            ],
            'blogUrl' => $activeLocale === 'pt_BR' ? url($blogPath) : url('/'.$activeLocale.$blogPath),
        ]);
    }

    /**
     * @return array{0: string, 1: array<int, array{id: string, text: string, level: int}>}
     */
    private function renderContent(string $markdown): array
    {
        $html = (string) Str::markdown($markdown, [
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
        ]);

        $toc = [];
        $html = preg_replace_callback(
            '/<h([23])>(.*?)<\/h\1>/i',
            function (array $match) use (&$toc): string {
                $level = (int) $match[1];
                $text = trim(strip_tags($match[2]));
                $id = Str::slug($text);

                if ($id === '') {
                    return $match[0];
                }

                $toc[] = ['id' => $id, 'text' => $text, 'level' => $level];

                // Mantém o id (alvo do sumário lateral) mas sem <a>: o título
                // não deve virar link clicável — a navegação é feita pelo Sumário.
                return sprintf(
                    '<h%1$d id="%2$s" class="scroll-mt-24">%3$s</h%1$d>',
                    $level,
                    $id,
                    e($text),
                );
            },
            $html,
        );

        return [(string) $html, $toc];
    }

    /**
     * @return array<string, mixed>
     */
    private function transformCard(BlogPost $post, bool $withAuthor = false): array
    {
        $data = [
            'id' => $post->id,
            'title' => $post->title,
            'slug' => $post->slug,
            'excerpt' => $post->excerpt,
            'published_at' => $post->published_at?->toIso8601String(),
            'published_at_human' => $post->published_at?->translatedFormat('d M Y'),
            'reading_time_minutes' => $post->reading_time_minutes,
            'featured_image_url' => $post->featured_image_url,
            'category' => $post->relationLoaded('category') ? $post->category : null,
        ];

        if ($withAuthor) {
            $data['author'] = $post->relationLoaded('author') ? $post->author : null;
        }

        return $data;
    }

    private function resolveLocale(?string $locale): string
    {
        if (in_array($locale, ['es', 'en'], true)) {
            return $locale;
        }

        return 'pt_BR';
    }
}
