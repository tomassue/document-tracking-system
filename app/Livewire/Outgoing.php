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

    /* ------- REUSABLE MODELS AND IF 'OTHERS' IS SELECTED IN THE CATEGORY ------ */
    public $outgoing_category;
    public $document_no;
    public $document_name; //NOTE - RIS and OTHERS category uses this.
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
                'date'                  => 'required',
                'document_details'      => 'required',
                'attachments'           => 'required',
                'PR_no'                 => 'required',
                'PO_no'                 => 'required'
            ];
        } elseif ($this->outgoing_category == 'payroll') {
            return [
                'date'                  => 'required',
                'document_details'      => 'required',
                'attachments'           => 'required',
                'payroll_type'          => 'required'
            ];
        } elseif ($this->outgoing_category == 'voucher') {
            return [
                'date'                  => 'required',
                'document_details'      => 'required',
                'attachments'           => 'required',
                'voucher_name'          => 'required'
            ];
        } elseif ($this->outgoing_category == 'ris') {
            return [
                'document_name'         => 'required',
                'date'                  => 'required',
                'document_details'      => 'required',
                'attachments'           => 'required',
                'ppmp_code'             => 'required'
            ];
        } elseif ($this->outgoing_category == 'other') {
            return [
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

                $this->dispatch('show-something-went-wrong-toast');
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

    public function loadOutgoingDocuments()
    {
        $outgoing_documents = OutgoingDocumentsModel::with('category')->get();

        return $outgoing_documents;
    }

    public function clear()
    {
        $this->reset();
        $this->resetValidation();
        $this->dispatch('clear_plugins');
    }
}
