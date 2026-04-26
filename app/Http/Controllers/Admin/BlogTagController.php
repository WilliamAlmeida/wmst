<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreBlogTagRequest;
use App\Http\Requests\Admin\UpdateBlogTagRequest;
use App\Models\BlogTag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class BlogTagController extends Controller
{
    public function store(StoreBlogTagRequest $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validated();

        $baseSlug = $validated['slug'] ?? Str::slug($validated['name']);
        $validated['slug'] = $this->uniqueSlug($baseSlug, $validated['locale']);

        $tag = BlogTag::query()->create($validated);

        if (! $request->expectsJson()) {
            return to_route('admin.blog-posts.index');
        }

        return response()->json($tag, 201);
    }

    public function update(UpdateBlogTagRequest $request, BlogTag $blogTag): JsonResponse|RedirectResponse
    {
        $validated = $request->validated();

        if (array_key_exists('slug', $validated) && $validated['slug'] !== null) {
            $locale = $validated['locale'] ?? $blogTag->locale;
            $validated['slug'] = $this->uniqueSlug($validated['slug'], $locale, $blogTag->id);
        }

        if (array_key_exists('name', $validated) && ! array_key_exists('slug', $validated)) {
            $locale = $validated['locale'] ?? $blogTag->locale;
            $validated['slug'] = $this->uniqueSlug(Str::slug($validated['name']), $locale, $blogTag->id);
        }

        $blogTag->update($validated);

        if (! $request->expectsJson()) {
            return to_route('admin.blog-posts.index');
        }

        return response()->json($blogTag->fresh());
    }

    public function destroy(Request $request, BlogTag $blogTag): Response|RedirectResponse
    {
        $blogTag->delete();

        if (! $request->expectsJson()) {
            return to_route('admin.blog-posts.index');
        }

        return response()->noContent();
    }

    private function uniqueSlug(string $baseSlug, string $locale, ?int $ignoreId = null): string
    {
        $slug = $baseSlug !== '' ? $baseSlug : Str::random(8);
        $candidate = $slug;
        $counter = 1;

        while (BlogTag::query()
            ->where('locale', $locale)
            ->where('slug', $candidate)
            ->when($ignoreId !== null, fn ($query) => $query->whereKeyNot($ignoreId))
            ->exists()) {
            $candidate = $slug.'-'.$counter;
            $counter++;
        }

        return $candidate;
    }
}
