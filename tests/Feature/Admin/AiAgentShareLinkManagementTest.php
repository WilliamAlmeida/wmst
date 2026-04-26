<?php

use App\Enums\AgentShareLinkExpiryType;
use App\Enums\AgentShareLinkUsageType;
use App\Models\AgentShareLink;
use App\Models\AiAgent;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\patchJson;
use function Pest\Laravel\postJson;

uses(RefreshDatabase::class);

test('authenticated users can create agents and manage share links', function () {
    /** @var User $user */
    $user = User::factory()->create();

    actingAs($user);

    $agentResponse = postJson(route('admin.ai-agents.store'), [
        'name' => 'WMST Sales Assistant',
        'provider' => 'openai',
        'model' => 'gpt-4o-mini',
    ]);

    $agentResponse->assertCreated();

    $agent = AiAgent::query()->first();

    expect($agent)->not->toBeNull();

    $linkResponse = postJson(route('admin.agent-share-links.store', $agent), [
        'label' => 'Teste de onboarding',
        'expiry_type' => AgentShareLinkExpiryType::FixedDatetime->value,
        'expires_at' => now()->addDay()->toIso8601String(),
        'usage_type' => AgentShareLinkUsageType::SingleUse->value,
    ]);

    $linkResponse->assertCreated()->assertJsonStructure([
        'link' => ['id', 'token', 'usage_type', 'max_uses'],
        'url',
    ]);

    $shareLink = AgentShareLink::query()->first();

    expect($shareLink)->not->toBeNull()
        ->and($shareLink->created_by_user_id)->toBe($user->id)
        ->and($shareLink->usage_type)->toBe(AgentShareLinkUsageType::SingleUse)
        ->and($shareLink->max_uses)->toBe(1);

    $revokeResponse = patchJson(route('admin.agent-share-links.revoke', $shareLink));

    $revokeResponse->assertOk();

    expect($shareLink->fresh()->revoked_at)->not->toBeNull();
});
