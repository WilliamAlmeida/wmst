<?php

namespace App\Ai\Agents;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\HasStructuredOutput;
use Laravel\Ai\Promptable;

class PromptImproverAgent implements Agent, HasStructuredOutput
{
    use Promptable;

    public function instructions(): string
    {
        return <<<'PROMPT'
        Você é um especialista em engenharia de prompts para agentes de IA de atendimento e vendas.
        Recebe o prompt (system prompt) atual de um agente e o nome do agente, e devolve uma versão melhorada.

        Regras:
        - Escreva em português do Brasil, com acentuação correta.
        - Torne o prompt claro, específico e acionável: defina o papel do agente, o tom de voz,
          os objetivos, os limites e o que fazer quando não souber responder (encaminhar a um humano).
        - Preserve a intenção e os fatos do prompt original; não invente informações sobre a empresa.
        - Retorne o prompt melhorado em texto puro (sem markdown, sem títulos, sem aspas ao redor).
        - Em "summary", explique em 1 ou 2 frases, em português, o que foi melhorado.
        PROMPT;
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'improved_prompt' => $schema->string()->required(),
            'summary' => $schema->string()->required(),
        ];
    }
}
