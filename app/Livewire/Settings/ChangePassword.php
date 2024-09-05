<?php

namespace App\Livewire\Settings;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Change Password | Document tracking system')]
class ChangePassword extends Component
{
    public $current_password;
    public $new_password;
    public $confirm_password;

    public function render()
    {
        return view('livewire.settings.change-password');
    }

    public function update()
    {
        $rules = [
            'current_password' => 'required|current_password',
            'new_password'  =>  'required|min:8|regex:/^(?=.*[a-zA-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/',
            'confirm_password' => 'required|same:new_password',
        ];

        $this->validate($rules);

        $query = User::findOrFail(Auth::user()->id);
        $query->update([
            'password' => Hash::make($this->new_password)
        ]);
        $this->reset();
        return redirect()->route('dashboard');
        // $this->dispatch('show-success-update-message-toast');
    }
}
