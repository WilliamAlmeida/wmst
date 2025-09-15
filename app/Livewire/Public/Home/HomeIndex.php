<?php

namespace App\Livewire\Public\Home;

use App\Traits\HelperFunctions;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('W.M. Soluções Tecnológicas - Automação e Integrações com IA')]
class HomeIndex extends Component
{
    use HelperFunctions;

    public string $name = '';
    public string $email = '';
    public string $phone = '';
    public string $message = '';
    
    public bool $formSuccess = false;

    public $url_whatsapp = '';

    public function mount()
    {
        $this->url_whatsapp = $this->linkWhatsapp('12982184879');
    }
    
    public function submitContact()
    {
        $this->validate([
            'name' => 'required|min:3|max:100',
            'email' => 'required|email|max:100',
            'phone' => 'required|min:10|max:20',
            'message' => 'required|min:10|max:500',
        ]);
        
        $this->formSuccess = true;
        $this->reset(['name', 'email', 'phone', 'message']);
    }
    
    public function render()
    {
        return view('livewire.public.home.home-index');
    }
}
