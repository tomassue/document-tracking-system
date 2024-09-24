<?php

namespace App\Livewire\CPSO\Incoming;

use App\Models\Document_History_Model;
use App\Models\File_Data_Model;
use App\Models\Incoming_Request_CPSO_Model;
use App\Models\Ref_Category_Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;

#[Title('Request | Document Tracking System')]
class Request extends Component
{
    /**
     * NOTE
     * A number of offices uses this system but with different system requirements but mostly of the requirements somehow seems to be similar.
     * We will be controlling the data access through which office the user is under.
     * 
     * Use Auth::user()->ref_office == 'CPSO' to control it. -->EXAMPLE
     * 
     * LOGS
     * - CPSO is ONGOING -> DONE
     */

    use WithPagination, WithFileUploads;

    /**
     * NOTE
     * `$page_type`
     * What happened is that I nested the Request component to Dashboard component.
     * Passing data from the parent component (Dashboard) is like passing props to a typical Blade component.
     * We can receive that data through the child component's mount() method.
     */
    public $page_type = "";

    public $search, $incoming_category, $status, $office_barangay_organization, $request_date, $category, $venue, $start_time, $end_time, $description, $attachment = [];
    public $file_title, $file_data;
    public $editMode, $edit_document_id;
    public $document_history = [];

    // LINK - app\Livewire\CPSO\Incoming\Request.php#34
    public function mount($page_type = "")
    {
        $this->page_type = $page_type;
    }

    public function rules()
    {
        return [
            'incoming_category' => 'required',
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
            'office_barangay_organization' => 'input',
            'incoming_category' => 'input'
        ];
    }

    public function render()
    {

        $data = [
            'incoming_requests_cpso' => $this->loadIncomingRequestsCPSO(),
            'categories' => $this->loadCategories()
        ];

        return view('livewire.CPSO.incoming.request', $data);
    }

    public function clear()
    {
        $this->resetExcept('page_type'); // Since we need the page_type as what I mentioned, we will not clear the property.
        $this->resetValidation();
        $this->dispatch('clear-plugins');
    }

    public function openRequestModal()
    {
        $this->clear();
        $this->dispatch('refresh-plugin');
        $this->dispatch('show-requestModal');
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
            $incoming_request = Incoming_Request_CPSO_Model::create($incoming_request_data);

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

    public function edit($key)
    {
        $this->editMode = true;
        $this->edit_document_id = $key;

        $incoming_request = Incoming_Request_CPSO_Model::where('incoming_request_id', $key)->first();
        $document_history = Document_History_Model::where('document_id', $key)->latest()->first(); //NOTE - latest() returns the most recent record based on the `created_by` column. This ia applicable to our document_history since we store multiple foreign keys to track updates and who updated them. We mainly want to return the latest status and populate it to our `status-select` when `editMode` is true.

        $this->dispatch('set-incoming_category', $incoming_request->incoming_category);
        if ($document_history->status == 'done') {
            $this->dispatch('set-status-disabled', $document_history->status); // Since the status is DONE, we won't allow users to modify the document's status.
        } else {
            $this->dispatch('set-status-enable', $document_history->status);
        }
        $this->office_barangay_organization = $incoming_request->office_or_barangay_or_organization;
        $this->dispatch('set-request-date', $incoming_request->request_date);
        $this->dispatch('set-category', $incoming_request->category);
        $this->dispatch('set-venue', $incoming_request->venue);
        $this->dispatch('set-from-time', $this->timeToMinutes($incoming_request->start_time));
        $this->dispatch('set-end-time', $this->timeToMinutes($incoming_request->end_time));
        $this->dispatch('set-description', $incoming_request->description);

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
        $this->document_history = []; //NOTE - Set this to empty to avoid data to stack.

        $document_history = Document_History_Model::join('users', 'users.id', '=', 'document_history.user_id')
            ->where('document_history.document_id', $key)
            ->select(
                DB::raw("DATE_FORMAT(document_history.created_at, '%b %d, %Y %h:%i%p') AS history_date_time"),
                'document_history.status',
                DB::raw("CASE
                WHEN document_history.remarks = 'created_by' THEN 'Created by'
                WHEN document_history.remarks = 'updated_by' THEN 'Updated by'
                ELSE 'Unknown'
                END AS remarks"),
                'users.name'
            )
            ->orderBy('document_history.updated_at', 'DESC')
            ->get();

        if ($document_history) {
            $this->document_history = $document_history;
            $this->dispatch('show-historyModal');
        }
    }

    public function loadIncomingRequestsCPSO()
    {
        $incoming_requests_cpso = DB::table('incoming_request_cpso')
            ->join(DB::raw('(SELECT document_id, status
                    FROM document_history
                    WHERE id IN (
                        SELECT MAX(id)
                        FROM document_history
                        GROUP BY document_id
                    )) AS latest_document_history'), 'latest_document_history.document_id', '=', 'incoming_request_cpso.incoming_request_id')
            ->select(
                'incoming_request_cpso.incoming_request_id AS id',
                DB::raw("DATE_FORMAT(incoming_request_cpso.request_date, '%b %d, %Y') AS request_date"),
                'incoming_request_cpso.office_or_barangay_or_organization',
                'incoming_request_cpso.category',
                'incoming_request_cpso.venue',
                'latest_document_history.status'
            )
            ->where('incoming_request_cpso.office_or_barangay_or_organization', 'like', '%' . $this->search . '%')
            ->when($this->page_type == "dashboard", function ($query) {
                return $query->where('latest_document_history.status', 'pending');
            })
            ->orderBy('incoming_request_cpso.request_date', 'ASC')
            ->paginate(10);

        return $incoming_requests_cpso;
    }

    public function loadCategories()
    {
        $categories = Ref_Category_Model::select(
            'id',
            'category',
            'document_type',
            'is_active'
        )
            ->where('document_type', 'incoming')
            ->where('is_active', 'yes')
            ->get()
            ->map(function ($item) {
                return [
                    'label' => $item->category,
                    'value' => $item->id
                ];
            });

        return $categories;
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
