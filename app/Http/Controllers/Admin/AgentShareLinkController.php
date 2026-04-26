<?php

namespace App\Http\Controllers\Admin;

use App\Enums\AgentShareLinkExpiryType;
use App\Enums\AgentShareLinkUsageType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreAgentShareLinkRequest;
use App\Models\AgentShareLink;
use App\Models\AiAgent;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AgentShareLinkController extends Controller
{
    public function store(StoreAgentShareLinkRequest $request, AiAgent $aiAgent): JsonResponse|RedirectResponse
    {
        $validated = $request->validated();

        $usageType = $validated['usage_type'];

        if ($usageType === AgentShareLinkUsageType::SingleUse->value) {
            $validated['max_uses'] = 1;
        }

        if (($validated['expiry_type'] ?? null) === AgentShareLinkExpiryType::FixedDatetime->value) {
            $validated['expires_in_minutes'] = null;
        }

        if (($validated['expiry_type'] ?? null) === AgentShareLinkExpiryType::RelativeDuration->value) {
            $validated['expires_at'] = null;
            $validated['issued_at'] = $validated['issued_at'] ?? now();
        }

        $link = $aiAgent->shareLinks()->create([
            ...$validated,
            'created_by_user_id' => $request->user()->id,
            'token' => $this->generateUniqueToken(),
            'issued_at' => $validated['issued_at'] ?? now(),
        ]);

        if (! $request->expectsJson()) {
            return to_route('admin.ai-agents.index');
        }

        return response()->json([
            'link' => $link,
            'url' => route('share-links.show', ['token' => $link->token]),
        ], 201);
    }

    public function revoke(Request $request, AgentShareLink $agentShareLink): JsonResponse|RedirectResponse
    {
        $agentShareLink->update(['revoked_at' => now()]);

        if (! $request->expectsJson()) {
            return to_route('admin.ai-agents.index');
        }

        return response()->json($agentShareLink->fresh());
    }

    private function generateUniqueToken(int $size = 48): string
    {
        do {
            $token = Str::random($size);
        } while (AgentShareLink::query()->where('token', $token)->exists());

        return $token;
    }
}
