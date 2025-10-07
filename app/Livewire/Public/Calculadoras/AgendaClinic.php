<?php

namespace App\Livewire\Public\Calculadoras;

use App\Traits\HelperFunctions;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('W.M. Soluções Tecnológicas - Automação e Integrações com IA')]
class AgendaClinic extends Component
{
    use HelperFunctions;

    public function render()
    {
        return view('livewire.public.calculadoras.agenda-clinic');
    }
}
