<?php

namespace App\Livewire\Incoming;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Documents extends Component
{
    /**
     * NOTE
     * A number of offices uses this system but with different system requirements but mostly of the requirements somehow seems to be similar.
     * We will be controlling the data access through which office the user is under.
     * 
     * Use Auth::user()->ref_office == 'CPSO' to control it. -->EXAMPLE
     * 
     * LOGS
     * - CPSO is ONGOING
     */

    use WithFileUploads, WithPagination;

    public $editMode = false, $status;
    public $search;
    public $incoming_document_category, $document_info, $attachment = [], $date;


    public function render()
    {
        return view('livewire.incoming.documents');
    }

    public function clear()
    {
        $this->reset();
        $this->resetValidation();
        $this->dispatch('clear-plugins');
    }

    public function add()
    {
        dd($this);
    }
}
