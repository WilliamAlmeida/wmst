<?php

namespace App\Mcp\Tools\Concerns;

use App\Models\BlogPost;
use App\Models\User;
use Illuminate\Support\Str;

trait InteractsWithBlog
{
    /**
     * Gera um slug único dentro do locale informado.
     */
    protected function uniqueSlug(string $baseSlug, string $locale, ?int $ignoreId = null): string
    {
        $slug = $baseSlug !== '' ? $baseSlug : Str::random(8);
        $candidate = $slug;
        $counter = 1;

        while (BlogPost::query()
            ->where('locale', $locale)
            ->where('slug', $candidate)
            ->when($ignoreId !== null, fn ($query) => $query->whereKeyNot($ignoreId))
            ->exists()) {
            $candidate = $slug.'-'.$counter;
            $counter++;
        }

        return $candidate;
    }

    /**
     * O autor padrão dos posts criados via MCP é o primeiro usuário (admin).
     * O servidor MCP local roda no console, sem usuário autenticado.
     */
    protected function resolveAuthorId(): ?int
    {
        return User::query()->orderBy('id')->value('id');
    }

    /**
     * URL pública do post por locale.
     */
    protected function publicUrl(BlogPost $post): string
    {
        return $post->locale === 'pt_BR'
            ? url("/blog/{$post->slug}")
            : url("/{$post->locale}/blog/{$post->slug}");
    }

    /**
     * Representação enxuta de um post para retorno das tools.
     *
     * @return array<string, mixed>
     */
    protected function summarize(BlogPost $post, bool $withContent = false): array
    {
        $post->loadMissing(['category:id,name', 'tags:id,name']);

        $data = [
            'id' => $post->id,
            'title' => $post->title,
            'slug' => $post->slug,
            'locale' => $post->locale,
            'status' => $post->status->value,
            'excerpt' => $post->excerpt,
            'category' => $post->category?->name,
            'blog_category_id' => $post->blog_category_id,
            'tags' => $post->tags->pluck('name')->all(),
            'seo_title' => $post->seo_title,
            'seo_description' => $post->seo_description,
            'scheduled_for' => $post->scheduled_for?->toDateTimeString(),
            'published_at' => $post->published_at?->toDateTimeString(),
            'public_url' => $this->publicUrl($post),
        ];

        if ($withContent) {
            $data['content'] = $post->content;
        }

        return $data;
    }
}
