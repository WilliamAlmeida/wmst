<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['agent_chat_session_id', 'role', 'content'])]
class AgentChatMessage extends Model
{
    /**
     * @return BelongsTo<AgentChatSession, $this>
     */
    public function session(): BelongsTo
    {
        return $this->belongsTo(AgentChatSession::class, 'agent_chat_session_id');
    }
}
