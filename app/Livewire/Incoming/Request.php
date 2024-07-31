<?php

namespace App\Livewire\Incoming;

use Livewire\Component;
use Livewire\WithPagination;

class Request extends Component
{
    use WithPagination;

    public $search;
    public $editMode;

    public function render()
    {
        return view('livewire.incoming.request');
    }

    public function clear()
    {
        $this->reset();
        $this->resetValidation();
    }
}
