<?php

namespace App\Mcp\Tools;

use App\Mcp\Tools\Concerns\InteractsWithBlog;
use App\Models\BlogTag;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('Atualiza uma tag existente do blog. Informe o id e apenas os campos que deseja alterar.')]
class UpdateBlogTag extends Tool
{
    use InteractsWithBlog;

    public function handle(Request $request): Response
    {
        $tag = BlogTag::query()->find($request->get('id'));

        if ($tag === null) {
            return Response::error('Tag não encontrada.');
        }

        $data = array_filter([
            'locale' => $request->get('locale'),
            'name' => $request->get('name'),
            'slug' => $request->get('slug'),
        ], fn ($value) => $value !== null);

        $validator = Validator::make($data, [
            'locale' => ['sometimes', Rule::in(['pt_BR', 'es', 'en'])],
            'name' => ['sometimes', 'string', 'max:120'],
            'slug' => ['sometimes', 'string', 'max:140'],
        ]);

        if ($validator->fails()) {
            return Response::error('Validação falhou: '.$validator->errors()->first());
        }

        $validated = $validator->validated();
        $locale = $validated['locale'] ?? $tag->locale;

        if (array_key_exists('slug', $validated)) {
            $validated['slug'] = $this->uniqueTaxonomySlug(BlogTag::class, $validated['slug'], $locale, $tag->id);
        } elseif (array_key_exists('name', $validated)) {
            $validated['slug'] = $this->uniqueTaxonomySlug(BlogTag::class, Str::slug($validated['name']), $locale, $tag->id);
        }

        $tag->update($validated);

        return Response::json([
            'message' => 'Tag atualizada com sucesso.',
            'tag' => $tag->fresh()->only(['id', 'locale', 'name', 'slug']),
        ]);
    }

    /**
     * @return array<string, JsonSchema>
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'id' => $schema->integer()->description('ID da tag a atualizar.')->required(),
            'name' => $schema->string()->description('Novo nome (máx. 120).'),
            'locale' => $schema->string()->description('Idioma: pt_BR, es ou en.')->enum(['pt_BR', 'es', 'en']),
            'slug' => $schema->string()->description('Novo slug (revalidado por locale).'),
        ];
    }
}
