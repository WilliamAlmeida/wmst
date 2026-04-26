<?php

namespace App\Http\Controllers\Admin;

use App\Enums\BlogPostStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreBlogPostRequest;
use App\Http\Requests\Admin\UpdateBlogPostRequest;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\BlogTag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class BlogPostController extends Controller
{
    public function index(Request $request): JsonResponse|InertiaResponse
    {
        $request->validate([
            'locale' => ['nullable', 'string', Rule::in(['pt_BR', 'es', 'en'])],
            'status' => ['nullable', Rule::enum(BlogPostStatus::class)],
        ]);

        $query = BlogPost::query()
            ->with(['author:id,name', 'category:id,name'])
            ->latest();

        if ($locale = $request->string('locale')->toString()) {
            $query->where('locale', $locale);
        }

        if ($status = $request->string('status')->toString()) {
            $query->where('status', $status);
        }

        $posts = $query->paginate(15)->withQueryString();

        if ($request->expectsJson()) {
            return response()->json($posts);
        }

        return Inertia::render('admin/BlogPosts/Index', [
            'posts' => $posts,
            'categories' => BlogCategory::query()
                ->where('is_active', true)
                ->orderBy('name')
                ->get(['id', 'locale', 'name', 'slug']),
            'tags' => BlogTag::query()
                ->orderBy('name')
                ->get(['id', 'locale', 'name', 'slug']),
            'filters' => [
                'locale' => $request->input('locale'),
                'status' => $request->input('status'),
            ],
            'statuses' => [
                BlogPostStatus::Draft->value,
                BlogPostStatus::Scheduled->value,
                BlogPostStatus::Published->value,
            ],
        ]);
    }

    public function store(StoreBlogPostRequest $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validated();

        $slug = $validated['slug'] ?? Str::slug($validated['title']);
        $locale = $validated['locale'];
        $validated['slug'] = $this->uniqueSlug($slug, $locale);
        $validated['author_id'] = $request->user()->id;

        $tagIds = $validated['tag_ids'] ?? [];
        unset($validated['tag_ids']);

        if (($validated['status'] ?? null) === BlogPostStatus::Published->value && ! array_key_exists('published_at', $validated)) {
            $validated['published_at'] = now();
        }

        if (($validated['status'] ?? null) !== BlogPostStatus::Published->value) {
            $validated['published_at'] = null;
        }

        $blogPost = BlogPost::query()->create($validated);
        $blogPost->tags()->sync($tagIds);

        if (! $request->expectsJson()) {
            return to_route('admin.blog-posts.index');
        }

        return response()->json($blogPost->load(['tags:id,name', 'category:id,name']), 201);
    }

    public function edit(BlogPost $blogPost): InertiaResponse
    {
        return Inertia::render('admin/BlogPosts/Edit', [
            'post' => $blogPost->load(['category:id,name,locale', 'tags:id,name,locale']),
            'categories' => BlogCategory::query()
                ->where('is_active', true)
                ->orderBy('name')
                ->get(['id', 'locale', 'name', 'slug']),
            'tags' => BlogTag::query()
                ->orderBy('name')
                ->get(['id', 'locale', 'name', 'slug']),
            'statuses' => [
                BlogPostStatus::Draft->value,
                BlogPostStatus::Scheduled->value,
                BlogPostStatus::Published->value,
            ],
        ]);
    }

    public function update(UpdateBlogPostRequest $request, BlogPost $blogPost): JsonResponse|RedirectResponse
    {
        $validated = $request->validated();

        if (array_key_exists('slug', $validated) && $validated['slug'] !== null) {
            $locale = $validated['locale'] ?? $blogPost->locale;
            $validated['slug'] = $this->uniqueSlug($validated['slug'], $locale, $blogPost->id);
        }

        if (array_key_exists('title', $validated) && ! array_key_exists('slug', $validated)) {
            $locale = $validated['locale'] ?? $blogPost->locale;
            $validated['slug'] = $this->uniqueSlug(Str::slug($validated['title']), $locale, $blogPost->id);
        }

        if (($validated['status'] ?? $blogPost->status->value) === BlogPostStatus::Published->value
            && empty($validated['published_at'])
            && $blogPost->published_at === null) {
            $validated['published_at'] = now();
        }

        if (array_key_exists('status', $validated) && $validated['status'] !== BlogPostStatus::Published->value) {
            $validated['published_at'] = null;
        }

        $tagIds = $validated['tag_ids'] ?? null;
        unset($validated['tag_ids']);

        $blogPost->update($validated);

        if ($tagIds !== null) {
            $blogPost->tags()->sync($tagIds);
        }

        if (! $request->expectsJson()) {
            return to_route('admin.blog-posts.index');
        }

        return response()->json($blogPost->fresh()->load(['tags:id,name', 'category:id,name']));
    }

    public function destroy(Request $request, BlogPost $blogPost): Response|RedirectResponse
    {
        $blogPost->delete();

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
}
