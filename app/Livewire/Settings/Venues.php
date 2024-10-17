<?php

namespace App\Livewire\Settings;

use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Venue | Document tracking system')]
class Venues extends Component
{
    public function render()
    {
        return view('livewire.settings.venues');
    }
}
