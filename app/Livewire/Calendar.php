<?php

namespace App\Livewire;

use App\Models\Incoming_Request_CPSO_Model;
use Livewire\Component;

class Calendar extends Component
{
    public $venue; //NOTE - Filter select

    public function render()
    {
        $data = [
            'incoming_request' => $this->loadIncomingRequest()
        ];

        return view('livewire.calendar', $data);
    }

    public function updated($property)
    {
        if ($property == 'venue') {
            $this->updateCalendar();
        }
    }

    public function updateCalendar()
    {
        $this->dispatch('filter-calendar', $this->loadIncomingRequest());
    }

    public function loadIncomingRequest()
    {
        $incoming_request = Incoming_Request_CPSO_Model::join('ref_category', 'ref_category.id', '=', 'incoming_request_cpso.incoming_category')
            ->select(
                'incoming_request_cpso.incoming_request_id',
                'ref_category.category AS ref_category',
                'incoming_request_cpso.office_or_barangay_or_organization',
                'incoming_request_cpso.request_date',
                'incoming_request_cpso.category',
                'incoming_request_cpso.venue',
                'incoming_request_cpso.start_time',
                'incoming_request_cpso.end_time',
                'incoming_request_cpso.description',
                'incoming_request_cpso.files',
                'incoming_request_cpso.created_at'
            )
            ->whereNotNull('venue')
            ->where('venue', 'like', "%{$this->venue}%")
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->incoming_request_id,
                    'title' => $item->office_or_barangay_or_organization,
                    'start' => $item->request_date . 'T' . $item->start_time,
                    'end' => $item->request_date . 'T' . $item->end_time,
                    'allDay' => false
                ];
            });

        return $incoming_request;
    }
}
