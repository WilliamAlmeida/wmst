<?php

use App\Enums\AgentShareLinkExpiryType;
use App\Models\AgentConversationEvent;
use App\Models\AgentShareLink;
use App\Models\AgentShareLinkVisit;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;

uses(RefreshDatabase::class);

test('valid share links can be accessed and create visit tracking', function () {
    $shareLink = AgentShareLink::factory()->create([
        'expiry_type' => AgentShareLinkExpiryType::FixedDatetime,
        'expires_at' => now()->addDay(),
    ]);

    $response = getJson(route('share-links.show', ['token' => $shareLink->token]));

    $response->assertOk()->assertJsonPath('status', 'ok');

    expect($shareLink->fresh()->used_count)->toBe(1)
        ->and(AgentShareLinkVisit::query()->where('agent_share_link_id', $shareLink->id)->count())->toBe(1);
});

test('expired share links are blocked', function () {
    $shareLink = AgentShareLink::factory()->create([
        'expiry_type' => AgentShareLinkExpiryType::FixedDatetime,
        'expires_at' => now()->subMinute(),
    ]);

    $response = getJson(route('share-links.show', ['token' => $shareLink->token]));

    $response->assertForbidden()->assertJsonPath('reason', 'expired');
});

test('single use links are blocked on second access', function () {
    $shareLink = AgentShareLink::factory()->singleUse()->create([
        'expiry_type' => AgentShareLinkExpiryType::FixedDatetime,
        'expires_at' => now()->addDay(),
    ]);

    getJson(route('share-links.show', ['token' => $shareLink->token]))->assertOk();

    $secondAccessResponse = getJson(route('share-links.show', ['token' => $shareLink->token]));

    $secondAccessResponse->assertForbidden()->assertJsonPath('reason', 'usage_exhausted');
});

test('conversation events are tracked for active links', function () {
    $shareLink = AgentShareLink::factory()->create([
        'expiry_type' => AgentShareLinkExpiryType::FixedDatetime,
        'expires_at' => now()->addDay(),
    ]);

    $eventResponse = postJson(route('share-links.events.store', ['token' => $shareLink->token]), [
        'event_name' => 'conversation.started',
        'payload' => ['channel' => 'chat'],
    ]);

    $eventResponse->assertCreated()->assertJsonPath('status', 'event_recorded');

    expect(AgentConversationEvent::query()->where('agent_share_link_id', $shareLink->id)->count())->toBe(1);
});

test('conversation events are blocked for revoked links', function () {
    $shareLink = AgentShareLink::factory()->create([
        'revoked_at' => now(),
        'expires_at' => now()->addDay(),
    ]);

    $eventResponse = postJson(route('share-links.events.store', ['token' => $shareLink->token]), [
        'event_name' => 'conversation.started',
    ]);

    $eventResponse->assertForbidden()->assertJsonPath('reason', 'revoked');
});
