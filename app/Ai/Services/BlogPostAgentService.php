<?php

namespace App\Ai\Services;

use App\Ai\Agents\BlogPostWriterAgent;
use App\Models\BlogPost;
use BackedEnum;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Throwable;

class BlogPostAgentService
{
    public function __construct(private BlogPostWriterAgent $agent) {}

    /**
     * @param  Collection<int, BlogPost>  $posts
     * @param  Collection<int, object>  $categories
     * @return array<string, mixed>
     */
    public function analyze(Collection $posts, Collection $categories, ?string $focus = null): array
    {
        $context = $this->buildContext($posts, $categories);

        $focusLine = $focus !== null && $focus !== ''
            ? "Main focus for analysis: {$focus}"
            : 'Main focus for analysis: editorial quality and conversion potential.';

        $prompt = <<<PROMPT
Task: Analyze existing blog content and available categories.
{$focusLine}

Context JSON:
{$context}

Return a concise diagnostic and an execution plan.
If no post drafting is requested, leave draft fields with short placeholders.
PROMPT;

        $structured = $this->askAgent($prompt);

        if ($structured === null) {
            return $this->fallbackAnalyze($posts, $categories, $focus);
        }

        return $this->formatStructuredResponse($structured);
    }

    /**
     * @param  Collection<int, BlogPost>  $posts
     * @param  Collection<int, object>  $categories
     * @param  array<string, mixed>  $input
     * @return array<string, mixed>
     */
    public function generateDraft(Collection $posts, Collection $categories, array $input): array
    {
        $context = $this->buildContext($posts, $categories);

        $prompt = <<<PROMPT
Task: Generate a draft blog post for human review.

Input:
- Locale: {$input['locale']}
- Title hint: {$input['title_hint']}
- Objective: {$input['objective']}
- Target audience: {$input['target_audience']}
- Preferred category ID: {$input['blog_category_id']}

Context JSON:
{$context}

Return a realistic publish-ready draft proposal in plain text fields.
PROMPT;

        $structured = $this->askAgent($prompt);

        if ($structured === null) {
            return $this->fallbackGenerate($input);
        }

        return $this->formatStructuredResponse($structured);
    }

    /**
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    public function improvePost(BlogPost $blogPost, array $payload): array
    {
        $currentTitle = (string) ($payload['current_title'] ?? $blogPost->title);
        $currentExcerpt = (string) ($payload['current_excerpt'] ?? $blogPost->excerpt ?? '');
        $currentContent = (string) ($payload['current_content'] ?? $blogPost->content);
        $currentSeoTitle = (string) ($payload['current_seo_title'] ?? $blogPost->seo_title ?? '');
        $currentSeoDescription = (string) ($payload['current_seo_description'] ?? $blogPost->seo_description ?? '');

        $currentPost = json_encode([
            'title' => $currentTitle,
            'excerpt' => $currentExcerpt,
            'content' => $currentContent,
            'seo_title' => $currentSeoTitle,
            'seo_description' => $currentSeoDescription,
            'locale' => $blogPost->locale,
        ], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);

        $prompt = <<<PROMPT
Task: Improve an existing blog post using editor feedback.

Editor feedback:
{$payload['feedback']}

Current post JSON:
{$currentPost}

Return an improved full version of the post and explain your reasoning.
PROMPT;

        $structured = $this->askAgent($prompt);

        if ($structured === null) {
            return $this->fallbackImprove($currentTitle, $currentExcerpt, $currentContent, (string) $payload['feedback']);
        }

        return $this->formatStructuredResponse($structured);
    }

    /**
     * @return array<string, mixed>|null
     */
    private function askAgent(string $prompt): ?array
    {
        try {
            $response = $this->agent->prompt($prompt);
            $structured = json_decode((string) $response, true);

            return is_array($structured) ? $structured : null;
        } catch (Throwable) {
            return null;
        }
    }

    /**
     * @param  array<string, mixed>  $structured
     * @return array<string, mixed>
     */
    private function formatStructuredResponse(array $structured): array
    {
        $title = trim((string) ($structured['suggested_title'] ?? ''));
        $excerpt = trim((string) ($structured['suggested_excerpt'] ?? ''));
        $content = trim((string) ($structured['suggested_content'] ?? ''));

        $slugCandidate = trim((string) ($structured['suggested_slug'] ?? ''));
        $slug = Str::slug($slugCandidate !== '' ? $slugCandidate : $title);

        if ($slug === '') {
            $slug = Str::slug($title !== '' ? $title : 'post-rascunho');
        }

        return [
            'analysis' => trim((string) ($structured['analysis'] ?? '')),
            'action_plan' => trim((string) ($structured['action_plan'] ?? '')),
            'suggestion' => [
                'title' => $title,
                'slug' => $slug,
                'excerpt' => $excerpt,
                'content' => $content,
                'seo_title' => trim((string) ($structured['suggested_seo_title'] ?? $title)),
                'seo_description' => trim((string) ($structured['suggested_seo_description'] ?? Str::limit($excerpt !== '' ? $excerpt : $content, 150))),
            ],
        ];
    }

    /**
     * @param  Collection<int, BlogPost>  $posts
     * @param  Collection<int, object>  $categories
     */
    private function buildContext(Collection $posts, Collection $categories): string
    {
        $payload = [
            'posts' => $posts->map(fn (BlogPost $post) => [
                'id' => $post->id,
                'locale' => $post->locale,
                'status' => $this->statusToValue($post->status),
                'title' => $post->title,
                'excerpt' => Str::limit((string) $post->excerpt, 240),
                'category' => $post->category?->name,
                'published_at' => $post->published_at?->toIso8601String(),
            ])->values()->all(),
            'categories' => $categories->map(fn ($category) => [
                'id' => $category->id,
                'locale' => $category->locale,
                'name' => $category->name,
                'slug' => $category->slug,
            ])->values()->all(),
        ];

        return json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) ?: '{"posts":[],"categories":[]}';
    }

    private function statusToValue(mixed $status): string
    {
        if ($status instanceof BackedEnum) {
            return (string) $status->value;
        }

        return (string) $status;
    }

    /**
     * @param  Collection<int, BlogPost>  $posts
     * @param  Collection<int, object>  $categories
     * @return array<string, mixed>
     */
    private function fallbackAnalyze(Collection $posts, Collection $categories, ?string $focus): array
    {
        $published = $posts->filter(fn (BlogPost $post) => $this->statusToValue($post->status) === 'published')->count();

        return [
            'analysis' => 'Automated fallback analysis was used. '.
                "Posts analyzed: {$posts->count()}. Published: {$published}. Categories mapped: {$categories->count()}. ".
                ($focus !== null && $focus !== '' ? "Focus considered: {$focus}." : ''),
            'action_plan' => '1) Increase topical clustering by category. 2) Maintain consistent SEO metadata. 3) Produce draft posts in batch and review before publishing.',
            'suggestion' => [
                'title' => 'Post planning review',
                'slug' => 'post-planning-review',
                'excerpt' => 'High level editorial review generated without external model response.',
                'content' => 'Use this analysis as a baseline and refine manually.',
                'seo_title' => 'Post planning review',
                'seo_description' => 'High level editorial review generated without external model response.',
            ],
        ];
    }

    /**
     * @param  array<string, mixed>  $input
     * @return array<string, mixed>
     */
    private function fallbackGenerate(array $input): array
    {
        $title = trim((string) $input['title_hint']);
        $objective = trim((string) ($input['objective'] ?? ''));
        $audience = trim((string) ($input['target_audience'] ?? ''));

        $excerpt = 'Draft generated in fallback mode for editorial evaluation.';
        $content = "Suggested objective: {$objective}\n\nTarget audience: {$audience}\n\nBase draft:\n- Problem context\n- Proposed solution\n- Practical next steps";

        return [
            'analysis' => 'Fallback mode was used because the AI provider was unavailable.',
            'action_plan' => 'Review and expand this draft before publishing.',
            'suggestion' => [
                'title' => $title !== '' ? $title : 'Novo rascunho para avaliação',
                'slug' => Str::slug($title !== '' ? $title : 'novo-rascunho-para-avaliação'),
                'excerpt' => $excerpt,
                'content' => $content,
                'seo_title' => $title !== '' ? $title : 'Novo rascunho para avaliação',
                'seo_description' => $excerpt,
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function fallbackImprove(string $title, string $excerpt, string $content, string $feedback): array
    {
        $updatedContent = trim($content)."\n\nEditorial feedback to apply:\n{$feedback}";
        $updatedExcerpt = $excerpt !== '' ? $excerpt : Str::limit($updatedContent, 160);

        return [
            'analysis' => 'Fallback mode was used. Feedback was appended for manual review.',
            'action_plan' => 'Apply the feedback points manually and run final copy editing.',
            'suggestion' => [
                'title' => $title,
                'slug' => Str::slug($title !== '' ? $title : 'post-melhorado'),
                'excerpt' => $updatedExcerpt,
                'content' => $updatedContent,
                'seo_title' => $title,
                'seo_description' => Str::limit($updatedExcerpt, 160),
            ],
        ];
    }
}
