<?php

namespace App\Http\Controllers\Public;

use App\Ai\Services\AgentChatService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Public\StartAgentChatRequest;
use App\Models\AgentChatSession;
use App\Models\AgentShareLink;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Laravel\Ai\Streaming\Events\TextDelta;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;

class AgentChatController extends Controller
{
    public function __construct(private AgentChatService $chatService) {}

    /**
     * Valida os dados do visitante (código + IA) e inicia uma sessão de chat.
     */
    public function start(StartAgentChatRequest $request, string $token): JsonResponse
    {
        $link = $this->resolveLink($token);

        if ($link === null) {
            return response()->json(['message' => 'Este link não está mais disponível.'], 410);
        }

        // Iniciar uma sessão NOVA exige um uso disponível.
        if ($this->linkUnavailable($link) || ! $link->hasRemainingUses()) {
            return response()->json(['message' => 'Este link não está mais disponível.'], 410);
        }

        $validated = $request->validated();

        $screening = $this->chatService->screenVisitor([
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'reason' => $validated['reason'],
        ]);

        if (! $screening['approved']) {
            return response()->json([
                'message' => 'Não conseguimos validar suas informações. Revise os dados e tente novamente com informações reais.',
            ], 422);
        }

        $session = AgentChatSession::query()->create([
            'ai_agent_id' => $link->ai_agent_id,
            'agent_share_link_id' => $link->id,
            'session_token' => Str::random(48),
            'visitor_name' => $validated['name'],
            'visitor_phone' => $validated['phone'],
            'visitor_reason' => $validated['reason'],
            'screening_status' => $screening['status'],
            'screening_notes' => $screening['notes'],
            'status' => 'active',
        ]);

        // Consome o uso do link apenas agora, ao criar a sessão (não a cada visita).
        $link->markUsed();

        // Mensagem de abertura gerada pela IA (opcional: não bloqueia o início se falhar
        // e não conta como interação do visitante).
        $messages = [];

        try {
            $greeting = $this->chatService->greeting($session, $link->agent);

            if ($greeting['text'] !== '') {
                $session->messages()->create(['role' => 'assistant', 'content' => $greeting['text']]);
                $session->forceFill([
                    'prompt_tokens' => $session->prompt_tokens + $greeting['prompt_tokens'],
                    'completion_tokens' => $session->completion_tokens + $greeting['completion_tokens'],
                    'last_message_at' => now(),
                ])->save();

                $messages[] = ['role' => 'assistant', 'content' => $greeting['text']];
            }
        } catch (Throwable) {
            // Segue sem saudação automática.
        }

        return response()->json([
            'session_token' => $session->session_token,
            'agent_name' => $link->agent->name,
            'max_message_length' => $link->agent->max_message_length,
            'remaining_interactions' => $link->agent->max_interactions,
            'messages' => $messages,
        ], 201);
    }

    /**
     * Restaura uma sessão existente (retomada via localStorage).
     */
    public function show(Request $request, string $token, string $sessionToken): JsonResponse
    {
        $link = $this->resolveLink($token);

        if ($link === null) {
            return response()->json(['message' => 'Este link não está mais disponível.'], 410);
        }

        $session = $this->resolveSession($link, $sessionToken);

        if ($session === null) {
            return response()->json(['message' => 'Sessão não encontrada.'], 404);
        }

        // Retomar uma sessão ativa não gasta uso; só é bloqueada se o link foi
        // revogado/expirou ou o agente foi desativado.
        if (! $session->isCompleted() && $this->linkUnavailable($link)) {
            return response()->json(['message' => 'Este link não está mais disponível.'], 410);
        }

        return response()->json([
            'session_token' => $session->session_token,
            'agent_name' => $link->agent->name,
            'status' => $session->status,
            'max_message_length' => $link->agent->max_message_length,
            'remaining_interactions' => $session->remainingInteractions(),
            'messages' => $session->messages()
                ->orderBy('id')
                ->get(['role', 'content'])
                ->map(fn ($message): array => [
                    'role' => $message->role,
                    'content' => $message->content,
                ]),
        ]);
    }

    /**
     * Recebe uma mensagem do visitante e devolve a resposta do agente em
     * streaming (SSE). Os deltas são enviados conforme chegam; ao final a
     * mensagem completa, os tokens e a interação são persistidos.
     */
    public function message(Request $request, string $token, string $sessionToken): StreamedResponse|JsonResponse
    {
        $link = $this->resolveLink($token);

        if ($link === null) {
            return response()->json(['message' => 'Este link não está mais disponível.'], 410);
        }

        $session = $this->resolveSession($link, $sessionToken);

        if ($session === null) {
            return response()->json(['message' => 'Sessão não encontrada.'], 404);
        }

        if ($this->linkUnavailable($link)) {
            return response()->json(['message' => 'Este link não está mais disponível.'], 410);
        }

        $agent = $link->agent;

        if ($session->isCompleted() || $session->interactions_count >= $agent->max_interactions) {
            $this->completeSession($session);

            return response()->json([
                'limit_reached' => true,
                'message' => 'Limite de mensagens da demonstração atingido.',
            ], 409);
        }

        $validated = $request->validate(
            ['message' => ['required', 'string', 'max:'.$agent->max_message_length]],
            [
                'message.required' => 'Mensagem vazia.',
                'message.max' => 'Mensagem muito longa.',
            ],
        );

        $userMessage = $validated['message'];

        return response()->stream(function () use ($session, $agent, $userMessage): void {
            while (ob_get_level() > 0) {
                ob_end_flush();
            }

            $emit = static function (array $data): void {
                echo 'data: '.json_encode($data, JSON_UNESCAPED_UNICODE)."\n\n";
                flush();
            };

            $full = '';
            $usage = null;

            try {
                $stream = $this->chatService->streamReply($session, $agent, $userMessage);

                foreach ($stream as $event) {
                    if ($event instanceof TextDelta && $event->delta !== '') {
                        $full .= $event->delta;
                        $emit(['delta' => $event->delta]);
                    }
                }

                $usage = $stream->usage;
            } catch (Throwable) {
                $emit(['error' => 'O agente está indisponível no momento. Tente novamente em instantes.']);
                echo "data: [DONE]\n\n";
                flush();

                return;
            }

            $session->messages()->create(['role' => 'user', 'content' => $userMessage]);
            $session->messages()->create(['role' => 'assistant', 'content' => $full]);

            $session->forceFill([
                'interactions_count' => $session->interactions_count + 1,
                'prompt_tokens' => $session->prompt_tokens + ($usage->promptTokens ?? 0),
                'completion_tokens' => $session->completion_tokens + ($usage->completionTokens ?? 0),
                'last_message_at' => now(),
            ])->save();

            $limitReached = $session->interactions_count >= $agent->max_interactions;

            if ($limitReached) {
                $this->completeSession($session);
            }

            $emit([
                'done' => true,
                'remaining_interactions' => max(0, $agent->max_interactions - $session->interactions_count),
                'limit_reached' => $limitReached,
            ]);
            echo "data: [DONE]\n\n";
            flush();
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'Connection' => 'keep-alive',
            'X-Accel-Buffering' => 'no',
        ]);
    }

    private function resolveLink(string $token): ?AgentShareLink
    {
        $link = AgentShareLink::query()
            ->with('agent')
            ->where('token', $token)
            ->first();

        if ($link === null || $link->agent === null) {
            return null;
        }

        return $link;
    }

    /**
     * Indisponível para todos (inclusive retomada): revogado, expirado ou
     * agente inativo. Esgotamento de usos NÃO entra aqui — só impede iniciar
     * uma sessão nova, não continuar uma já existente.
     */
    private function linkUnavailable(AgentShareLink $link): bool
    {
        return $link->isRevoked() || $link->isExpired() || ! $link->agent->is_active;
    }

    private function resolveSession(AgentShareLink $link, string $sessionToken): ?AgentChatSession
    {
        return AgentChatSession::query()
            ->where('agent_share_link_id', $link->id)
            ->where('session_token', $sessionToken)
            ->first();
    }

    private function completeSession(AgentChatSession $session): void
    {
        if (! $session->isCompleted()) {
            $session->forceFill(['status' => 'completed'])->save();
        }
    }
}
