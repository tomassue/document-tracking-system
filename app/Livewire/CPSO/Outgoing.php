<?php

namespace App\Livewire\CPSO;

use App\Models\Document_History_Model;
use App\Models\File_Data_Model;
use App\Models\OutgoingCategoryOthersModel;
use App\Models\OutgoingCategoryPayrollModel;
use App\Models\OutgoingCategoryProcurementModel;
use App\Models\OutgoingCategoryRISModel;
use App\Models\OutgoingCategoryVoucherModel;
use App\Models\OutgoingDocumentsModel;
use App\Models\Ref_Category_Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

#[Title('Outgoing | CPSO Management System')]
class Outgoing extends Component
{
    use WithPagination, WithFileUploads;

    public $search;
    public $editMode = false;
    public $hide_button_if_completed;
    public $notes; // For remarks or notes on every update.
    public $document_history = [];
    public $file_id, $file_title, $file_data;

    /* --------------------------------- FILTER --------------------------------- */
    public $filter_status;
    public $filter_category;
    /* ------------------------------- END FILTER ------------------------------- */

    /* ------- REUSABLE MODELS AND IF 'OTHERS' IS SELECTED IN THE CATEGORY ------ */
    public $outgoing_category;
    public $document_no;
    public $document_name; //NOTE - RIS and OTHERS category uses this.
    public $destination;
    public $person_responsible;
    public $date;
    public $status;
    public $document_details;
    public $attachments = [];
    /* ------- REUSABLE MODELS AND IF 'OTHERS' IS SELECTED IN THE CATEGORY ------ */

    /* ------------------------ PROCUREMENT MODAL MODELS ------------------------ */
    public $PR_no;
    public $PO_no;
    /* ------------------------ PROCUREMENT MODAL MODELS ------------------------ */

    /* -------------------------- PAYROLL MODAL MODELS -------------------------- */
    public $payroll_type;
    /* -------------------------- PAYROLL MODAL MODELS -------------------------- */

    /* -------------------------- VOUCHER MODAL MODELS -------------------------- */
    public $voucher_name;
    /* -------------------------- VOUCHER MODAL MODELS -------------------------- */

    /* ---------------------------- RIS MODAL MODELS ---------------------------- */
    public $ppmp_code;
    /* ---------------------------- RIS MODAL MODELS ---------------------------- */

    public function rules()
    {
        $commonRules = [
            'document_no' => 'required',
            'destination' => 'required',
            'person_responsible' => 'required',
            'date' => 'required',
            // 'document_details' => 'required',
            // 'attachments' => 'required',
        ];

        $categorySpecificRules = match ($this->outgoing_category) {
            '4' => [
                'PR_no' => 'required',
                'PO_no' => 'required',
            ],
            '5' => [
                'payroll_type' => 'required',
            ],
            '6' => [
                'voucher_name' => 'required',
            ],
            '7' => [
                'document_name' => 'required',
                'ppmp_code' => 'required',
            ],
            '8' => [
                'document_name' => 'required',
            ],
            default => [],
        };

        return array_merge($commonRules, $categorySpecificRules);
    }

    //FIXME - NOT SHOWING INDICATED ATTRIBUTES
    public function attributes()
    {
        return [
            'person_responsible' => 'Person Responsible',
            'date' => 'Date',
            'document_details' => 'Document Details',
            // 'attachments' => 'Attachments',
            'PR_no' => 'Purchase Request Number',
            'PO_no' => 'Purchase Order Number',
            'payroll_type' => 'Payroll Type',
            'voucher_name' => 'Voucher Name',
            'document_name' => 'Document Name',
            'ppmp_code' => 'PPMP Code',
        ];
    }

    public function render()
    {
        $data = [
            'outgoing_documents' => $this->loadOutgoingDocuments(),
            'categories' => $this->loadCategories()
        ];

        return view('livewire.CPSO.outgoing', $data);
    }

    /**
     * NOTE
     * To make sure that either all data is saved successfully or none of it is saved, we can wrap the code in a database transaction. 
     * This way, if something fails during the process, the transaction will be rolled back, and no partial data will be saved.
     */

    public function add()
    {
        $this->validate($this->rules(), [], $this->attributes()); //manually calling validation, ensure that you are referencing attributes() in the validate() method

        if ($this->outgoing_category == '4') {
            try {
                DB::beginTransaction();

                $file_data_IDs = [];

                // Save attachments if there are any
                foreach ($this->attachments ?? [] as $file) {
                    $file_data = File_Data_Model::create([
                        'file_name' => $file->getClientOriginalName(),
                        'file_size' => $file->getSize(),
                        'file_type' => $file->extension(),
                        'file'      => file_get_contents($file->path()),
                        'user_id'   => Auth::user()->id
                    ]);
                    // Store the ID of the saved file
                    $file_data_IDs[] = $file_data->id;
                }

                // Save outgoing procurement data
                $outgoing_category_procurement = OutgoingCategoryProcurementModel::create([
                    'pr_no' => $this->PR_no,
                    'po_no' => $this->PO_no
                ]);

                // Save outgoing documents
                $outgoing_documents = new OutgoingDocumentsModel([
                    'document_no' => $this->document_no,
                    'date' => $this->date,
                    'document_details' => $this->document_details,
                    'destination' => $this->destination,
                    'person_responsible' => $this->person_responsible,
                    'attachments' => json_encode($file_data_IDs ?? []) // if empty, an empty array will be stored.
                ]);

                // Save (Polymorphic Relations)
                $outgoing_category_procurement->outgoing_documents()->save($outgoing_documents);

                // Save document history
                Document_History_Model::create([
                    'document_id' => $outgoing_documents->document_no,
                    'status' => 'processing',
                    'user_id' => Auth::user()->id,
                    'remarks' => 'created_by'
                ]);

                // Commit the transaction if all is successful
                DB::commit();

                $this->clear();
                $this->dispatch('hide-outgoingModal');
                $this->dispatch('show-success-save-message-toast');
            } catch (\Exception $e) {
                // Rollback the transaction on failure
                DB::rollBack();

                // $this->dispatch('show-something-went-wrong-toast');
                dd($e->getMessage());
            }
        } elseif ($this->outgoing_category == '5') {
            try {
                DB::beginTransaction();

                // Save attachments
                foreach ($this->attachments ?? [] as $file) {
                    $file_data = File_Data_Model::create([
                        'file_name' => $file->getClientOriginalName(),
                        'file_size' => $file->getSize(),
                        'file_type' => $file->extension(),
                        'file'      => file_get_contents($file->path()),
                        'user_id'   => Auth::user()->id
                    ]);
                    // Store the ID of the saved file
                    $file_data_IDs[] = $file_data->id;
                }

                // Save outgoing payroll
                $outgoing_category_payroll = OutgoingCategoryPayrollModel::create([
                    'payroll_type' => $this->payroll_type
                ]);

                // Save outgoing documents
                $outgoing_documents = new OutgoingDocumentsModel([
                    'document_no' => $this->document_no,
                    'date' => $this->date,
                    'document_details' => $this->document_details,
                    'destination' => $this->destination,
                    'person_responsible' => $this->person_responsible,
                    'attachments' => json_encode($file_data_IDs ?? [])
                ]);

                // Save (Polymorphic Relations)
                $outgoing_category_payroll->outgoing_documents()->save($outgoing_documents);

                // Save document history
                Document_History_Model::create([
                    'document_id' => $outgoing_documents->document_no,
                    'status' => 'processing',
                    'user_id' => Auth::user()->id,
                    'remarks' => 'created_by'
                ]);

                // Commit the transaction if all is successful
                DB::commit();

                $this->clear();
                $this->dispatch('hide-outgoingModal');
                $this->dispatch('show-success-save-message-toast');
            } catch (\Exception $e) {
                // Rollback the transaction on failure
                DB::rollBack();

                $this->dispatch('show-something-went-wrong-toast');
            }
        } elseif ($this->outgoing_category == '6') {
            try {
                DB::beginTransaction();

                // Save attachments
                foreach ($this->attachments ?? [] as $file) {
                    $file_data = File_Data_Model::create([
                        'file_name' => $file->getClientOriginalName(),
                        'file_size' => $file->getSize(),
                        'file_type' => $file->extension(),
                        'file'      => file_get_contents($file->path()),
                        'user_id'   => Auth::user()->id
                    ]);
                    // Store the ID of the saved file
                    $file_data_IDs[] = $file_data->id;
                }

                // Save outgoing voucher
                $outgoing_category_voucher = OutgoingCategoryVoucherModel::create([
                    'voucher_name' => $this->voucher_name
                ]);

                // Save outgoing documents
                $outgoing_documents = new OutgoingDocumentsModel([
                    'document_no' => $this->document_no,
                    'date' => $this->date,
                    'document_details' => $this->document_details,
                    'destination' => $this->destination,
                    'person_responsible' => $this->person_responsible,
                    'attachments' => json_encode($file_data_IDs ?? [])
                ]);

                $outgoing_category_voucher->outgoing_documents()->save($outgoing_documents);

                // Save document history
                Document_History_Model::create([
                    'document_id' => $outgoing_documents->document_no,
                    'status' => 'processing',
                    'user_id' => Auth::user()->id,
                    'remarks' => 'created_by'
                ]);

                // Commit the transaction if all is successful
                DB::commit();

                $this->clear();
                $this->dispatch('hide-outgoingModal');
                $this->dispatch('show-success-save-message-toast');
            } catch (\Exception $e) {
                // Rollback the transaction on failure
                DB::rollBack();

                $this->dispatch('show-something-went-wrong-toast');
            }
        } elseif ($this->outgoing_category == '7') {
            try {
                DB::beginTransaction();

                // Save attachments
                foreach ($this->attachments ?? [] as $file) {
                    $file_data = File_Data_Model::create([
                        'file_name' => $file->getClientOriginalName(),
                        'file_size' => $file->getSize(),
                        'file_type' => $file->extension(),
                        'file'      => file_get_contents($file->path()),
                        'user_id'   => Auth::user()->id
                    ]);
                    // Store the ID of the saved file
                    $file_data_IDs[] = $file_data->id;
                }

                // Save outgoing RIS
                $outgoing_category_ris = OutgoingCategoryRISModel::create([
                    'document_name' => $this->document_name,
                    'ppmp_code' => $this->ppmp_code
                ]);

                // Save outgoing documents
                $outgoing_documents = new OutgoingDocumentsModel([
                    'document_no' => $this->document_no,
                    'date' => $this->date,
                    'document_details' => $this->document_details,
                    'destination' => $this->destination,
                    'person_responsible' => $this->person_responsible,
                    'attachments' => json_encode($file_data_IDs ?? [])
                ]);

                $outgoing_category_ris->outgoing_documents()->save($outgoing_documents);

                // Save document history
                Document_History_Model::create([
                    'document_id' => $outgoing_documents->document_no,
                    'status' => 'processing',
                    'user_id' => Auth::user()->id,
                    'remarks' => 'created_by'
                ]);

                // Commit the transaction if all is successful
                DB::commit();

                $this->clear();
                $this->dispatch('hide-outgoingModal');
                $this->dispatch('show-success-save-message-toast');
            } catch (\Exception $e) {
                // Rollback the transaction on failure
                DB::rollBack();

                $this->dispatch('show-something-went-wrong-toast');
            }
        } elseif ($this->outgoing_category == '8') {
            try {
                DB::beginTransaction();

                // Save attachments
                foreach ($this->attachments ?? [] as $file) {
                    $file_data = File_Data_Model::create([
                        'file_name' => $file->getClientOriginalName(),
                        'file_size' => $file->getSize(),
                        'file_type' => $file->extension(),
                        'file'      => file_get_contents($file->path()),
                        'user_id'   => Auth::user()->id
                    ]);
                    // Store the ID of the saved file
                    $file_data_IDs[] = $file_data->id;
                }

                // Save outgoing others
                $outgoing_category_others = OutgoingCategoryOthersModel::create([
                    'document_name' => $this->document_name
                ]);

                // Save outgoing documents
                $outgoing_documents = new OutgoingDocumentsModel([
                    'document_no' => $this->document_no,
                    'date' => $this->date,
                    'document_details' => $this->document_details,
                    'destination' => $this->destination,
                    'person_responsible' => $this->person_responsible,
                    'attachments' => json_encode($file_data_IDs ?? [])
                ]);

                $outgoing_category_others->outgoing_documents()->save($outgoing_documents);

                // Save document history
                Document_History_Model::create([
                    'document_id' => $outgoing_documents->document_no,
                    'status' => 'processing',
                    'user_id' => Auth::user()->id,
                    'remarks' => 'created_by'
                ]);

                // Commit the transaction if all is successful
                DB::commit();

                $this->clear();
                $this->dispatch('hide-outgoingModal');
                $this->dispatch('show-success-save-message-toast');
            } catch (\Exception $e) {
                // Rollback the transaction on failure
                DB::rollBack();

                $this->dispatch('show-something-went-wrong-toast');
            }
        } else {
            try {
                DB::beginTransaction();

                // Save attachments
                foreach ($this->attachments ?? [] as $file) {
                    $file_data = File_Data_Model::create([
                        'file_name' => $file->getClientOriginalName(),
                        'file_size' => $file->getSize(),
                        'file_type' => $file->extension(),
                        'file'      => file_get_contents($file->path()),
                        'user_id'   => Auth::user()->id
                    ]);
                    // Store the ID of the saved file
                    $file_data_IDs[] = $file_data->id;
                }

                // Save outgoing others
                // $outgoing_category_others = OutgoingCategoryOthersModel::create([
                //     'document_name' => $this->document_name
                // ]);

                // Save outgoing documents
                $outgoing_documents = new OutgoingDocumentsModel([
                    'document_no' => $this->document_no,
                    'date' => $this->date,
                    'document_details' => $this->document_details,
                    'destination' => $this->destination,
                    'person_responsible' => $this->person_responsible,
                    'attachments' => json_encode($file_data_IDs ?? [])
                ]);

                // $outgoing_category_others->outgoing_documents()->save($outgoing_documents);

                // Save document history
                Document_History_Model::create([
                    'document_id' => $outgoing_documents->document_no,
                    'status' => 'processing',
                    'user_id' => Auth::user()->id,
                    'remarks' => 'created_by'
                ]);

                // Commit the transaction if all is successful
                DB::commit();

                $this->clear();
                $this->dispatch('hide-outgoingModal');
                $this->dispatch('show-success-save-message-toast');
            } catch (\Exception $e) {
                // Rollback the transaction on failure
                DB::rollBack();

                dd($e->getMessage());
                // $this->dispatch('show-something-went-wrong-toast');
            }
        }
    }

    public function edit($key)
    {
        $this->editMode = true;

        $outgoing_category = OutgoingDocumentsModel::where('document_no', $key)->first();
        $document_history  = Document_History_Model::where('document_id', $key)->latest()->first();

        $this->person_responsible   = $outgoing_category->person_responsible;
        $this->document_no          = $outgoing_category->document_no;
        $this->dispatch('set-date', $outgoing_category->date);

        if ($document_history->status == 'completed') {
            $this->dispatch('set-outgoing-status-select-disable', $document_history->status);
            $this->hide_button_if_completed = true;
            $this->dispatch('set-notes');
        } else {
            $this->dispatch('set-outgoing-status-select-enable', $document_history->status);
            $this->dispatch('set_notes-enabled');
        }

        $this->dispatch('set-document_details', $outgoing_category->document_details);
        $this->destination          = $outgoing_category->destination;

        if ($outgoing_category->category_type == "App\Models\OutgoingCategoryProcurementModel") {
            $this->dispatch('set-outgoing-category-select', '4');
            $this->PR_no = $outgoing_category->category->pr_no;
            $this->PO_no = $outgoing_category->category->po_no;
        } elseif ($outgoing_category->category_type == "App\Models\OutgoingCategoryPayrollModel") {
            $this->dispatch('set-outgoing-category-select', '5');
            $this->dispatch('set_payroll_type_select', $outgoing_category->category->payroll_type);
        } elseif ($outgoing_category->category_type == "App\Models\OutgoingCategoryVoucherModel") {
            $this->dispatch('set-outgoing-category-select', '6');
            $this->voucher_name = $outgoing_category->category->voucher_name;
        } elseif ($outgoing_category->category_type == "App\Models\OutgoingCategoryRISModel") {
            $this->dispatch('set-outgoing-category-select', '7');
            $this->document_name = $outgoing_category->category->document_name;
            $this->ppmp_code = $outgoing_category->category->ppmp_code;
        } elseif ($outgoing_category->category_type == "App\Models\OutgoingCategoryOthersModel") {
            $this->dispatch('set-outgoing-category-select', '8');
            $this->document_name = $outgoing_category->category->document_name;
        }

        foreach (json_decode($outgoing_category->attachments) as $item) {
            $file = File_Data_Model::where('id', $item)
                ->select(
                    'id',
                    'file_name',
                )
                ->first();
            $file->file_size = $this->convertSize($file->file_size);
            $this->attachments[] = $file;
        }

        $this->dispatch('show-outgoingModal');
    }

    public function update()
    {
        //NOTE - For now, we will update the status only and record the action in our document_history

        $this->validate([
            'status' => 'required'
        ]);

        try {
            DB::beginTransaction();

            Document_History_Model::create([
                'document_id' => $this->document_no,
                'status' => $this->status,
                'user_id' => Auth::user()->id,
                'remarks' => 'updated_by',
                'notes' => $this->notes
            ]);

            $this->clear();
            $this->dispatch('hide-outgoingModal');
            $this->dispatch('show-success-update-message-toast');

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('show-something-went-wrong-toast');
        }
    }

    // Closing attachment preview
    public function clearFileData()
    {
        $this->reset('file_id', 'file_data');
    }

    public function previewAttachment($key)
    {
        if ($key) {
            $file = File_Data_Model::findOrFail($key);

            $this->file_id = $key;

            if ($file && $file->file) {
                $this->file_title = $file->file_name;
                $this->file_data = base64_encode($file->file);
            }
        }
    }

    public function loadOutgoingDocuments()
    {
        $outgoing_documents = OutgoingDocumentsModel::with('category')
            ->join(DB::raw('(SELECT id, document_id, status, user_id
                    FROM document_history
                    WHERE id IN (
                        SELECT MAX(id)
                        FROM document_history
                        GROUP BY document_id
                    )) AS latest_document_history'), 'outgoing_documents.document_no', '=', 'latest_document_history.document_id')
            ->join('users', 'users.id', '=', 'latest_document_history.user_id')
            ->select('outgoing_documents.*', 'users.name as user_name', 'latest_document_history.status', DB::raw("DATE_FORMAT(date, '%b %d, %Y') AS date"))
            ->orderBy('outgoing_documents.date', 'desc')
            // ->where('document_details', 'like', '%' . $this->search . '%')
            ->where(function ($query) {
                $query->where('document_details', 'like', '%' . $this->search . '%')
                    ->orWhere('document_no', 'like', '%' . $this->search . '%');
            })
            ->when($this->filter_status != NULL, function ($query) {
                $query->where('latest_document_history.status', $this->filter_status);
            }, function ($query) {
                $query->whereNot('latest_document_history.status', 'completed');
            })
            ->when($this->filter_category != null, function ($query) {
                $categoryMap = [
                    'procurement' => 'App\Models\OutgoingCategoryProcurementModel',
                    'payroll' => 'App\Models\OutgoingCategoryPayrollModel',
                    'voucher' => 'App\Models\OutgoingCategoryVoucherModel',
                    'ris' => 'App\Models\OutgoingCategoryRISModel',
                    'other' => 'App\Models\OutgoingCategoryOthersModel'
                ];

                if (isset($categoryMap[$this->filter_category])) {
                    $query->whereHas('category', function ($categoryQuery) use ($categoryMap) {
                        $categoryQuery->where('category_type', $categoryMap[$this->filter_category]);
                    });
                }
            })
            ->paginate(10);

        return $outgoing_documents;
    }

    public function loadCategories()
    {
        // Outgoing Categories
        $categories = Ref_Category_Model::join('user_offices', 'user_offices.user_id', '=', 'ref_category.created_by')
            ->where('user_offices.office_id', Auth::user()->ref_office->id)
            ->select(
                'ref_category.id',
                'ref_category.category',
                'ref_category.document_type',
                'ref_category.is_active'
            )
            ->where('document_type', 'outgoing')
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

    public function history($key)
    {
        $this->document_history = []; //NOTE - Set this to empty to avoid data to stack.

        $document_history = Document_History_Model::join('users', 'users.id', '=', 'document_history.user_id')
            ->where('document_history.document_id', $key)
            ->select(
                DB::raw("DATE_FORMAT(document_history.created_at, '%b %d, %Y %h:%i%p') AS history_date_time"),
                'document_history.status',
                DB::raw("
                    CASE
                        WHEN document_history.remarks = 'created_by' THEN 'Created by'
                        WHEN document_history.remarks = 'updated_by' THEN 'Updated by'
                        ELSE 'Unknown'
                    END AS remarks
                "),
                DB::raw("
                    CASE
                        WHEN document_history.notes IS NULL THEN ''
                        ELSE document_history.notes
                    END AS notes
                "),
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
        $this->resetExcept('filter_category', 'filter_status');
        $this->resetValidation();
        $this->dispatch('clear_plugins');
    }

    // NOTE - upon opening the modal, the next document_no will be assigned to the property $document_no.
    public function show_outgoingModal()
    {
        $this->dispatch('enable-plugins'); //NOTE - enables the plugins again after editMode since we disable them during editMode.

        // Get the last document_no
        // $lastDocumentNo = OutgoingDocumentsModel::orderBy('document_no', 'desc')->first();

        // if ($lastDocumentNo) {
        //     $lastIdNumber = intval(substr($lastDocumentNo->document_no, 9));
        //     $newIdNumber = $lastIdNumber + 1;
        // } else {
        //     $newIdNumber = OutgoingDocumentsModel::getStartingNumber();
        // }

        // $padLength = max(2, strlen((string)($newIdNumber)));
        // $this->document_no = 'DOCUMENT-' . str_pad($newIdNumber, $padLength, '0', STR_PAD_LEFT);

        $this->dispatch('show-outgoingModal');
    }

    //NOTE - file_size in KB convert to MB 
    public function convertSize($sizeInKB)
    {
        return round($sizeInKB / 1024, 2); // Convert KB to MB and round to 2 decimal places
    }
}
