<?php

namespace App\Models;

use Database\Factories\AiAgentFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'slug', 'description', 'system_prompt', 'provider', 'model', 'is_active', 'metadata'])]
class AiAgent extends Model
{
    /** @use HasFactory<AiAgentFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'metadata' => 'array',
        ];
    }

    /**
     * @return HasMany<AgentShareLink, $this>
     */
    public function shareLinks(): HasMany
    {
        return $this->hasMany(AgentShareLink::class);
    }
}
