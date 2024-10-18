<?php

namespace App\Http\Controllers;

use App\Models\Incoming_Request_CPSO_Model;
use Barryvdh\DomPDF\Facade\Pdf;

class GeneratePDFController extends Controller
{
    public function venueSchedulePDF()
    {
        if (!request()->hasValidSignature()) {
            abort(403);
        }

        // Fetch venues based on query parameters
        $venues = Incoming_Request_CPSO_Model::where('venue', request('venue'))
            ->where('request_date', request('date'))
            ->get();

        if ($venues->isEmpty()) {
            return response()->json(['message' => 'No venues found for the specified date.'], 404);
        }

        $data = ['venues' => $venues];

        // Load the view into the PDF
        $pdf = Pdf::loadView('livewire.CPSO.pdf.venue-schedule', $data);

        // Stream the PDF to the browser
        return $pdf->stream('venue-schedule.pdf');
    }
}
