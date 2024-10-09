<?php

namespace App\Livewire\Settings;

use App\Models\Ref_Offices_Model;
use App\Models\User;
use App\Models\User_Offices_Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('User Management | Document tracking system')]
class UserManagement extends Component
{
    use WithPagination;

    public $search, $full_name, $selectedRefOffice, $username;
    public $editMode = false, $user_id, $user_offices_id, $is_active;

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
            'username' => 'required|unique:users,username'
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
            'username' => $this->username,
            'password' => Hash::make('password'),
            'role' => '1',
            'is_active' => '1'
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
        $this->username = $query_user->username;

        $query_user_offices = User_Offices_Model::leftJoin('ref_offices', 'ref_offices.id', '=', 'user_offices.office_id')
            ->where('user_offices.user_id', $key)
            ->select(
                'user_offices.office_id',
                'ref_offices.office_name'
            )
            ->first();
        $this->user_offices_id = $query_user_offices->id; //NOTE - This will be used in update().

        $this->dispatch('office_id-edit', value: $query_user_offices->office_id);
        $this->dispatch('is_active_edit', value: $query_user->is_active);
        $this->dispatch('show-userManagementModal');
    }

    public function update()
    {
        $rules = [
            'full_name' => 'required',
            'username' => 'required',
        ];

        $this->validate($rules);

        $data_user = [
            'name' => $this->full_name,
            'username' => $this->username,
            'is_active' => $this->is_active,
        ];

        $query_user = User::findOrFail($this->user_id);

        // Check for duplicates based on 'name' and 'email', ignoring 'is_active'
        $check_query_user_duplicates = User::where(function ($query) {
            $query->where('name', $this->full_name)
                ->orWhere('email', $this->username);
        })
            ->where('id', '!=', $this->user_id) // Exclude the current user
            ->exists();

        if ($check_query_user_duplicates) {
            $this->dispatch('show-error-duplicate-entry-message-toast');
        } else {

            // Update the user
            $query_user->update($data_user);

            // Update the user office
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

    #[On('reset-password')]
    public function resetPassword($id)
    {
        User::whereId($id)->update([
            'password' => Hash::make('password'), // Hash the password and update the user
        ]);
        $this->reset();
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
                'ref_offices.office_name',
                DB::raw("
                CASE
                    WHEN users.is_active = '1' THEN 'Active'
                    WHEN users.is_active = '0' THEN 'Inactive'
                    ELSE ''
                END AS status
                ")
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
            'office_name',
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
