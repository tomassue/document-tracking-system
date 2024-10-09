<?php

namespace App\Livewire\Settings;

use App\Models\Ref_Offices_Model;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Offices | Document tracking system')]
class Offices extends Component
{
    use WithPagination;

    public $id_ref_office, $search, $office_name; //NOTE - wire:model
    public $editMode;

    public function render()
    {
        $data = [
            'ref_offices' => $this->loadRefOffices()
        ];

        return view('livewire.settings.offices', $data);
    }

    public function loadRefOffices()
    {
        $ref_offices = Ref_Offices_Model::select(
            'id',
            'office_name'
        )
            ->where('office_name', 'like', '%' . $this->search . '%')
            ->paginate(10);

        return $ref_offices;
    }

    public function clear()
    {
        $this->resetValidation();
        $this->reset();
    }

    public function rules()
    {
        return [
            'office_name' => 'required|unique:ref_offices,office_name'
        ];
    }

    public function validationAttributes()
    {
        return [
            'office_name' => 'office'
        ];
    }

    public function add()
    {
        $this->validate();

        $data = [
            'office_name' => $this->office_name
        ];
        $query = Ref_Offices_Model::query();
        $query->create($data);

        $this->reset('office_name');
        $this->dispatch('hide-officeModal');
        $this->dispatch('show-success-save-message-toast');
    }

    public function edit(Ref_Offices_Model $key)
    {
        $this->id_ref_office = $key->id;
        $this->office_name = $key->office_name;
        $this->editMode = true;
        $this->dispatch('show-officeModal-edit');
    }

    public function update()
    {
        $this->validate();

        $data = [
            'office_name' => $this->office_name
        ];

        $query = Ref_Offices_Model::findOrFail($this->id_ref_office);
        $query->update($data);

        $this->reset('office_name');
        $this->dispatch('hide-officeModal');
        $this->dispatch('show-success-save-message-toast');
    }
}
