<?php

namespace App\Livewire\Public\Agents;

use App\Traits\HelperFunctions;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('W.M. Soluções Tecnológicas - Automação e Integrações com IA')]
class AgentIndex extends Component
{
    use HelperFunctions;

    private $code_pass = '202509';

    public function mount()
    {
        $code = request('code');
        if($code != $this->code_pass) {
            abort(403, 'Você não tem permissão para testar nosso Agente.');
        }
    }
    
    public function render()
    {
        return view('livewire.public.agents.agent-index');
    }
}
