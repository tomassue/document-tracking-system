<?php

namespace App\Livewire\CPSO\Incoming;

use App\Models\Document_History_Model;
use App\Models\File_Data_Model;
use App\Models\Incoming_Documents_CPSO_Model;
use App\Models\Ref_Category_Model;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

#[Title('Documents | Document Tracking System')]
class Documents extends Component
{
    /**
     * NOTE
     * A number of offices uses this system but with different system requirements but mostly of the requirements somehow seems to be similar.
     * We will be controlling the data access through which office the user is under.
     * 
     * Use Auth::user()->ref_office == 'CPSO' to control it. -->EXAMPLE
     * 
     * LOGS
     * - CPSO is ONGOING
     */

    use WithFileUploads, WithPagination;

    // LINK - App\Livewire\CPSO\Incoming\Request.php#33
    public $page_type = "";

    public $search;
    public $editMode = false, $status;
    public $document_history = [];
    public $files = [], $file_title, $file_data;
    public $edit_document_no, $document_no, $incoming_document_category, $document_info, $attachment = [], $date;

    public function mount($page_type = "")
    {
        $this->page_type = $page_type;
    }

    public function render()
    {
        $data = [
            'incoming_documents' => $this->loadIncomingDocumentsCPSO(),
            'categories' => $this->loadCategories()
        ];
        return view('livewire.CPSO.incoming.documents', $data);
    }

    public function rules()
    {
        return [
            'incoming_document_category' => 'required',
            'document_info' => 'required',
            'attachment' => 'required',
            'date' => 'required'
        ];
    }

    public function validationAttributes()
    {
        return [
            'incoming_document_category' => 'category'
        ];
    }

    public function clear()
    {
        $this->resetExcept('page_type');
        $this->resetValidation();
        $this->dispatch('clear-plugins');
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

            $incoming_documents_data = [
                'incoming_document_category' => $this->incoming_document_category,
                'date' => $this->date,
                'document_info' => $this->document_info,
                'attachment' => json_encode($file_data_IDs)
            ];
            $incoming_documents = Incoming_Documents_CPSO_Model::create($incoming_documents_data);

            $document_history_data = [
                'document_id' => $incoming_documents->document_no,
                'status' => 'pending',
                'user_id' => Auth::user()->id,
                'remarks' => 'created_by'
            ];
            Document_History_Model::create($document_history_data);

            $this->clear();
            $this->dispatch('hide-documentsModal');
            $this->dispatch('show-success-save-message-toast');
        }
    }

    public function viewDetails($key)
    {
        $incoming_documents = Incoming_Documents_CPSO_Model::join('ref_category', 'ref_category.id', '=', 'incoming_documents_cpso.incoming_document_category')
            ->where('incoming_documents_cpso.document_no', $key)
            ->first();
        $document_history = Document_History_Model::where('document_id', $key)->first();

        $this->incoming_document_category = $incoming_documents->category;
        $this->document_no = $incoming_documents->document_no;
        $this->date = (new DateTime($incoming_documents->date))->format('M d, Y');
        $this->document_info = $incoming_documents->document_info;
        $this->status = $document_history->status;

        foreach (json_decode($incoming_documents->attachment) as $item) {
            $file = File_Data_Model::where('id', $item)
                ->select(
                    'id',
                    'file_name',
                )
                ->first();
            $file->file_size = $this->convertSize($file->file_size);
            $this->files[] = $file;
        }

        $this->dispatch('show-viewDetailsDocumentsModal');
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

    public function edit($key)
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
        //NOTE - For now, we will update the status only and record the action in our document_history

        $document_history = Document_History_Model::query();
        $document_history_data = [
            'document_id' => $this->edit_document_no,
            'status' => $this->status,
            'user_id' => Auth::user()->id,
            'remarks' => 'updated_by'
        ];
        $document_history->create($document_history_data);

        $this->clear();
        $this->dispatch('hide-documentsModal');
        $this->dispatch('show-success-update-message-toast');
    }

    public function loadIncomingDocumentsCPSO()
    {
        $incoming_documents = DB::table('incoming_documents_cpso')
            ->join(DB::raw('(SELECT document_id, status
                FROM document_history
                WHERE id IN (
                    SELECT MAX(id)
                    FROM document_history
                    GROUP BY document_id
                )) AS latest_document_history'), 'latest_document_history.document_id', '=', 'incoming_documents_cpso.document_no')
            ->join('ref_category', 'ref_category.id', 'incoming_documents_cpso.incoming_document_category')
            ->select(
                'incoming_documents_cpso.document_no',
                'ref_category.category AS incoming_document_category',
                'incoming_documents_cpso.document_info',
                'latest_document_history.status',
                DB::raw("DATE_FORMAT(incoming_documents_cpso.date, '%b %d, %Y') AS date")
            )
            ->where('incoming_documents_cpso.document_info', 'like', '%' . $this->search . '%')
            ->when($this->page_type == 'dashboard', function ($query) {
                return $query->where('latest_document_history.status', 'pending');
            })
            ->orderBy('incoming_documents_cpso.date', 'ASC')
            ->paginate(10);

        return $incoming_documents;
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

    //NOTE - Instead of just dispatching the event show-documentsModal directly from the button through wire:click, we want to display the document_no to the input field so we have it this way instead.
    public function show_documentsModal()
    {
        //NOTE - The following lines of codes are for displaying the supposed-to-be-document_no before it is saved.
        //LINK - app\Models\Incoming_Documents_CPSO_Model.php#31

        // Get the last document_no
        $lastDocumentNo = Incoming_Documents_CPSO_Model::orderBy('document_no', 'desc')->first();

        if ($lastDocumentNo) {
            $lastIdNumber = intval(substr($lastDocumentNo->document_no, 5));
            $newIdNumber = $lastIdNumber + 1;
        } else {
            $newIdNumber = Incoming_Documents_CPSO_Model::getStartingNumber();
        }

        $padLength = max(2, strlen((string)($newIdNumber)));
        $this->document_no = 'MEMO-' . str_pad($newIdNumber, $padLength, '0', STR_PAD_LEFT);

        $this->dispatch('enable-plugins');
        $this->dispatch('show-documentsModal');
    }

    //NOTE - file_size in KB convert to MB 
    public function convertSize($sizeInKB)
    {
        return round($sizeInKB / 1024, 2); // Convert KB to MB and round to 2 decimal places
    }
}
