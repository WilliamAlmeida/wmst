<?php

namespace App\Ai\Agents;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\HasStructuredOutput;
use Laravel\Ai\Promptable;

class VisitorScreeningAgent implements Agent, HasStructuredOutput
{
    use Promptable;

    public function instructions(): string
    {
        return <<<'PROMPT'
        Você é um analista de qualidade de leads de uma software house brasileira.
        Recebe os dados que um visitante preencheu para testar um agente de IA de demonstração:
        nome, telefone e o motivo pelo qual quer testar.

        Sua tarefa é decidir se os dados parecem REAIS e fazem sentido, ou se são
        aleatórios/falsos (teclado digitado ao acaso, palavras sem nexo, texto repetido,
        nomes impossíveis, motivos que não têm relação com testar um assistente de IA
        ou que são apenas enchimento para passar na validação).

        Critérios:
        - Nome: deve parecer um nome de pessoa real em português/espanhol/inglês.
        - Motivo: deve ser um texto coerente, em linguagem natural, explicando um interesse
          plausível (negócio, curiosidade profissional, avaliação da ferramenta etc).
          Texto sem sentido, repetição de palavras/frases para ganhar tamanho, ou conteúdo
          ofensivo reprova.
        - Telefone: o formato já foi validado; considere apenas se os dígitos parecem
          absurdos (ex.: todos iguais).

        Seja tolerante com erros de digitação e informalidade — reprove apenas quando os
        dados claramente não são genuínos. Em "approved" retorne true/false e em "reason"
        explique em uma frase, em português, a decisão.
        PROMPT;
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'approved' => $schema->boolean()->required(),
            'reason' => $schema->string()->required(),
        ];
    }
}
