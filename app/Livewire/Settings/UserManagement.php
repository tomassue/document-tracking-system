<?php

namespace App\Livewire\Settings;

use App\Models\Ref_Offices_Model;
use App\Models\User;
use App\Models\User_Offices_Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithPagination;

class UserManagement extends Component
{
    use WithPagination;

    public $search, $full_name, $selectedRefOffice, $username;
    public $editMode = false;

    public function render()
    {
        $data = [
            'users' => $this->loadUsers(),
            'ref_offices' => $this->loadRefOffices()
        ];

        return view('livewire.settings.user-management', $data);
    }

    public function rules()
    {
        return [
            'full_name' => 'required',
            'selectedRefOffice' => 'required',
            'username' => 'required|email:rfc,dns|unique:users,email'
        ];
    }

    public function validationAttributes()
    {
        return [
            'selectedRefOffice' => 'office'
        ];
    }

    public function add()
    {
        $this->validate();

        $data_user = [
            'name' => $this->full_name,
            'email' => $this->username,
            'password' => Hash::make('password')
        ];

        $user = User::create($data_user);

        $data_user_office = [
            'user_id' =>  $user->id,
            'office_id' => $this->selectedRefOffice,
        ];

        User_Offices_Model::create($data_user_office);

        $this->dispatch('hide-userManagementModal');
        $this->clear();
        $this->dispatch('reset-virtual-select');
        $this->dispatch('show-success-save-message-toast');
    }

    public function edit($key)
    {
        $query_user = User::findOrFail($key);
        $this->full_name = $query_user->name;
        $this->username = $query_user->email;

        $query_user_offices = User_Offices_Model::leftJoin('ref_offices', 'ref_offices.id', '=', 'user_offices.office_id')
            ->where('user_offices.user_id', $key)
            ->select(
                'user_offices.office_id',
                'ref_offices.office_name'
            )
            ->first();

        $this->editMode = true;
        //TODO - Display the office in edit mode.
        $this->dispatch('show-userManagementModal');
    }

    public function clear()
    {
        $this->resetValidation();
        $this->reset();
    }

    public function loadUsers()
    {
        $query = User::select(
            'id',
            'name'
        )
            ->where('name', 'like', '%' . $this->search . '%')
            ->where('id', '!=', Auth::user()->id)
            ->paginate(10);

        return $query;
    }

    public function loadRefOffices()
    {
        $ref_offices = Ref_Offices_Model::select(
            'id',
            'office_name'
        )
            ->get()
            ->map(
                function ($item) {
                    return [
                        'label' => $item->office_name,
                        'value' => $item->id
                    ];
                }
            );

        return $ref_offices;
    }
}
