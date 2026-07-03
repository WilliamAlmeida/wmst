<?php

namespace App\Models;

use Database\Factories\AiAgentFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'slug', 'description', 'system_prompt', 'provider', 'model', 'is_active', 'metadata', 'max_interactions', 'max_message_length'])]
class AiAgent extends Model
{
    /** @use HasFactory<AiAgentFactory> */
    use HasFactory;

    /**
     * Espelha os defaults do banco para instâncias ainda não persistidas.
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'max_interactions' => 20,
        'max_message_length' => 500,
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'metadata' => 'array',
            'max_interactions' => 'integer',
            'max_message_length' => 'integer',
        ];
    }

    /**
     * @return HasMany<AgentShareLink, $this>
     */
    public function shareLinks(): HasMany
    {
        return $this->hasMany(AgentShareLink::class);
    }

    /**
     * @return HasMany<AgentChatSession, $this>
     */
    public function chatSessions(): HasMany
    {
        return $this->hasMany(AgentChatSession::class);
    }
}
