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

#[Description('Cria uma nova categoria do blog da WMST. Informe name e locale. O slug é gerado automaticamente por locale se não for enviado.')]
class CreateBlogCategory extends Tool
{
    use InteractsWithBlog;

    public function handle(Request $request): Response
    {
        $data = [
            'locale' => $request->get('locale', 'pt_BR'),
            'name' => $request->get('name'),
            'slug' => $request->get('slug'),
            'description' => $request->get('description'),
            'is_active' => $request->get('is_active', true),
        ];

        $validator = Validator::make($data, [
            'locale' => ['required', Rule::in(['pt_BR', 'es', 'en'])],
            'name' => ['required', 'string', 'max:120'],
            'slug' => ['nullable', 'string', 'max:140'],
            'description' => ['nullable', 'string'],
            'is_active' => ['boolean'],
        ]);

        if ($validator->fails()) {
            return Response::error('Validação falhou: '.$validator->errors()->first());
        }

        $validated = $validator->validated();
        $validated['slug'] = $this->uniqueTaxonomySlug(
            BlogCategory::class,
            $validated['slug'] ?? Str::slug($validated['name']),
            $validated['locale'],
        );

        $category = BlogCategory::query()->create($validated);

        return Response::json([
            'message' => 'Categoria criada com sucesso.',
            'category' => $category->only(['id', 'locale', 'name', 'slug', 'description', 'is_active']),
        ]);
    }

    /**
     * @return array<string, JsonSchema>
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'name' => $schema->string()->description('Nome da categoria (máx. 120 caracteres).')->required(),
            'locale' => $schema->string()->description('Idioma: pt_BR, es ou en.')->enum(['pt_BR', 'es', 'en']),
            'slug' => $schema->string()->description('Slug opcional; gerado a partir do nome se omitido.'),
            'description' => $schema->string()->description('Descrição opcional da categoria.'),
            'is_active' => $schema->boolean()->description('Se a categoria está ativa (padrão true).'),
        ];
    }
}
