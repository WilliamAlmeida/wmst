<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class BlogController extends Controller
{
    public function index(Request $request, ?string $locale = null): InertiaResponse
    {
        $activeLocale = $this->resolveLocale($locale);

        app()->setLocale($activeLocale);

        $posts = BlogPost::query()
            ->published()
            ->where('locale', $activeLocale)
            ->with(['category:id,name,slug'])
            ->orderByDesc('published_at')
            ->paginate(9)
            ->withQueryString();

        return Inertia::render('public/blog/Index', [
            'locale' => $activeLocale,
            'posts' => $posts,
            'canonicalUrl' => $activeLocale === 'pt_BR' ? url('/blog') : url('/'.$activeLocale.'/blog'),
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

        $relatedPosts = BlogPost::query()
            ->published()
            ->where('locale', $activeLocale)
            ->whereKeyNot($post->id)
            ->orderByDesc('published_at')
            ->limit(3)
            ->get(['id', 'title', 'slug', 'published_at']);

        $path = '/blog/'.$post->slug;

        return Inertia::render('public/blog/Show', [
            'locale' => $activeLocale,
            'post' => $post,
            'relatedPosts' => $relatedPosts,
            'canonicalUrl' => $activeLocale === 'pt_BR' ? url($path) : url('/'.$activeLocale.$path),
            'alternateUrls' => [
                'pt_BR' => url($path),
                'es' => url('/es'.$path),
                'en' => url('/en'.$path),
            ],
            'blogUrl' => $activeLocale === 'pt_BR' ? url('/blog') : url('/'.$activeLocale.'/blog'),
        ]);
    }

    private function resolveLocale(?string $locale): string
    {
        if (in_array($locale, ['es', 'en'], true)) {
            return $locale;
        }

        return 'pt_BR';
    }
}
