<?php

use App\Enums\AgentShareLinkExpiryType;
use App\Enums\AgentShareLinkUsageType;
use App\Models\AgentShareLink;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('fixed datetime share links expire when datetime is reached', function () {
    $link = AgentShareLink::factory()->create([
        'expiry_type' => AgentShareLinkExpiryType::FixedDatetime,
        'expires_at' => now()->subMinute(),
    ]);

    expect($link->isExpired())->toBeTrue()
        ->and($link->canBeUsed())->toBeFalse();
});

test('relative duration links expire based on issued time', function () {
    $link = AgentShareLink::factory()
        ->durationBased(60)
        ->create([
            'issued_at' => now()->subMinutes(61),
        ]);

    expect($link->isExpired())->toBeTrue()
        ->and($link->canBeUsed())->toBeFalse();
});

test('single use links become unavailable after first successful use', function () {
    $link = AgentShareLink::factory()
        ->singleUse()
        ->create([
            'expiry_type' => AgentShareLinkExpiryType::FixedDatetime,
            'expires_at' => now()->addDay(),
        ]);

    expect($link->usage_type)->toBe(AgentShareLinkUsageType::SingleUse)
        ->and($link->canBeUsed())->toBeTrue();

    $link->markUsed();
    $link->refresh();

    expect($link->used_count)->toBe(1)
        ->and($link->hasRemainingUses())->toBeFalse()
        ->and($link->canBeUsed())->toBeFalse();
});

test('multi use links honor maximum usage limits', function () {
    $link = AgentShareLink::factory()->create([
        'usage_type' => AgentShareLinkUsageType::MultiUse,
        'max_uses' => 3,
        'used_count' => 2,
        'expires_at' => now()->addDay(),
    ]);

    expect($link->canBeUsed())->toBeTrue();

    $link->markUsed();
    $link->refresh();

    expect($link->used_count)->toBe(3)
        ->and($link->hasRemainingUses())->toBeFalse()
        ->and($link->canBeUsed())->toBeFalse();
});
