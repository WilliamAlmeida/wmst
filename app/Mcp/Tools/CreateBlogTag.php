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

#[Description('Cria uma nova tag do blog da WMST. Informe name e locale. O slug é gerado automaticamente por locale se não for enviado.')]
class CreateBlogTag extends Tool
{
    use InteractsWithBlog;

    public function handle(Request $request): Response
    {
        $data = [
            'locale' => $request->get('locale', 'pt_BR'),
            'name' => $request->get('name'),
            'slug' => $request->get('slug'),
        ];

        $validator = Validator::make($data, [
            'locale' => ['required', Rule::in(['pt_BR', 'es', 'en'])],
            'name' => ['required', 'string', 'max:120'],
            'slug' => ['nullable', 'string', 'max:140'],
        ]);

        if ($validator->fails()) {
            return Response::error('Validação falhou: '.$validator->errors()->first());
        }

        $validated = $validator->validated();
        $validated['slug'] = $this->uniqueTaxonomySlug(
            BlogTag::class,
            $validated['slug'] ?? Str::slug($validated['name']),
            $validated['locale'],
        );

        $tag = BlogTag::query()->create($validated);

        return Response::json([
            'message' => 'Tag criada com sucesso.',
            'tag' => $tag->only(['id', 'locale', 'name', 'slug']),
        ]);
    }

    /**
     * @return array<string, JsonSchema>
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'name' => $schema->string()->description('Nome da tag (máx. 120 caracteres).')->required(),
            'locale' => $schema->string()->description('Idioma: pt_BR, es ou en.')->enum(['pt_BR', 'es', 'en']),
            'slug' => $schema->string()->description('Slug opcional; gerado a partir do nome se omitido.'),
        ];
    }
}
