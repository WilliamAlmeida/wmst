<?php

namespace App\Models;

use Database\Factories\AgentShareLinkVisitFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['agent_share_link_id', 'user_id', 'ip_address', 'user_agent', 'referrer', 'visited_at', 'metadata'])]
class AgentShareLinkVisit extends Model
{
    /** @use HasFactory<AgentShareLinkVisitFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'visited_at' => 'datetime',
            'metadata' => 'array',
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
