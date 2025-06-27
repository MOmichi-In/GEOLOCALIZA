<?php

namespace App\Livewire;

use Livewire\Component;

class UserLivewire extends Component
{
    public function render()
    {
        return view('livewire.user-livewire')->layout('layouts.app');
    }
}
