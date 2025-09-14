<?php

namespace App\Livewire\Public\Home;

use Livewire\Attributes\Rule;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('W.M. Soluções Tecnológicas - Automação e Integrações com IA')]
class HomeIndex extends Component
{
    #[Rule('required|min:3|max:100')]
    public string $name = '';
    
    #[Rule('required|email|max:100')]
    public string $email = '';
    
    #[Rule('required|min:10|max:20')]
    public string $phone = '';
    
    #[Rule('required|min:10|max:500')]
    public string $message = '';
    
    public bool $formSuccess = false;
    
    public function submitContact()
    {
        $this->validate();
        
        // Here you would typically save to database or send email
        // For now, we'll just show a success message
        
        $this->formSuccess = true;
        $this->reset(['name', 'email', 'phone', 'message']);
    }
    
    public function render()
    {
        return view('livewire.public.home.home-index');
    }
}
