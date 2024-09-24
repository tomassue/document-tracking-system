<?php

namespace App\Livewire\Settings;

use App\Models\Ref_Category_Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class Category extends Component
{
    public $editMode = false;
    public $id_category;

    /* -------------------------------------------------------------------------- */

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
            'category' => [
                'required',
                // This rule validates if there are duplicates in category AND document_type
                Rule::unique('ref_category', 'category')
                    ->where(function ($query) {
                        return $query->where('document_type', $this->document_type);
                    })
                    ->ignore($this->id_category, 'id') // Ignore the current record's ID when updating
            ],
            'document_type' => 'required', // Just ensure it's required, the unique check happens in category
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

    public function edit($id)
    {
        try {
            $category = Ref_Category_Model::findOrFail($id);
            $this->id_category = $id;
            $this->category = $category->category;
            $this->dispatch('set_document_type', $category->document_type);
            $this->dispatch('set_is_active', $category->is_active);

            $this->editMode = true;

            $this->dispatch('show-categoryModal');
        } catch (\Exception $e) {
            $this->dispatch('show-something-went-wrong-toast');
        }
    }

    public function update()
    {
        $this->validate();

        try {
            DB::beginTransaction();

            Ref_Category_Model::findOrFail($this->id_category)
                ->update([
                    'category' => $this->category,
                    'document_type' => $this->document_type,
                    'is_active' => $this->is_active
                ]);

            DB::commit();

            $this->dispatch('hide-categoryModal');
            $this->dispatch('show-success-update-message-toast');
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
