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
    public $editMode = false, $user_id, $user_offices_id;

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
            'password' => Hash::make('password'),
            'role' => '1'
        ];

        $user = User::create($data_user);

        if ($user) {
            $data_user_office = [
                'user_id' =>  $user->id,
                'office_id' => $this->selectedRefOffice,
            ];

            User_Offices_Model::create($data_user_office);

            $this->dispatch('hide-userManagementModal');
            $this->clear();
            $this->dispatch('reset-virtual-select');
            $this->dispatch('show-success-save-message-toast');
        } else {
            $this->dispatch('show-something-went-wrong-toast');
        }
    }

    public function edit($key)
    {
        $this->editMode = true;

        $query_user = User::findOrFail($key);
        $this->user_id = $query_user->id; //NOTE - This will be used in update().
        $this->full_name = $query_user->name;
        $this->username = $query_user->email;

        $query_user_offices = User_Offices_Model::leftJoin('ref_offices', 'ref_offices.id', '=', 'user_offices.office_id')
            ->where('user_offices.user_id', $key)
            ->select(
                'user_offices.office_id',
                'ref_offices.office_name'
            )
            ->first();
        $this->user_offices_id = $query_user_offices->id; //NOTE - This will be used in update().

        $this->dispatch('office_id-edit', value: $query_user_offices->office_id);
        $this->dispatch('show-userManagementModal');
    }

    public function update()
    {
        $rules = [
            'full_name' => 'required',
            'username' => 'required|email:rfc,dns',
        ];

        $this->validate($rules);

        $data_user = [
            'name' => $this->full_name,
            'email' => $this->username,
        ];
        $query_user = User::findOrFail($this->user_id);

        $check_query_user_duplicates = User::where('name', $this->full_name)
            ->orWhere('email', $this->username)
            ->where('id', '!=', $this->user_id)
            ->exists();

        if ($check_query_user_duplicates) {
            $this->dispatch('show-error-duplicate-entry-message-toast');
        } elseif (!$check_query_user_duplicates) {
            $query_user->update($data_user);

            $data_user_office = [
                'user_id' =>  $this->user_id,
                'office_id' => $this->selectedRefOffice,
            ];
            $query_user_offices = User_Offices_Model::where('user_id', $this->user_id);
            $query_user_offices->update($data_user_office);

            $this->dispatch('hide-userManagementModal');
            $this->clear();
            $this->dispatch('show-success-update-message-toast');
        }
    }

    public function clear()
    {
        $this->resetValidation();
        $this->reset();
        $this->dispatch('reset-virtual-select');
    }

    public function loadUsers()
    {
        $query = User::join('user_offices', 'user_offices.user_id', '=', 'users.id')
            ->join('ref_offices', 'ref_offices.id', '=', 'user_offices.office_id')
            ->select(
                'users.id',
                'users.name',
                'ref_offices.office_name'
            )
            ->where('users.name', 'like', '%' . $this->search . '%')
            ->where('users.id', '!=', Auth::user()->id)
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
