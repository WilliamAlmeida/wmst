<?php

namespace App\Http\Requests\Public;

use Illuminate\Foundation\Http\FormRequest;

class StartAgentChatRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        // Normaliza o telefone: remove espaços, parênteses, hífens e pontos.
        $phone = preg_replace('/[\s\(\)\-\.]+/', '', (string) $this->input('phone', ''));

        $this->merge([
            'name' => trim((string) $this->input('name', '')),
            'phone' => $phone,
            'reason' => trim((string) $this->input('reason', '')),
        ]);
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:6', 'max:120'],
            'phone' => ['required', 'string', 'regex:/^\+55\d{10,11}$/'],
            'reason' => ['required', 'string', 'min:100', 'max:2000'],
        ];
    }

    /**
     * Mensagens propositalmente vagas: não revelam os limites mínimos para
     * evitar que o visitante preencha com texto aleatório só para passar.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nome inválido ou muito curto.',
            'name.min' => 'Nome inválido ou muito curto.',
            'name.max' => 'Nome inválido.',
            'phone.required' => 'Telefone inválido. Informe no formato +55 (DDD) número.',
            'phone.regex' => 'Telefone inválido. Informe no formato +55 (DDD) número.',
            'reason.required' => 'Motivo inválido ou muito curto.',
            'reason.min' => 'Motivo inválido ou muito curto.',
            'reason.max' => 'Motivo muito longo.',
        ];
    }
}
