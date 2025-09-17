<?php

namespace App\Livewire\Public\TermsUse;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.layouts.guest')]
#[Title('Termos de Uso')]
class TermsUseIndex extends Component
{
    public $back_to_url = null;

    public function mount()
    {
        if (url()->previous() && url()->previous() !== url()->current()) {

            if (parse_url(url()->previous(), PHP_URL_HOST) !== parse_url(url()->current(), PHP_URL_HOST)) {
                $this->back_to_url = route('home');
                return;
            }

            $this->back_to_url = url()->previous();

        } else {
            $this->back_to_url = route('home');
        }
    }

    public function render()
    {
        return view('livewire.public.terms-use.terms-use-index');
    }
}
