<?php

namespace App\Http\Controllers\Admin;

use App\Ai\Services\BlogPostAgentService;
use App\Enums\BlogPostStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AnalyzeBlogPostsRequest;
use App\Http\Requests\Admin\GenerateBlogPostDraftRequest;
use App\Http\Requests\Admin\ImproveBlogPostRequest;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class BlogPostAgentController extends Controller
{
    public function __construct(private BlogPostAgentService $blogPostAgentService) {}

    public function index(Request $request): InertiaResponse
    {
        $request->validate([
            'locale' => ['nullable', Rule::in(['pt_BR', 'es', 'en'])],
        ]);

        $locale = $request->string('locale')->toString();

        $posts = BlogPost::query()
            ->with('category:id,name')
            ->when($locale !== '', fn ($query) => $query->where('locale', $locale))
            ->latest()
            ->limit(25)
            ->get([
                'id',
                'blog_category_id',
                'locale',
                'title',
                'status',
                'published_at',
                'updated_at',
            ]);

        $categories = BlogCategory::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'locale', 'name', 'slug']);

        return Inertia::render('admin/BlogPosts/PostingAgent', [
            'posts' => $posts,
            'categories' => $categories,
            'activeLocale' => $locale !== '' ? $locale : null,
            'stats' => [
                'total_posts' => BlogPost::query()->count(),
                'published_posts' => BlogPost::query()->where('status', BlogPostStatus::Published->value)->count(),
                'draft_posts' => BlogPost::query()->where('status', BlogPostStatus::Draft->value)->count(),
                'categories' => $categories->count(),
            ],
        ]);
    }

    public function analyze(AnalyzeBlogPostsRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $posts = BlogPost::query()
            ->with('category:id,name,locale')
            ->when(
                ! empty($validated['locale'] ?? null),
                fn ($query) => $query->where('locale', $validated['locale'])
            )
            ->latest()
            ->limit(30)
            ->get([
                'id',
                'blog_category_id',
                'locale',
                'title',
                'excerpt',
                'status',
                'published_at',
            ]);

        $categories = BlogCategory::query()
            ->orderBy('name')
            ->get(['id', 'locale', 'name', 'slug']);

        return response()->json(
            $this->blogPostAgentService->analyze($posts, $categories, $validated['focus'] ?? null)
        );
    }

    public function generate(GenerateBlogPostDraftRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $normalizedInput = [
            'locale' => $validated['locale'],
            'title_hint' => $validated['title_hint'],
            'objective' => $validated['objective'] ?? '',
            'target_audience' => $validated['target_audience'] ?? '',
            'blog_category_id' => $validated['blog_category_id'] ?? null,
        ];

        $posts = BlogPost::query()
            ->with('category:id,name,locale')
            ->where('locale', $validated['locale'])
            ->latest()
            ->limit(30)
            ->get([
                'id',
                'blog_category_id',
                'locale',
                'title',
                'excerpt',
                'status',
                'published_at',
            ]);

        $categories = BlogCategory::query()
            ->orderBy('name')
            ->get(['id', 'locale', 'name', 'slug']);

        $result = $this->blogPostAgentService->generateDraft($posts, $categories, $normalizedInput);

        $draft = null;

        if (($validated['save_as_draft'] ?? false) === true) {
            $suggestion = $result['suggestion'];

            $draftPost = BlogPost::query()->create([
                'author_id' => $request->user()->id,
                'blog_category_id' => $validated['blog_category_id'] ?? null,
                'locale' => $validated['locale'],
                'title' => $suggestion['title'],
                'slug' => $this->uniqueSlug($suggestion['slug'], $validated['locale']),
                'excerpt' => $suggestion['excerpt'],
                'content' => $suggestion['content'],
                'status' => BlogPostStatus::Draft->value,
                'featured_image_path' => null,
                'seo_title' => $suggestion['seo_title'],
                'seo_description' => $suggestion['seo_description'],
                'seo_og_image_path' => null,
                'scheduled_for' => null,
                'published_at' => null,
            ]);

            $draft = [
                'id' => $draftPost->id,
                'title' => $draftPost->title,
                'status' => $draftPost->status,
            ];
        }

        return response()->json([
            ...$result,
            'draft' => $draft,
        ], $draft !== null ? 201 : 200);
    }

    public function improve(ImproveBlogPostRequest $request, BlogPost $blogPost): JsonResponse
    {
        return response()->json(
            $this->blogPostAgentService->improvePost($blogPost, $request->validated())
        );
    }

    private function uniqueSlug(string $baseSlug, string $locale): string
    {
        $slug = Str::slug($baseSlug !== '' ? $baseSlug : Str::random(8));
        $candidate = $slug;
        $counter = 1;

        while (BlogPost::query()->where('locale', $locale)->where('slug', $candidate)->exists()) {
            $candidate = $slug.'-'.$counter;
            $counter++;
        }

        return $candidate;
    }
}
