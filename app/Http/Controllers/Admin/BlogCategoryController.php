<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreBlogCategoryRequest;
use App\Http\Requests\Admin\UpdateBlogCategoryRequest;
use App\Models\BlogCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class BlogCategoryController extends Controller
{
    public function store(StoreBlogCategoryRequest $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validated();

        $baseSlug = $validated['slug'] ?? Str::slug($validated['name']);
        $validated['slug'] = $this->uniqueSlug($baseSlug, $validated['locale']);

        $category = BlogCategory::query()->create($validated);

        if (! $request->expectsJson()) {
            return to_route('admin.blog-posts.index');
        }

        return response()->json($category, 201);
    }

    public function update(UpdateBlogCategoryRequest $request, BlogCategory $blogCategory): JsonResponse|RedirectResponse
    {
        $validated = $request->validated();

        if (array_key_exists('slug', $validated) && $validated['slug'] !== null) {
            $locale = $validated['locale'] ?? $blogCategory->locale;
            $validated['slug'] = $this->uniqueSlug($validated['slug'], $locale, $blogCategory->id);
        }

        if (array_key_exists('name', $validated) && ! array_key_exists('slug', $validated)) {
            $locale = $validated['locale'] ?? $blogCategory->locale;
            $validated['slug'] = $this->uniqueSlug(Str::slug($validated['name']), $locale, $blogCategory->id);
        }

        $blogCategory->update($validated);

        if (! $request->expectsJson()) {
            return to_route('admin.blog-posts.index');
        }

        return response()->json($blogCategory->fresh());
    }

    public function destroy(Request $request, BlogCategory $blogCategory): Response|RedirectResponse
    {
        $blogCategory->delete();

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

        while (BlogCategory::query()
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
