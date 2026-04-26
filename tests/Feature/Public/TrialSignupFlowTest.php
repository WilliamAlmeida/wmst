<?php

use App\Enums\AgentShareLinkExpiryType;
use App\Models\AgentShareLink;
use App\Models\AiAgent;
use App\Models\TrialSignup;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\from;

uses(RefreshDatabase::class);

test('trial signup creates share link in default locale route', function () {
    $agent = AiAgent::factory()->create([
        'name' => 'Sales Agent',
        'slug' => 'sales-agent',
        'is_active' => true,
    ]);

    $response = from('/')->post('/trial-signups', [
        'name' => 'William Test',
        'email' => 'william@example.com',
        'company' => 'WMST',
        'product' => 'custom',
        'ai_agent_slug' => 'sales-agent',
        'consent_accepted' => '1',
    ]);

    $response
        ->assertRedirect('/')
        ->assertSessionHas('trialSignup.status', 'created');

    expect(TrialSignup::query()->count())->toBe(1)
        ->and(AgentShareLink::query()->count())->toBe(1);

    $trialSignup = TrialSignup::query()->first();
    expect($trialSignup)->not->toBeNull();
    expect($trialSignup?->email)->toBe('william@example.com');

    $shareLink = AgentShareLink::query()->first();
    expect($shareLink)->not->toBeNull();
    expect($shareLink?->ai_agent_id)->toBe($agent->id);
    expect($shareLink?->expiry_type)->toBe(AgentShareLinkExpiryType::RelativeDuration);
});

test('trial signup supports localized route and stores preferred locale', function () {
    AiAgent::factory()->create([
        'name' => 'Spanish Agent',
        'slug' => 'spanish-agent',
        'is_active' => true,
    ]);

    $response = from('/es')->post('/es/trial-signups', [
        'name' => 'Maria Test',
        'email' => 'maria@example.com',
        'product' => 'conecta',
        'consent_accepted' => '1',
    ]);

    $response
        ->assertRedirect('/es')
        ->assertSessionHas('trialSignup.status', 'created');

    $trialSignup = TrialSignup::query()->first();
    expect($trialSignup)->not->toBeNull();
    expect($trialSignup?->preferred_locale)->toBe('es');
});
