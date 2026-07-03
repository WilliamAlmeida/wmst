<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\Public\StoreAgentConversationEventRequest;
use App\Models\AgentShareLink;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class AgentShareLinkAccessController extends Controller
{
    public function show(Request $request, string $token): JsonResponse|InertiaResponse
    {
        $locale = $this->resolveLocale($request->query('locale'));
        app()->setLocale($locale);

        $shareLink = AgentShareLink::query()
            ->with('agent:id,name,slug,description')
            ->where('token', $token)
            ->first();

        if ($shareLink === null) {
            $payload = [
                'status' => 'invalid',
                'message' => 'Link de acesso inválido.',
            ];

            if ($request->expectsJson()) {
                return response()->json($payload, 404);
            }

            return Inertia::render('chat/AgentAccess', [
                ...$payload,
                'locale' => $locale,
                'agent' => null,
                'link' => null,
                'homeUrl' => $locale === 'pt_BR' ? url('/') : url('/'.$locale),
            ]);
        }

        if (! $shareLink->canBeUsed()) {
            $payload = [
                'status' => 'blocked',
                'reason' => $shareLink->unavailabilityReason(),
                'message' => 'Este link não está mais disponível.',
            ];

            if ($request->expectsJson()) {
                return response()->json($payload, 403);
            }

            return Inertia::render('chat/AgentAccess', [
                ...$payload,
                'locale' => $locale,
                'agent' => $shareLink->agent,
                'link' => [
                    'token' => $shareLink->token,
                    'reason' => $shareLink->unavailabilityReason(),
                ],
                'homeUrl' => $locale === 'pt_BR' ? url('/') : url('/'.$locale),
            ]);
        }

        $shareLink->markUsed();

        $shareLink->visits()->create([
            'user_id' => $request->user()?->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'referrer' => $request->headers->get('referer'),
            'visited_at' => now(),
            'metadata' => [
                'path' => $request->path(),
            ],
        ]);

        $shareLink->refresh();

        $payload = [
            'status' => 'ok',
            'agent' => $shareLink->agent,
            'link' => [
                'token' => $shareLink->token,
                'usage_type' => $shareLink->usage_type,
                'remaining_uses' => $shareLink->remainingUses(),
                'expires_at' => ($shareLink->expiry_type->value === 'fixed_datetime'
                    ? $shareLink->expires_at
                    : $shareLink->expiresAtFromDuration())?->toIso8601String(),
            ],
        ];

        if ($request->expectsJson()) {
            return response()->json($payload);
        }

        return Inertia::render('chat/AgentAccess', [
            ...$payload,
            'locale' => $locale,
            'homeUrl' => $locale === 'pt_BR' ? url('/') : url('/'.$locale),
        ]);
    }

    public function storeEvent(StoreAgentConversationEventRequest $request, string $token): JsonResponse
    {
        $shareLink = AgentShareLink::query()->where('token', $token)->first();

        if ($shareLink === null) {
            return response()->json([
                'status' => 'invalid',
                'message' => 'Link de acesso inválido.',
            ], 404);
        }

        if ($shareLink->isRevoked() || $shareLink->isExpired()) {
            return response()->json([
                'status' => 'blocked',
                'reason' => $shareLink->unavailabilityReason(),
                'message' => 'Este link não está mais disponível para registrar eventos.',
            ], 403);
        }

        $event = $shareLink->conversationEvents()->create([
            ...$request->validated(),
            'user_id' => $request->user()?->id,
            'event_at' => $request->date('event_at') ?? now(),
        ]);

        return response()->json([
            'status' => 'event_recorded',
            'event_id' => $event->id,
        ], 201);
    }

    private function resolveLocale(?string $locale): string
    {
        if (in_array($locale, ['es', 'en'], true)) {
            return $locale;
        }

        return 'pt_BR';
    }
}
