<?php

namespace App\Livewire;

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

#[Title('Dashboard | Document Tracking System')]
class Dashboard extends Component
{
    use WithPagination, WithFileUploads;

    public $document_history = [];
    public $editMode = false;
    public $attachment = [], $file_title, $file_data;

    /* ---------------------------- Incoming Request ---------------------------- */
    public $incoming_request_category, $incoming_request_status, $incoming_request_office_barangay_organization, $incoming_request_date, $incoming_request_category_2, $incoming_request_venue, $incoming_request_start_time,  $incoming_request_end_time, $incoming_request_description;
    public $edit_document_id;
    /* ---------------------------- Incoming Request ---------------------------- */

    /* ---------------------------- Incoming Documents ---------------------------- */
    public $incoming_document_category, $incoming_document_no, $incoming_document_date, $incoming_document_info, $incoming_document_status;
    public $edit_document_no, $document_no, $document_info;
    /* ---------------------------- Incoming Documents ---------------------------- */

    public function render()
    {
        $data = [
            'incoming_requests_cpso' => $this->loadIncomingRequestsCPSO(),
            'incoming_documents' => $this->loadIncomingDocumentsCPSO()
        ];
        return view('livewire.dashboard', $data);
    }

    public function editIncomingRequests($key)
    {
        // TODO
        $this->editMode = true;
        $this->edit_document_id = $key;

        $incoming_request = Incoming_Request_CPSO_Model::where('incoming_request_id', $key)->first();
        $document_history = Document_History_Model::where('document_id', $key)->latest()->first(); //NOTE - latest() returns the most recent record based on the `created_by` column. This ia applicable to our document_history since we store multiple foreign keys to track updates and who updated them. We mainly want to return the latest status and populate it to our `status-select` when `editMode` is true.

        $this->dispatch('set-incoming_category', $incoming_request->incoming_category);
        $this->dispatch('set-status', $document_history->status);
        $this->incoming_request_office_barangay_organization = $incoming_request->office_or_barangay_or_organization;
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

    public function editIncomingDocuments($key)
    {
        $this->editMode = true;
        $this->edit_document_no = $key;

        $incoming_documents = Incoming_Documents_CPSO_Model::where('document_no', $key)->first();
        $document_history = Document_History_Model::where('document_id', $key)->latest()->first(); //NOTE - latest() returns the most recent record based on the `created_by` column. This ia applicable to our document_history since we store multiple foreign keys to track updates and who updated them. We mainly want to return the latest status and populate it to our `status-select` when `editMode` is true.

        $this->dispatch('set-incoming-category-documents-select', $incoming_documents->incoming_document_category);
        $this->dispatch('set-document-status-select', $document_history->status);
        $this->document_no = $incoming_documents->document_no;
        $this->dispatch('set-document-incoming-date', $incoming_documents->date);
        $this->document_info = $incoming_documents->document_info;

        $this->dispatch('show-documentsModal');
        // dd($incoming_documents->incoming_document_category);
    }

    public function update()
    {
        $incoming_request = Incoming_Request_CPSO_Model::where('incoming_request_id', $this->edit_document_id)->first();
        $incoming_documents = Incoming_Documents_CPSO_Model::where('document_no', $this->edit_document_no)->first();

        if ($incoming_request) {
            $document_history = Document_History_Model::query();
            $data = [
                'document_id' => $this->edit_document_id,
                'status' => $this->incoming_request_status,
                'user_id' => Auth::user()->id,
                'remarks' => 'updated_by'
            ];
            $document_history->create($data);

            $this->clear();
            $this->dispatch('hide-requestModal');
            $this->dispatch('show-success-update-message-toast');
        }

        if ($incoming_documents) {
            $document_history = Document_History_Model::query();
            $document_history_data = [
                'document_id' => $this->edit_document_no,
                'status' => $this->incoming_document_status,
                'user_id' => Auth::user()->id,
                'remarks' => 'updated_by'
            ];
            $document_history->create($document_history_data);

            $this->clear();
            $this->dispatch('hide-documentsModal');
            $this->dispatch('show-success-update-message-toast');
        }
    }

    // NOTE - This is for showing the other virtual select if category == 'venue' is selected.
    public function updatedIncomingRequestCategory2()
    {
        // NOTE - When user chooses venue.
        if ($this->incoming_request_category_2 == 'venue') {
            if ($this->editMode == true) {
                $this->dispatch('initialize-venue-select');
            }
        } else {
            // NOTE - When user selects other options aside 'venue'.
            $this->dispatch('destroy-venue-select');
        }
    }

    public function details($key)
    {
        $incoming_documents = Incoming_Documents_CPSO_Model::where('document_no', $key)->first();
        $incoming_request = Incoming_Request_CPSO_Model::where('incoming_request_id', $key)->first();

        $document_history = Document_History_Model::where('document_id', $key)->latest()->first();

        if ($incoming_documents) {

            $this->incoming_document_category = $incoming_documents->incoming_document_category;
            $this->incoming_document_no = $incoming_documents->document_no;
            $this->incoming_document_date = (new DateTime($incoming_documents->date))->format('M d, Y');
            $this->incoming_document_info = $incoming_documents->document_info;
            $this->incoming_document_status = $document_history->status;

            foreach (json_decode($incoming_documents->attachment) as $item) {
                $file = File_Data_Model::where('id', $item)
                    ->select(
                        'id',
                        'file_name',
                    )
                    ->first();
                $file->file_size = $this->convertSize($file->file_size);
                $this->attachment[] = $file;
            }

            $this->dispatch('show-viewDetailsDocumentsModal');
        } elseif ($incoming_request) { // NOTE - $incoming_request is temporarily moved to editIncomingRequests()

            $this->incoming_request_category                        = $incoming_request->incoming_category;
            $this->incoming_request_status                          = $document_history->status;
            $this->incoming_request_office_barangay_organization    = $incoming_request->office_or_barangay_or_organization;
            $this->incoming_request_date                            = (new DateTime($incoming_request->request_date))->format('M d, Y');
            $this->incoming_request_category_2                      = $incoming_request->category;
            $this->incoming_request_venue                           = $incoming_request->venue; //NOTE - Sub-category
            $this->incoming_request_start_time                      = (new DateTime($incoming_request->start_time))->format('g:i A');
            $this->incoming_request_end_time                        = (new DateTime($incoming_request->end_time))->format('g:i A');
            $this->incoming_request_description                     = $incoming_request->description;

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

            $this->dispatch('show-viewDetailsRequestModal');
        }
    }

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

    public function history($key)
    {
        $this->document_history = []; //NOTE - Set this to empty to avoid data to stack.

        $document_history = Document_History_Model::join('users', 'users.id', '=', 'document_history.user_id')
            ->where('document_history.user_id', Auth::user()->id)
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

    public function clear()
    {
        $this->reset();
        $this->resetValidation();
        // $this->dispatch('clear-plugins');
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
            ->where('latest_document_history.status', 'pending')
            ->orderBy('incoming_request_cpso.request_date', 'ASC')
            ->paginate(10, pageName: 'incoming-requests');

        return $incoming_requests_cpso;
    }

    public function loadIncomingDocumentsCPSO()
    {
        $incoming_documents = DB::table('incoming_documents_cpso')
            ->join('ref_category', 'ref_category.id', '=', 'incoming_documents_cpso.incoming_document_category')
            ->join(DB::raw('(SELECT document_id, status
                FROM document_history
                WHERE id IN (
                    SELECT MAX(id)
                    FROM document_history
                    GROUP BY document_id
                )) AS latest_document_history'), 'latest_document_history.document_id', '=', 'incoming_documents_cpso.document_no')
            ->select(
                'ref_category.category',
                'incoming_documents_cpso.document_no',
                'incoming_documents_cpso.incoming_document_category',
                'incoming_documents_cpso.document_info',
                'latest_document_history.status',
                DB::raw("DATE_FORMAT(incoming_documents_cpso.date, '%b %d, %Y') AS date")
            )
            ->where('latest_document_history.status', 'pending')
            ->orderBy('incoming_documents_cpso.date', 'ASC')
            ->paginate(10, pageName: 'incoming-documents');

        return $incoming_documents;
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
}
