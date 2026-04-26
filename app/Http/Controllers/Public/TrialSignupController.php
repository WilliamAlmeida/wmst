<?php

namespace App\Http\Controllers\Public;

use App\Enums\AgentShareLinkExpiryType;
use App\Enums\AgentShareLinkUsageType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Public\StoreTrialSignupRequest;
use App\Models\AgentShareLink;
use App\Models\AiAgent;
use App\Models\TrialSignup;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;

class TrialSignupController extends Controller
{
    public function store(StoreTrialSignupRequest $request, ?string $locale = null): RedirectResponse
    {
        $activeLocale = $this->resolveLocale($locale);
        $validated = $request->validated();

        $agent = AiAgent::query()
            ->where('is_active', true)
            ->when(
                ! empty($validated['ai_agent_slug'] ?? null),
                fn ($query) => $query->where('slug', $validated['ai_agent_slug'])
            )
            ->orderByDesc('id')
            ->first();

        if ($agent === null) {
            return back()->withErrors([
                'ai_agent_slug' => 'No momento não temos agentes disponíveis para teste.',
            ]);
        }

        $shareLink = $agent->shareLinks()->create([
            'created_by_user_id' => null,
            'assigned_user_id' => null,
            'token' => $this->generateUniqueToken(),
            'label' => 'Trial: '.$validated['email'],
            'expiry_type' => AgentShareLinkExpiryType::RelativeDuration,
            'expires_at' => null,
            'expires_in_minutes' => 10080,
            'issued_at' => now(),
            'usage_type' => AgentShareLinkUsageType::MultiUse,
            'max_uses' => null,
            'used_count' => 0,
            'meta' => [
                'source' => 'public_signup',
                'locale' => $activeLocale,
            ],
        ]);

        TrialSignup::query()->create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'company' => $validated['company'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'message' => $validated['message'] ?? null,
            'preferred_locale' => $activeLocale,
            'product' => $validated['product'] ?? null,
            'ai_agent_slug' => $agent->slug,
            'consent_accepted' => true,
            'metadata' => [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ],
            'ai_agent_id' => $agent->id,
            'agent_share_link_id' => $shareLink->id,
        ]);

        $url = route('share-links.show', ['token' => $shareLink->token]);

        if ($activeLocale !== 'pt_BR') {
            $url .= '?locale='.$activeLocale;
        }

        return back()->with('trialSignup', [
            'status' => 'created',
            'agent' => $agent->name,
            'url' => $url,
        ]);
    }

    private function resolveLocale(?string $locale): string
    {
        if (in_array($locale, ['es', 'en'], true)) {
            return $locale;
        }

        return 'pt_BR';
    }

    private function generateUniqueToken(int $size = 48): string
    {
        do {
            $token = Str::random($size);
        } while (AgentShareLink::query()->where('token', $token)->exists());

        return $token;
    }
}
