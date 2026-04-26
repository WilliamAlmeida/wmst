<?php

namespace App\Models;

use App\Enums\AgentShareLinkExpiryType;
use App\Enums\AgentShareLinkUsageType;
use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use Database\Factories\AgentShareLinkFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'ai_agent_id',
    'created_by_user_id',
    'assigned_user_id',
    'token',
    'label',
    'expiry_type',
    'expires_at',
    'expires_in_minutes',
    'issued_at',
    'usage_type',
    'max_uses',
    'used_count',
    'first_accessed_at',
    'last_accessed_at',
    'revoked_at',
    'meta',
])]
class AgentShareLink extends Model
{
    /** @use HasFactory<AgentShareLinkFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'expiry_type' => AgentShareLinkExpiryType::class,
            'expires_at' => 'datetime',
            'issued_at' => 'datetime',
            'usage_type' => AgentShareLinkUsageType::class,
            'first_accessed_at' => 'datetime',
            'last_accessed_at' => 'datetime',
            'revoked_at' => 'datetime',
            'meta' => 'array',
        ];
    }

    /**
     * @return BelongsTo<AiAgent, $this>
     */
    public function agent(): BelongsTo
    {
        return $this->belongsTo(AiAgent::class, 'ai_agent_id');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_user_id');
    }

    /**
     * @return HasMany<AgentShareLinkVisit, $this>
     */
    public function visits(): HasMany
    {
        return $this->hasMany(AgentShareLinkVisit::class);
    }

    /**
     * @return HasMany<AgentConversationEvent, $this>
     */
    public function conversationEvents(): HasMany
    {
        return $this->hasMany(AgentConversationEvent::class);
    }

    public function isRevoked(): bool
    {
        return $this->revoked_at !== null;
    }

    public function expiresAtFromDuration(): ?CarbonImmutable
    {
        if ($this->expires_in_minutes === null) {
            return null;
        }

        $anchor = $this->issued_at ?? $this->created_at;

        if ($anchor === null) {
            return null;
        }

        return $anchor->toImmutable()->addMinutes($this->expires_in_minutes);
    }

    public function isExpired(?CarbonInterface $at = null): bool
    {
        $moment = $at?->toImmutable() ?? now()->toImmutable();

        if ($this->expiry_type === AgentShareLinkExpiryType::FixedDatetime) {
            return $this->expires_at !== null
                && $moment->greaterThanOrEqualTo($this->expires_at->toImmutable());
        }

        $durationExpiration = $this->expiresAtFromDuration();

        return $durationExpiration !== null
            && $moment->greaterThanOrEqualTo($durationExpiration);
    }

    public function hasRemainingUses(): bool
    {
        if ($this->usage_type === AgentShareLinkUsageType::SingleUse) {
            return $this->used_count < 1;
        }

        if ($this->max_uses === null) {
            return true;
        }

        return $this->used_count < $this->max_uses;
    }

    public function remainingUses(): ?int
    {
        if ($this->usage_type === AgentShareLinkUsageType::SingleUse) {
            return max(1 - $this->used_count, 0);
        }

        if ($this->max_uses === null) {
            return null;
        }

        return max($this->max_uses - $this->used_count, 0);
    }

    public function canBeUsed(?CarbonInterface $at = null): bool
    {
        return ! $this->isRevoked()
            && ! $this->isExpired($at)
            && $this->hasRemainingUses();
    }

    public function unavailabilityReason(?CarbonInterface $at = null): ?string
    {
        if ($this->isRevoked()) {
            return 'revoked';
        }

        if ($this->isExpired($at)) {
            return 'expired';
        }

        if (! $this->hasRemainingUses()) {
            return 'usage_exhausted';
        }

        return null;
    }

    public function markUsed(?CarbonInterface $at = null): void
    {
        $moment = $at?->toImmutable() ?? now()->toImmutable();

        $this->used_count++;
        $this->first_accessed_at ??= $moment;
        $this->last_accessed_at = $moment;

        $this->save();
    }
}
