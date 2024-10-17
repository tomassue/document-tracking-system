<?php

namespace App\Livewire\CPSO;

use App\Models\Document_History_Model;
use App\Models\File_Data_Model;
use App\Models\Incoming_Documents_CPSO_Model;
use App\Models\Incoming_Request_CPSO_Model;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

#[Title('Dashboard | CPSO Management System')]
class Dashboard extends Component
{
    public $dashboard = "dashboard";

    public function render()
    {
        return view('livewire.dashboard');
    }
}
