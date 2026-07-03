<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'ai_agent_id',
    'agent_share_link_id',
    'session_token',
    'visitor_name',
    'visitor_phone',
    'visitor_reason',
    'screening_status',
    'screening_notes',
    'status',
    'interactions_count',
    'prompt_tokens',
    'completion_tokens',
    'last_message_at',
])]
class AgentChatSession extends Model
{
    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'last_message_at' => 'datetime',
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
     * @return BelongsTo<AgentShareLink, $this>
     */
    public function shareLink(): BelongsTo
    {
        return $this->belongsTo(AgentShareLink::class, 'agent_share_link_id');
    }

    /**
     * @return HasMany<AgentChatMessage, $this>
     */
    public function messages(): HasMany
    {
        return $this->hasMany(AgentChatMessage::class);
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function remainingInteractions(): int
    {
        $max = $this->agent?->max_interactions ?? 0;

        return max(0, $max - $this->interactions_count);
    }
}
