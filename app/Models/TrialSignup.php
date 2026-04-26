<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'name',
    'email',
    'company',
    'phone',
    'message',
    'preferred_locale',
    'product',
    'ai_agent_slug',
    'consent_accepted',
    'metadata',
    'ai_agent_id',
    'agent_share_link_id',
])]
class TrialSignup extends Model
{
    /** @use HasFactory<TrialSignupFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'consent_accepted' => 'boolean',
            'metadata' => 'array',
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
}
