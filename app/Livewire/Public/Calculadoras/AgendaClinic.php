<?php

namespace App\Livewire\Public\Calculadoras;

use App\Traits\HelperFunctions;
use Livewire\Attributes\Renderless;
use Livewire\Attributes\Session;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('W.M. Soluções Tecnológicas - Automação e Integrações com IA')]
class AgendaClinic extends Component
{
    use HelperFunctions;

    #[Session('agenda_clinic:setup')]
    public $setup = 3000;

    #[Session('agenda_clinic:base1')]
    public $base1 = 900;

    #[Session('agenda_clinic:base3')]
    public $base3 = 750;

    #[Session('agenda_clinic:base6')]
    public $base6 = 600;

    #[Session('agenda_clinic:whatsapp')]
    public $whatsapp = 98.99;

    #[Session('agenda_clinic:instagram')]
    public $instagram = 98.99;

    #[Session('agenda_clinic:proxy')]
    public $proxy = 10.00;

    public $mostrarFormulario = false;

    public $crm = [
        'enabled' => "",
        'leads' => 10000,
        'funnels' => 5,
        'tags' => 20,
        'automations' => 10,
    ];
    public $campaigns = [
        'enabled' => "",
        'campaigns' => 5,
        'leads' => 1000,
    ];
    
    #[Renderless]
    public function updateValues()
    {
        $this->mostrarFormulario = false;
        return;
    }
    
    #[Renderless]
    public function resetValues()
    {
        $this->reset('setup', 'base1', 'base3', 'base6', 'whatsapp', 'instagram', 'proxy');
        return;
    }

    public function render()
    {
        return view('livewire.public.calculadoras.agenda-clinic');
    }
}
