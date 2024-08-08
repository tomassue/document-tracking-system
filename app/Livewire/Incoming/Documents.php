<?php

namespace App\Livewire\Incoming;

use Livewire\Component;

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


    public function render()
    {
        return view('livewire.incoming.documents');
    }
}
