<?php

namespace App\Livewire\Settings;

use App\Models\Ref_Venue_Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Venue | CPSO Management System')]
class Venues extends Component
{
    use WithPagination;

    public $search;
    public $editMode = false;
    public $venue_id;

    /* ------------------------------- wire models ------------------------------ */
    public $venue;
    public $is_active;
    /* ----------------------------- end wire models ---------------------------- */

    public function render()
    {
        $data = [
            'venues' => $this->loadVenues()
        ];

        return view('livewire.settings.venues', $data);
    }

    public function loadVenues()
    {
        $venues = Ref_Venue_Model::select(
            'id',
            'venue',
            'is_active'
        )
            ->when($this->search, function ($query) {
                $query->where('venue', 'like', '%' . $this->search . '%');
            })
            ->paginate(10);

        return $venues;
    }

    public function clear()
    {
        $this->reset();
        $this->resetValidation();
    }

    public function rules()
    {
        $rules = [
            'venue' => ['required', 'regex:/^[a-zA-Z\s]+$/u', Rule::unique('ref_venues', 'venue')->ignore($this->venue_id)], // Allows letters and spaces
        ];

        if ($this->editMode) {
            $rules['is_active'] = 'required';
        }

        return $rules;
    }

    public function messages()
    {
        $messages = [
            'venue.regex' => 'The venue must contain letters and spaces only.'
        ];

        return $messages;
    }

    public function add()
    {
        $this->validate();

        try {
            DB::beginTransaction();

            $venue = new Ref_Venue_Model();
            $venue->venue = $this->venue;
            $venue->is_active = 'yes';
            $venue->save();

            $this->dispatch('hide-venueModal');
            $this->clear();
            $this->dispatch('show-success-update-message-toast');

            DB::commit();
        } catch (\Exception) {
            DB::rollBack();

            $this->dispatch('show-something-went-wrong-toast');
        }
    }

    public function edit($id)
    {
        $this->editMode = true;

        $venue = Ref_Venue_Model::where('id', $id)->first();
        $this->venue_id = $id;
        $this->venue = $venue->venue;
        $this->dispatch('is_active_edit', $venue->is_active);

        $this->dispatch('show-venueModal');
    }

    public function update()
    {
        $this->validate();

        try {
            DB::beginTransaction();

            // Update the venue based on its ID
            Ref_Venue_Model::where('id', $this->venue_id)
                ->update([
                    'venue' => $this->venue,
                    'is_active' => $this->is_active
                ]);

            // Dispatch events to hide the modal and show success message
            $this->dispatch('hide-venueModal');
            $this->clear();
            $this->dispatch('show-success-update-message-toast');

            DB::commit();
        } catch (\Exception) {
            DB::rollBack();

            $this->dispatch('show-something-went-wrong-toast');
        }
    }
}
