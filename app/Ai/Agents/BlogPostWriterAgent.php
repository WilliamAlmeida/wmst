<?php

namespace App\Ai\Agents;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\HasStructuredOutput;
use Laravel\Ai\Promptable;

class BlogPostWriterAgent implements Agent, HasStructuredOutput
{
    use Promptable;

    public function instructions(): string
    {
        return <<<'PROMPT'
You are a senior content strategist for a B2B software company.
Your job is to analyze existing blog content, propose better editorial plans,
and produce high quality draft posts for human review.

Rules:
- Be practical and objective.
- Keep SEO and readability in mind.
- Respect the requested locale.
- Never return markdown; only plain text values in each field.
PROMPT;
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'analysis' => $schema->string()->required(),
            'action_plan' => $schema->string()->required(),
            'suggested_title' => $schema->string()->required(),
            'suggested_slug' => $schema->string()->required(),
            'suggested_excerpt' => $schema->string()->required(),
            'suggested_content' => $schema->string()->required(),
            'suggested_seo_title' => $schema->string()->required(),
            'suggested_seo_description' => $schema->string()->required(),
        ];
    }
}
