<?php

namespace App\Mcp\Tools;

use App\Mcp\Tools\Concerns\InteractsWithBlog;
use App\Models\BlogCategory;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('Atualiza uma categoria existente do blog. Informe o id e apenas os campos que deseja alterar.')]
class UpdateBlogCategory extends Tool
{
    use InteractsWithBlog;

    public function handle(Request $request): Response
    {
        $category = BlogCategory::query()->find($request->get('id'));

        if ($category === null) {
            return Response::error('Categoria não encontrada.');
        }

        $data = array_filter([
            'locale' => $request->get('locale'),
            'name' => $request->get('name'),
            'slug' => $request->get('slug'),
            'description' => $request->get('description'),
            'is_active' => $request->get('is_active'),
        ], fn ($value) => $value !== null);

        $validator = Validator::make($data, [
            'locale' => ['sometimes', Rule::in(['pt_BR', 'es', 'en'])],
            'name' => ['sometimes', 'string', 'max:120'],
            'slug' => ['sometimes', 'string', 'max:140'],
            'description' => ['sometimes', 'nullable', 'string'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        if ($validator->fails()) {
            return Response::error('Validação falhou: '.$validator->errors()->first());
        }

        $validated = $validator->validated();
        $locale = $validated['locale'] ?? $category->locale;

        if (array_key_exists('slug', $validated)) {
            $validated['slug'] = $this->uniqueTaxonomySlug(BlogCategory::class, $validated['slug'], $locale, $category->id);
        } elseif (array_key_exists('name', $validated)) {
            $validated['slug'] = $this->uniqueTaxonomySlug(BlogCategory::class, Str::slug($validated['name']), $locale, $category->id);
        }

        $category->update($validated);

        return Response::json([
            'message' => 'Categoria atualizada com sucesso.',
            'category' => $category->fresh()->only(['id', 'locale', 'name', 'slug', 'description', 'is_active']),
        ]);
    }

    /**
     * @return array<string, JsonSchema>
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'id' => $schema->integer()->description('ID da categoria a atualizar.')->required(),
            'name' => $schema->string()->description('Novo nome (máx. 120).'),
            'locale' => $schema->string()->description('Idioma: pt_BR, es ou en.')->enum(['pt_BR', 'es', 'en']),
            'slug' => $schema->string()->description('Novo slug (revalidado por locale).'),
            'description' => $schema->string()->description('Nova descrição.'),
            'is_active' => $schema->boolean()->description('Ativa ou desativa a categoria.'),
        ];
    }
}
