<?php

namespace App\Livewire\Settings;

use App\Models\Ref_Category_Model;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Category extends Component
{
    public $editMode = false;
    public $search;
    public $category;
    public $document_type;
    public $is_active;


    use WithPagination;

    public function render()
    {
        $data = [
            'categories' => $this->loadCategories()
        ];

        return view('livewire.settings.category', $data);
    }

    public function rules()
    {
        return [
            'category' => 'required',
            'document_type' => 'required',
            'is_active' => 'required'
        ];
    }

    public function add()
    {
        $this->validate();

        try {
            DB::beginTransaction();

            Ref_Category_Model::create([
                'category' => $this->category,
                'document_type' => $this->document_type,
                'is_active' => $this->is_active
            ]);

            DB::commit();

            $this->dispatch('hide-categoryModal');
            $this->dispatch('show-success-save-message-toast');
            $this->dispatch('clear-plugins');
            $this->clear();
        } catch (\Exception $e) {
            DB::rollBack();

            $this->dispatch('show-something-went-wrong-toast');
        }
    }

    public function loadCategories()
    {
        $categories = Ref_Category_Model::select(
            'id',
            'category',
            'document_type',
            'is_active'
        )
            ->get();

        return $categories;
    }

    public function clear()
    {
        $this->reset();
        $this->resetValidation();
    }
}
