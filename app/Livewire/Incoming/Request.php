<?php

namespace App\Livewire\Incoming;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Request extends Component
{
    use WithPagination, WithFileUploads;

    public $search, $office_barangay_organization, $request_date, $category, $start_time, $end_time, $description, $attachment;
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

    public function add()
    {
        dd($this);
    }
}
