<?php

namespace App\Http\Controllers\Admin;

use App\Ai\Agents\PromptImproverAgent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreAiAgentRequest;
use App\Http\Requests\Admin\UpdateAiAgentRequest;
use App\Models\AgentShareLink;
use App\Models\AiAgent;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
use Throwable;

class AiAgentController extends Controller
{
    public function __construct(private PromptImproverAgent $promptImproverAgent) {}

    public function index(Request $request): JsonResponse|InertiaResponse
    {
        $agents = AiAgent::query()
            ->withCount('shareLinks')
            ->latest()
            ->paginate(15)
            ->withQueryString();

        if ($request->expectsJson()) {
            return response()->json($agents);
        }

        return Inertia::render('admin/AiAgents/Index', [
            'agents' => $agents,
            'recentShareLinks' => AgentShareLink::query()
                ->with('agent:id,name')
                ->latest()
                ->limit(25)
                ->get([
                    'id',
                    'ai_agent_id',
                    'token',
                    'usage_type',
                    'used_count',
                    'max_uses',
                    'expiry_type',
                    'expires_at',
                    'expires_in_minutes',
                    'issued_at',
                    'revoked_at',
                    'created_at',
                ]),
            'shareBaseUrl' => url('/share'),
            // Provedores de texto/chat do config/ai.php, excluindo os que não são
            // LLMs de conversa (áudio/TTS e embeddings/rerank).
            'providers' => collect(config('ai.providers', []))
                ->keys()
                ->reject(fn (string $provider): bool => in_array($provider, ['eleven', 'jina', 'voyageai'], true))
                ->values()
                ->all(),
        ]);
    }

    public function store(StoreAiAgentRequest $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validated();

        $baseSlug = $validated['slug'] ?? Str::slug($validated['name']);
        $validated['slug'] = $this->uniqueSlug($baseSlug);

        $agent = AiAgent::query()->create($validated);

        if (! $request->expectsJson()) {
            return to_route('admin.ai-agents.index');
        }

        return response()->json($agent, 201);
    }

    public function update(UpdateAiAgentRequest $request, AiAgent $aiAgent): JsonResponse|RedirectResponse
    {
        $validated = $request->validated();

        if (array_key_exists('slug', $validated) && $validated['slug'] !== null) {
            $validated['slug'] = $this->uniqueSlug($validated['slug'], $aiAgent->id);
        }

        if (array_key_exists('name', $validated) && ! array_key_exists('slug', $validated)) {
            $validated['slug'] = $this->uniqueSlug(Str::slug($validated['name']), $aiAgent->id);
        }

        $aiAgent->update($validated);

        if (! $request->expectsJson()) {
            return to_route('admin.ai-agents.index');
        }

        return response()->json($aiAgent->fresh());
    }

    public function destroy(Request $request, AiAgent $aiAgent): Response|RedirectResponse
    {
        $aiAgent->delete();

        if (! $request->expectsJson()) {
            return to_route('admin.ai-agents.index');
        }

        return response()->noContent();
    }

    /**
     * Usa um agente de IA (laravel/ai) para reescrever/melhorar o system prompt.
     */
    public function improvePrompt(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'current_prompt' => ['required', 'string', 'max:20000'],
            'name' => ['nullable', 'string', 'max:120'],
        ]);

        $name = trim((string) ($validated['name'] ?? '')) ?: 'Agente';
        $current = $validated['current_prompt'];

        $userPrompt = <<<PROMPT
        Nome do agente: {$name}

        Prompt atual do agente:
        {$current}

        Reescreva e melhore o prompt acima seguindo as regras. Responda em português do Brasil.
        PROMPT;

        try {
            $response = $this->promptImproverAgent->prompt($userPrompt);
            $structured = json_decode((string) $response, true);

            if (is_array($structured) && ! empty($structured['improved_prompt'])) {
                return response()->json([
                    'improved_prompt' => (string) $structured['improved_prompt'],
                    'summary' => (string) ($structured['summary'] ?? ''),
                ]);
            }
        } catch (Throwable) {
            // cai na resposta de erro abaixo
        }

        return response()->json([
            'message' => 'Não foi possível melhorar o prompt agora. Verifique a configuração do provedor de IA (config/ai.php) e tente novamente.',
        ], 422);
    }

    private function uniqueSlug(string $baseSlug, ?int $ignoreId = null): string
    {
        $slug = $baseSlug !== '' ? $baseSlug : Str::random(8);
        $candidate = $slug;
        $counter = 1;

        while (AiAgent::query()
            ->where('slug', $candidate)
            ->when($ignoreId !== null, fn ($query) => $query->whereKeyNot($ignoreId))
            ->exists()) {
            $candidate = $slug.'-'.$counter;
            $counter++;
        }

        return $candidate;
    }
}
