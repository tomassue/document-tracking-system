<?php

namespace App\Livewire\CPSO;

use App\Models\Document_History_Model;
use App\Models\File_Data_Model;
use App\Models\Incoming_Request_CPSO_Model;
use App\Models\Ref_Venue_Model;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Illuminate\Support\Facades\URL;

#[Title('Calendar | CPSO Management System')]
class Calendar extends Component
{
    public $venue; //NOTE - Filter select
    /* -------------------------------------------------------------------------- */
    public $editMode = false;
    /* -------------------------------------------------------------------------- */
    public $incoming_request_id;
    public $incoming_request_category;
    public $status;
    public $office_or_barangay_or_organization;
    public $request_date;
    public $return_date;
    public $category;
    public $incoming_request_venue;
    public $start_time;
    public $end_time;
    public $description;
    public $files = [];
    public $file_title;
    public $file_data;
    /* ---------------------------------- PRINT --------------------------------- */
    public $p_venue;
    public $p_date;
    /* -------------------------------- PRINT PDF ------------------------------- */


    public function render()
    {
        $data = [
            'incoming_request' => $this->loadIncomingRequest(),
            'filter_venues' => $this->loadVenues(),
            'signedUrl' => $this->generateSignedUrl()
        ];

        return view('livewire.CPSO.calendar', $data);
    }

    public function clear()
    {
        $this->reset();
    }

    public function updated($property)
    {
        if ($property == 'venue') {
            $this->updateCalendar();
        }
    }

    #[On('show-details')]
    public function showDetails($key)
    {
        $this->editMode = true;

        $query = Incoming_Request_CPSO_Model::join('ref_venues', 'ref_venues.id', '=', 'incoming_request_cpso.venue')
            ->select(
                'incoming_request_cpso.incoming_category',
                'incoming_request_cpso.office_or_barangay_or_organization',
                'incoming_request_cpso.request_date',
                'incoming_request_cpso.return_date',
                'incoming_request_cpso.category',
                'ref_venues.venue',
                'incoming_request_cpso.start_time',
                'incoming_request_cpso.end_time',
                'incoming_request_cpso.description',
                'incoming_request_cpso.files'
            )
            ->where('incoming_request_cpso.incoming_request_id', $key)
            ->first();

        $document_history = Document_History_Model::where('document_id', $key)->latest()->first();

        $this->incoming_request_id                  = $query->incoming_request_id;
        $this->incoming_request_category            = $query->incoming_category;
        $this->status                               = $document_history->status;
        $this->office_or_barangay_or_organization   = $query->office_or_barangay_or_organization;
        $this->request_date                         = (new \DateTime($query->request_date))->format('M d, Y');
        $this->return_date                          = (new \DateTime($query->return_date))->format('M d, Y');
        $this->category                             = $query->category;
        $this->incoming_request_venue               = $query->venue;
        $this->start_time                           = (new \DateTime($query->start_time))->format('g:i A');
        $this->end_time                             = (new \DateTime($query->end_time))->format('g:i A');
        $this->description                          = $query->description;

        foreach (json_decode($query->files) as $item) {
            $file = File_Data_Model::where('id', $item)
                ->select(
                    'id',
                    'file_name',
                )
                ->first();
            $file->file_size = $this->convertSize($file->file_size);
            $this->files[] = $file;
        }

        $this->dispatch('show-viewDetailsModal');
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

    public function updateCalendar()
    {
        $this->dispatch('filter-calendar', $this->loadIncomingRequest());
    }

    public function generateSignedUrl()
    {
        if ($this->p_venue && $this->p_date) {
            $signedUrl = URL::temporarySignedRoute(
                'venue.schedule.pdf',
                now()->addMinutes(5), // Set expiration time in seconds
                ['venue' => $this->p_venue, 'date' => $this->p_date]
            );

            return $signedUrl;
        }

        return null; // Return null if venue or date is not set
    }

    public function print()
    {
        // $venues = Incoming_Request_CPSO_Model::where('venue', $this->p_venue)
        //     ->where('request_date', $this->p_date)
        //     ->get();

        // $data = [
        //     'venues' => $venues
        // ];

        // $pdf = Pdf::loadView('livewire.CPSO.pdf.venue-schedule', $data);

        // return response()->streamDownload(function () use ($pdf) {
        //     echo $pdf->stream();
        // }, 'name.pdf');

        // Redirect to a route that generates the PDF
        return redirect()->route('generate.pdf', [
            'venue' => $this->p_venue,
            'date' => $this->p_date
        ]);
    }

    public function loadVenues()
    {
        // Filter select
        $venues = Ref_Venue_Model::all()
            ->map(function ($item) {
                return [
                    'label' => $item->venue,
                    'value' => $item->id
                ];
            });

        return $venues;
    }

    public function loadIncomingRequest()
    {
        $incoming_request = Incoming_Request_CPSO_Model::join(DB::raw('(SELECT document_id, status
            FROM document_history
            WHERE id IN (
                SELECT MAX(id)
                FROM document_history
                GROUP BY document_id
            )) AS latest_document_history'), 'latest_document_history.document_id', '=', 'incoming_request_cpso.incoming_request_id')
            ->join('ref_venues', 'ref_venues.id', '=', 'incoming_request_cpso.venue')
            ->select(
                'incoming_request_cpso.incoming_request_id',
                'incoming_request_cpso.office_or_barangay_or_organization',
                'incoming_request_cpso.request_date',
                'incoming_request_cpso.return_date',
                'incoming_request_cpso.category',
                'ref_venues.venue',
                'incoming_request_cpso.start_time',
                'incoming_request_cpso.end_time',
                'incoming_request_cpso.description',
                'incoming_request_cpso.files',
                'incoming_request_cpso.created_at',
                'latest_document_history.status'
            )
            ->where('incoming_request_cpso.venue', '!=', '')
            ->where('incoming_request_cpso.venue', 'like', "%{$this->venue}%")
            ->get()
            ->map(function ($item) {
                $backgroundColor = '#E4A11B'; // Default color

                switch ($item->status) {
                    case 'pending':
                        $backgroundColor = '#dc3545'; // Red
                        break;
                    case 'processed':
                        $backgroundColor = '#ffbf36'; // Yellow
                        break;
                    case 'forwarded':
                        $backgroundColor = '#282f3a'; // Dark
                        break;
                    case 'completed':
                        $backgroundColor = '#00d082'; // Green
                        break;
                    case 'cancelled':
                        $backgroundColor = '#6c757d'; // Grey
                        break;
                    default:
                        $backgroundColor = '#E4A11B';
                        break;
                }

                return [
                    'id'              => $item->incoming_request_id,
                    'title'           => $item->office_or_barangay_or_organization . ' | ' . strtoupper($item->venue),
                    'start'           => $item->request_date . 'T' . $item->start_time,
                    'end'             => $item->return_date . 'T' . $item->end_time,
                    'allDay'          => false,
                    'backgroundColor' => $backgroundColor
                ];
            });

        return $incoming_request;
    }

    //NOTE - file_size in KB convert to MB 
    public function convertSize($sizeInKB)
    {
        return round($sizeInKB / 1024, 2); // Convert KB to MB and round to 2 decimal places
    }
}
