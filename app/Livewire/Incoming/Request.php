<?php

namespace App\Livewire\Incoming;

use App\Models\Document_History_Model;
use App\Models\File_Data_Model;
use App\Models\Incoming_Request_Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class Request extends Component
{
    use WithPagination, WithFileUploads;

    public $search, $incoming_category, $status, $office_barangay_organization, $request_date, $category, $venue, $start_time, $end_time, $description, $attachment = [];
    public $file_title, $file_data;
    public $editMode, $edit_document_id;

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
        $data = [
            'incoming_requests' => $this->loadIncomingRequests()
        ];

        return view('livewire.incoming.request', $data);
    }

    public function clear()
    {
        $this->reset();
        $this->resetValidation();
        $this->dispatch('clear-plugins');
    }

    public function updatedCategory()
    {
        // NOTE - When user chooses venue.
        if ($this->category == 'venue') {
            if ($this->editMode == false) {
                $this->dispatch('initialize-venue-select');
            }
        } else {
            // NOTE - When user selects other options aside 'venue'.
            $this->dispatch('destroy-venue-select');
        }
    }

    public function add()
    {
        $this->validate();

        if ($this->attachment) {
            # Iterate over each file.
            # Uploading small files is okay with BLOB data type. I encountered an error where uploading bigger size such as PDF won't upload in the database which is resulting an error.
            try {
                foreach ($this->attachment as $file) {
                    $file_data = File_Data_Model::create([
                        'file_name' => $file->getClientOriginalName(),
                        'file_size' => $file->getSize(),
                        'file_type' => $file->extension(),
                        'file' => file_get_contents($file->path()),
                        'user_id' => Auth::user()->id
                    ]);
                    // Store the ID of the saved file
                    $file_data_IDs[] = $file_data->id;
                }
            } catch (\Exception $e) {
                // dd($e->getMessage());
                $this->dispatch('show-something-went-wrong-toast');
            }

            $incoming_request_data = [
                'incoming_request_id' => $this->generateUniqueNumber(),
                'incoming_category' => $this->incoming_category,
                'office_or_barangay_or_organization' => $this->office_barangay_organization,
                'request_date' => $this->request_date,
                'category' => $this->category,
                'venue' => $this->venue,
                'start_time' => $this->start_time,
                'end_time' => $this->end_time,
                'description' => $this->description,
                'files' => json_encode($file_data_IDs)
            ];
            $incoming_request = Incoming_Request_Model::create($incoming_request_data);

            $document_history_data = [
                'document_id' => $incoming_request->incoming_request_id,
                'status' => 'pending',
                'user_id' => Auth::user()->id,
                'remarks' => 'created_by'
            ];
            Document_History_Model::create($document_history_data);

            $this->clear();
            $this->dispatch('hide-requestModal');
            $this->dispatch('show-success-save-message-toast');
        }
    }

    #[On('edit-mode')]
    public function edit($key)
    {
        // dd($key);
        $this->editMode = true;
        $this->edit_document_id = $key;

        $incoming_request = Incoming_Request_Model::where('incoming_request_id', $key)->first();
        $document_history = Document_History_Model::where('document_id', $key)->first();

        $this->dispatch('set-incoming_category', $incoming_request->incoming_category);
        $this->dispatch('set-status', $document_history->status);
        $this->office_barangay_organization = $incoming_request->office_or_barangay_or_organization;
        $this->dispatch('set-request-date', $incoming_request->request_date);
        $this->dispatch('set-category', $incoming_request->category);
        ($incoming_request->venue ? $this->dispatch('set-venue', $incoming_request->venue) : '');
        $this->dispatch('set-from-time', $this->timeToMinutes($incoming_request->start_time));
        $this->dispatch('set-end-time', $this->timeToMinutes($incoming_request->end_time));
        $this->dispatch('set-myeditorinstance', $incoming_request->description);

        foreach (json_decode($incoming_request->files) as $item) {
            $file = File_Data_Model::where('id', $item)
                ->select(
                    'id',
                    'file_name',
                )
                ->first();
            $file->file_size = $this->convertSize($file->file_size);
            $this->attachment[] = $file;
        }

        $this->dispatch('show-requestModal');
    }

    public function update()
    {
        //NOTE - For now, we will update the status only and record the action in our document_history
        // dd($this->status);

        $document_history = Document_History_Model::query();
        $data = [
            'document_id' => $this->edit_document_id,
            'status' => $this->status,
            'user_id' => Auth::user()->id,
            'remarks' => 'updated_by'
        ];
        $document_history->create($data);

        $this->clear();
        $this->dispatch('hide-requestModal');
        $this->dispatch('show-success-update-message-toast');
    }

    #[On('preview-attachment')]
    public function previewAttachment($key)
    {
        if ($key) {
            $file = File_Data_Model::findOrFail($key);

            if ($file && $file->file) {
                $this->file_title = $file->file_name;
                $this->file_data = base64_encode($file->file);
            }
        }
    }

    #[On('history')]
    public function history($key)
    {
        $document_history = Document_History_Model::join('users', 'users.id', '=', 'document_history.user_id')
            ->where('document_history.user_id', Auth::user()->id)
            ->where('document_history.document_id', $key)
            ->orderBy('document_history.updated_at', 'DESC')
            ->get();

        dd($document_history);
    }

    public function loadIncomingRequests()
    {
        $incoming_requests = DB::table('incoming_request')
            ->join(DB::raw('(SELECT document_id, status
                    FROM document_history
                    WHERE id IN (
                        SELECT MAX(id)
                        FROM document_history
                        GROUP BY document_id
                    )) AS latest_document_history'), 'latest_document_history.document_id', '=', 'incoming_request.incoming_request_id')
            ->select(
                'incoming_request.incoming_request_id AS id',
                DB::raw("DATE_FORMAT(incoming_request.request_date, '%b %d, %Y') AS request_date"),
                'incoming_request.office_or_barangay_or_organization',
                'incoming_request.category',
                'incoming_request.venue',
                'latest_document_history.status'
            )
            ->orderBy('incoming_request.request_date', 'ASC')
            ->get();

        return $incoming_requests;
    }

    //NOTE - file_size in KB convert to MB 
    public function convertSize($sizeInKB)
    {
        return round($sizeInKB / 1024, 2); // Convert KB to MB and round to 2 decimal places
    }

    //NOTE - This is for retrieving data from database to pickatime. To avoid errors due to format, we convert the time to minutes.
    public function timeToMinutes($time)
    {
        $hours = intval(date('H', strtotime($time)));
        $minutes = intval(date('i', strtotime($time)));
        return [$hours, $minutes];
    }

    //NOTE - Method to generate a unique number
    private function generateUniqueNumber()
    {
        // Get the current Unix timestamp
        $timestamp = time();

        // NOTE - You can adjust the numbers here.
        // Extract the last eight digits of the timestamp. 
        $uniqueIdentifier = substr($timestamp, -8);

        // Generate four random lowercase letters (a-z)
        $randomLetters = '';
        for ($i = 0; $i < 4; $i++) {
            $randomLetters .= strtoupper(chr(mt_rand(97, 122))); // ASCII values for lowercase letters
        }

        // Concatenate the random letters with the eight-digit number
        $uniqueNumber = $uniqueIdentifier . $randomLetters;

        // Shuffle the unique number (digits and letters)
        $shuffledNumber = str_shuffle($uniqueNumber);

        return $shuffledNumber;
    }
}
