<?php

namespace App\Models;

use Database\Factories\AgentConversationEventFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['agent_share_link_id', 'user_id', 'event_name', 'event_at', 'external_event_id', 'payload'])]
class AgentConversationEvent extends Model
{
    /** @use HasFactory<AgentConversationEventFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'event_at' => 'datetime',
            'payload' => 'array',
        ];
    }

    /**
     * @return BelongsTo<AgentShareLink, $this>
     */
    public function shareLink(): BelongsTo
    {
        return $this->belongsTo(AgentShareLink::class, 'agent_share_link_id');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
