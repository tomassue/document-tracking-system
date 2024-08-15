<?php

namespace App\Livewire;

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
    public $document_name;
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

    public function render()
    {
        return view('livewire.outgoing');
    }

    public function add()
    {
        dd($this);
    }

    public function clear()
    {
        $this->reset();
        $this->resetValidation();
        $this->dispatch('clear_plugins');
    }
}
