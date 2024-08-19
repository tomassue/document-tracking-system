<?php

namespace App\Livewire;

use App\Models\Document_History_Model;
use App\Models\File_Data_Model;
use App\Models\OutgoingCategoryOthersModel;
use App\Models\OutgoingCategoryPayrollModel;
use App\Models\OutgoingCategoryProcurementModel;
use App\Models\OutgoingCategoryRISModel;
use App\Models\OutgoingCategoryVoucherModel;
use App\Models\OutgoingDocumentsModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Outgoing extends Component
{
    use WithPagination, WithFileUploads;

    public $search;
    public $editMode = false;
    public $document_history = [];

    /* ------- REUSABLE MODELS AND IF 'OTHERS' IS SELECTED IN THE CATEGORY ------ */
    public $outgoing_category;
    public $document_no;
    public $document_name; //NOTE - RIS and OTHERS category uses this.
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
        if ($this->outgoing_category == 'procurement') {
            return [
                'person_responsible'    => 'required',
                'date'                  => 'required',
                'document_details'      => 'required',
                'attachments'           => 'required',
                'PR_no'                 => 'required',
                'PO_no'                 => 'required'
            ];
        } elseif ($this->outgoing_category == 'payroll') {
            return [
                'person_responsible'    => 'required',
                'date'                  => 'required',
                'document_details'      => 'required',
                'attachments'           => 'required',
                'payroll_type'          => 'required'
            ];
        } elseif ($this->outgoing_category == 'voucher') {
            return [
                'person_responsible'    => 'required',
                'date'                  => 'required',
                'document_details'      => 'required',
                'attachments'           => 'required',
                'voucher_name'          => 'required'
            ];
        } elseif ($this->outgoing_category == 'ris') {
            return [
                'person_responsible'    => 'required',
                'document_name'         => 'required',
                'date'                  => 'required',
                'document_details'      => 'required',
                'attachments'           => 'required',
                'ppmp_code'             => 'required'
            ];
        } elseif ($this->outgoing_category == 'other') {
            return [
                'person_responsible'    => 'required',
                'document_name'         => 'required',
                'date'                  => 'required',
                'document_details'      => 'required',
                'attachments'           => 'required',
            ];
        }
    }

    public function render()
    {
        $data = [
            'outgoing_documents' => $this->loadOutgoingDocuments()
        ];

        return view('livewire.outgoing', $data);
    }

    /**
     * NOTE
     * To make sure that either all data is saved successfully or none of it is saved, we can wrap the code in a database transaction. 
     * This way, if something fails during the process, the transaction will be rolled back, and no partial data will be saved.
     */

    public function add()
    {
        if ($this->outgoing_category == 'procurement') {
            $this->validate();

            DB::beginTransaction();

            try {
                $file_data_IDs = [];

                // Save attachments
                foreach ($this->attachments as $file) {
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
                    'date' => $this->date,
                    'document_details' => $this->document_details,
                    'person_responsible' => $this->person_responsible,
                    'attachments' => json_encode($file_data_IDs)
                ]);

                // Save (Polymorphic Relations)
                $outgoing_category_procurement->outgoing_documents()->save($outgoing_documents);

                // Save document history
                Document_History_Model::create([
                    'document_id' => $outgoing_documents->document_no,
                    'status' => 'pending',
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
        } elseif ($this->outgoing_category == 'payroll') {
            $this->validate();

            DB::beginTransaction();

            try {
                // Save attachments
                foreach ($this->attachments as $file) {
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
                    'date' => $this->date,
                    'document_details' => $this->document_details,
                    'person_responsible' => $this->person_responsible,
                    'attachments' => json_encode($file_data_IDs)
                ]);

                // Save (Polymorphic Relations)
                $outgoing_category_payroll->outgoing_documents()->save($outgoing_documents);

                // Save document history
                Document_History_Model::create([
                    'document_id' => $outgoing_documents->document_no,
                    'status' => 'pending',
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
        } elseif ($this->outgoing_category == 'voucher') {
            $this->validate();

            DB::beginTransaction();

            try {
                // Save attachments
                foreach ($this->attachments as $file) {
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
                    'date' => $this->date,
                    'document_details' => $this->document_details,
                    'person_responsible' => $this->person_responsible,
                    'attachments' => json_encode($file_data_IDs)
                ]);

                $outgoing_category_voucher->outgoing_documents()->save($outgoing_documents);

                // Save document history
                Document_History_Model::create([
                    'document_id' => $outgoing_documents->document_no,
                    'status' => 'pending',
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
        } elseif ($this->outgoing_category == 'ris') {
            $this->validate();

            DB::beginTransaction();

            try {
                // Save attachments
                foreach ($this->attachments as $file) {
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
                    'date' => $this->date,
                    'document_details' => $this->document_details,
                    'person_responsible' => $this->person_responsible,
                    'attachments' => json_encode($file_data_IDs)
                ]);

                $outgoing_category_ris->outgoing_documents()->save($outgoing_documents);

                // Save document history
                Document_History_Model::create([
                    'document_id' => $outgoing_documents->document_no,
                    'status' => 'pending',
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
        } elseif ($this->outgoing_category == 'other') {
            $this->validate();

            DB::beginTransaction();

            try {
                // Save attachments
                foreach ($this->attachments as $file) {
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
                    'date' => $this->date,
                    'document_details' => $this->document_details,
                    'person_responsible' => $this->person_responsible,
                    'attachments' => json_encode($file_data_IDs)
                ]);

                $outgoing_category_others->outgoing_documents()->save($outgoing_documents);

                // Save document history
                Document_History_Model::create([
                    'document_id' => $outgoing_documents->document_no,
                    'status' => 'pending',
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
        }
    }

    public function edit($key)
    {
        $this->editMode = true;

        $outgoing_category = OutgoingDocumentsModel::where('document_no', $key)->first();

        if ($outgoing_category->category_type == "App\Models\OutgoingCategoryProcurementModel") {
            $this->dispatch('set-outgoing-category-select', 'procurement');
            $this->person_responsible   = $outgoing_category->person_responsible;
            $this->document_no          = $outgoing_category->document_no;
            $this->dispatch('set-date', $outgoing_category->date);

            $this->dispatch('show-outgoingModal');
        } elseif ($outgoing_category->category_type == "App\Models\OutgoingCategoryPayrollModel") {
            dd('payroll');
        } elseif ($outgoing_category->category_type == "App\Models\OutgoingCategoryVoucherModel") {
            dd('voucher');
        } elseif ($outgoing_category->category_type == "App\Models\OutgoingCategoryRISModel") {
            dd('ris');
        } elseif ($outgoing_category->category_type == "App\Models\OutgoingCategoryOthersModel") {
            dd('others');
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
            ->select('outgoing_documents.*', 'users.name as user_name', 'latest_document_history.status')
            ->get();

        return $outgoing_documents;
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
        $this->dispatch('clear_plugins');
    }

    // NOTE - upon opening the modal, the next document_no will be assigned to the property $document_no.
    public function show_outgoingModal()
    {
        // Get the last document_no
        $lastDocumentNo = OutgoingDocumentsModel::orderBy('document_no', 'desc')->first();

        if ($lastDocumentNo) {
            $lastIdNumber = intval(substr($lastDocumentNo->document_no, 9));
            $newIdNumber = $lastIdNumber + 1;
        } else {
            $newIdNumber = OutgoingDocumentsModel::getStartingNumber();
        }

        $padLength = max(2, strlen((string)($newIdNumber)));
        $this->document_no = 'DOCUMENT-' . str_pad($newIdNumber, $padLength, '0', STR_PAD_LEFT);

        $this->dispatch('show-outgoingModal');
    }
}
