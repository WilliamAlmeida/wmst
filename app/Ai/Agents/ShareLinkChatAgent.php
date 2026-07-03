<?php

namespace App\Ai\Agents;

use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\Conversational;
use Laravel\Ai\Messages\Message;
use Laravel\Ai\Promptable;

/**
 * Agente de chat dos links compartilhados. As instruções e o histórico são
 * definidos por instância a partir do AiAgent cadastrado no banco.
 */
class ShareLinkChatAgent implements Agent, Conversational
{
    use Promptable;

    public string $systemPrompt = '';

    /** @var list<Message> */
    public array $history = [];

    public function instructions(): string
    {
        return $this->systemPrompt;
    }

    /**
     * @return list<Message>
     */
    public function messages(): iterable
    {
        return $this->history;
    }
}
