<?php

namespace App\Livewire\Incoming;

use App\Models\File_Data_Model;
use App\Models\Incoming_Request_Model;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Request extends Component
{
    use WithPagination, WithFileUploads;

    public $search, $office_barangay_organization, $request_date, $category, $start_time, $end_time, $description, $attachment = [];
    public $editMode;

    public function rules()
    {
        return [
            'office_barangay_organization' => 'required',
            'request_date' => 'required',
            'category' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            'description' => 'required',
            'attachment' => 'required'
        ];
    }

    public function validationAttributes()
    {
        return [
            'office_barangay_organization' => 'input'
        ];
    }

    public function render()
    {
        return view('livewire.incoming.request');
    }

    public function clear()
    {
        $this->reset();
        $this->resetValidation();
        $this->dispatch('clear-plugins');
    }

    public function add()
    {
        // $this->validate();

        $this->clear();
        $this->dispatch('hide-requestModal');
        $this->dispatch('show-success-save-message-toast');

        // if ($this->attachment) {
        //     # Iterate over each file.
        //     # Uploading small files is okay with BLOB data type. I encountered an error where uploading bigger size such as PDF won't upload in the database which is resulting an error.
        //     try {
        //         foreach ($this->attachment as $file) {
        //             $file_data = File_Data_Model::create([
        //                 'file_name' => $file->getClientOriginalName(),
        //                 'file_size' => $file->getSize(),
        //                 'file_type' => $file->extension(),
        //                 'file' => file_get_contents($file->path()),
        //                 'user_id' => Auth::user()->id
        //             ]);
        //             // Store the ID of the saved file
        //             $file_data_IDs[] = $file_data->id;
        //         }
        //     } catch (\Exception $e) {
        //         // dd($e->getMessage());
        //         $this->dispatch('show-something-went-wrong-toast');
        //     }

        //     $incoming_request_data = [
        //         'incoming_category' => 'request',
        //         'office_or_barangay_or_organization' => $this->office_barangay_organization,
        //         'request_date' => $this->request_date,
        //         'category' => $this->category,
        //         'start_time' => $this->start_time,
        //         'end_time' => $this->end_time,
        //         'description' => base64_encode($this->description),
        //         'files' => json_encode($file_data_IDs)
        //     ];
        //     Incoming_Request_Model::create($incoming_request_data);

        //     $this->clear();
        //     $this->dispatch('hide-requestModal');
        //     $this->dispatch('show-success-save-message-toast');
        // }
    }
}
