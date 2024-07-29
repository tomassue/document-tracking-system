<?php

namespace App\Livewire\Settings;

use App\Models\Ref_Offices_Model;
use Livewire\Component;

class Offices extends Component
{
    public $office_name;

    public function render()
    {
        return view('livewire.settings.offices');
    }

    public function add()
    {
        $rules = [
            'office_name' => 'required'
        ];

        $this->validate($rules);

        $data = [
            'office_name' => $this->office_name
        ];
        Ref_Offices_Model::create($data);
    }
}
