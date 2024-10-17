<?php

namespace App\Livewire\CPSO\Incoming;

use App\Models\Document_History_Model;
use App\Models\File_Data_Model;
use App\Models\Incoming_Request_CPSO_Model;
use App\Models\NumberMessageModel;
use App\Models\Ref_Category_Model;
use App\Models\Ref_Venue_Model;
use App\Models\SmsSenderModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;

#[Title('Request | CPSO Management System')]
class Request extends Component
{
    /**
     * NOTE
     * A number of offices uses this system but with different system requirements but mostly of the requirements somehow seems to be similar.
     * We will be controlling the data access through which office the user is under.
     * 
     * Use Auth::user()->ref_office == 'CPSO' to control it. -->EXAMPLE
     * 
     * LOGS
     * - CPSO is ONGOING -> DONE
     */

    use WithPagination, WithFileUploads;

    /**
     * NOTE
     * `$page_type`
     * What happened is that I nested the Request component to Dashboard component.
     * Passing data from the parent component (Dashboard) is like passing props to a typical Blade component.
     * We can receive that data through the child component's mount() method.
     */
    public $page_type = "";
    public $hide_button_if_completed;

    /* --------------------------------- FILTER --------------------------------- */

    public $filter_category, $filter_status;

    /* ------------------------------- END FILTER ------------------------------- */

    /* --------------------------------- OTHERS --------------------------------- */

    public $show_return_date = false; // This is for the return date input field when users are about to set the status to done for categories like equipment and vehicle.

    /* ------------------------------- END OTHERS ------------------------------- */

    public $search, $incoming_category = 'request', $status, $office_barangay_organization, $request_date, $return_date, $category, $venue, $start_time, $end_time, $contact_person, $contact_person_number, $description, $attachment = [];
    public $return_date_for_equipment_and_vehicle; // This is for when categories like the vehicle and equipment are updated to DONE, users should input the return date of those categories first.
    public $notes; // For adding remarks or notes on every status updates.
    public $file_id, $file_title, $file_data;
    public $editMode, $edit_document_id;
    public $document_history = [];

    // LINK - app\Livewire\CPSO\Incoming\Request.php#34
    public function mount($page_type = "")
    {
        $this->page_type = $page_type;
    }

    public function rules()
    {
        $rules = [
            'incoming_category' => 'required',
            'office_barangay_organization' => 'required',
            'request_date' => 'required',
            'category' => 'required',
            'start_time' => 'required',
            'end_time' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (strtotime($value) <= strtotime($this->start_time)) {
                        $fail('The end time must be after the start time and cannot be the same.');
                    }
                },
            ],
            'venue' => $this->category == '9' ? 'required' : 'nullable', // Conditionally require the 'venue' field if category is 9
            'contact_person' => 'required',
            'contact_person_number' => 'required|phone:PH'
            // 'description' => 'required',
            // 'attachment' => 'required'
        ];

        // Conditionally require return_date if category is 9, and ensure it's after request_date
        if ($this->category == '9') {
            $rules['return_date'] = [
                'required',
                function ($attribute, $value, $fail) {
                    if (strtotime($value) < strtotime($this->request_date)) {
                        $fail('The return date must be after the request date.');
                    } elseif (strtotime($value) == strtotime($this->request_date)) {
                        // Optional: Allow the same day but compare times
                        if (strtotime($this->end_time) <= strtotime($this->start_time)) {
                            $fail('The return time must be after the request time when on the same day.');
                        }
                    }
                },
            ];
        }

        if ($this->show_return_date && $this->status == 'completed') {
            $rules['return_date_for_equipment_and_vehicle'] = [
                'required',
                function ($attribute, $value, $fail) {
                    $requestDateTime = strtotime($this->request_date);
                    $returnDateTime = strtotime($value);

                    // Ensure return date is after request date, allowing within the same day
                    if ($returnDateTime < $requestDateTime) {
                        $fail('The return date for equipment and vehicle must be after the request date.');
                    }
                },
            ];
        }


        return $rules;
    }

    public function validationAttributes()
    {
        return [
            'office_barangay_organization' => 'input',
            'incoming_category' => 'input',
            'return_date_for_equipment_and_vehicle' => 'return date'
        ];
    }

    public function render()
    {

        $data = [
            'incoming_requests_cpso' => $this->loadIncomingRequestsCPSO(),
            'categories' => $this->loadCategories(),
            'venues' => $this->loadVenues()
        ];

        return view('livewire.CPSO.incoming.request', $data);
    }

    public function clear()
    {
        $this->resetExcept('page_type', 'filter_status'); // Since we need the page_type as what I mentioned, we will not clear the property.
        $this->resetValidation();
        // $this->dispatch('clear-plugins');
    }

    public function updated($property)
    {
        if ($property === 'status') {
            $this->dispatch('reset-return_date_for_equipment_and_vehicle');
        }
    }

    public function openRequestModal()
    {
        $this->clear();
        $this->dispatch('refresh-plugin');
        $this->dispatch('show-requestModal');
    }

    public function add()
    {
        $this->validate();

        $overlap = Incoming_Request_CPSO_Model::join(
            DB::raw('(SELECT document_id, status
                    FROM document_history
                    WHERE id IN (
                        SELECT MAX(id)
                        FROM document_history
                        GROUP BY document_id
                    )) AS latest_document_history'),
            'latest_document_history.document_id',
            '=',
            'incoming_request_cpso.incoming_request_id'
        )
            ->where('venue', $this->venue)
            ->whereIn('latest_document_history.status', ['pending', 'processed', 'forwarded']) // Only consider relevant statuses
            ->where(function ($query) {
                $query->where(function ($query) {
                    // New booking starts during an existing booking
                    $query->where('request_date', '<=', $this->request_date)
                        ->where('return_date', '>=', $this->request_date)
                        ->where('start_time', '<=', $this->start_time)
                        ->where('end_time', '>=', $this->start_time);
                })
                    ->orWhere(function ($query) {
                        // New booking ends during an existing booking
                        $query->where('request_date', '<=', $this->return_date)
                            ->where('return_date', '>=', $this->return_date)
                            ->where('start_time', '<=', $this->end_time)
                            ->where('end_time', '>=', $this->end_time);
                    });
                // ->orWhere(function ($query) {
                //     // New booking completely overlaps an existing booking
                //     $query->where('request_date', '>=', $this->request_date)
                //         ->where('return_date', '<=', $this->return_date);
                // });
            })
            ->exists();

        if ($overlap) {
            $this->dispatch('show-overlapping-venu-request-toast');
        } else {
            try {
                DB::beginTransaction();

                # Iterate over each file.
                # Uploading small files is okay with BLOB data type. I encountered an error where uploading bigger size such as PDF won't upload in the database which is resulting an error.
                foreach ($this->attachment ?? [] as $file) {
                    $file_data = File_Data_Model::create([
                        'file_name' => $file->getClientOriginalName(),
                        'file_size' => $file->getSize(),
                        'file_type' => $file->extension(),
                        'file' => file_get_contents($file->path()),
                        'user_id' => Auth::user()->id
                    ]);
                    // Store the ID of the saved file
                    $file_data_IDs[] = $file_data->id;
                }

                $incoming_request_data = [
                    'incoming_request_id' => $this->generateUniqueNumber(),
                    'incoming_category' => $this->incoming_category,
                    'office_or_barangay_or_organization' => $this->office_barangay_organization,
                    'request_date' => $this->request_date,
                    'return_date' => $this->return_date,
                    'category' => $this->category,
                    'venue' => $this->venue,
                    'start_time' => $this->start_time,
                    'end_time' => $this->end_time,
                    'contact_person' => $this->contact_person,
                    'contact_person_number' => $this->contact_person_number,
                    'description' => $this->description,
                    'files' => json_encode($file_data_IDs ?? [])
                ];
                $incoming_request = Incoming_Request_CPSO_Model::create($incoming_request_data);

                $document_history_data = [
                    'document_id' => $incoming_request->incoming_request_id,
                    'status' => 'pending',
                    'user_id' => Auth::user()->id,
                    'remarks' => 'created_by'
                ];
                Document_History_Model::create($document_history_data);

                DB::commit();

                $this->clear();
                $this->dispatch('hide-requestModal');
                $this->dispatch('show-success-save-message-toast');
            } catch (\Exception $e) {
                DB::rollBack();

                // dd($e->getMessage());
                $this->dispatch('show-something-went-wrong-toast');
            }
        }
    }

    public function edit($key)
    {
        $this->editMode = true;
        $this->edit_document_id = $key;

        $this->dispatch('refresh-plugin');

        $incoming_request = Incoming_Request_CPSO_Model::where('incoming_request_id', $key)->first();
        $document_history = Document_History_Model::where('document_id', $key)->latest()->first(); //NOTE - latest() returns the most recent record based on the `created_by` column. This ia applicable to our document_history since we store multiple foreign keys to track updates and who updated them. We mainly want to return the latest status and populate it to our `status-select` when `editMode` is true.

        $this->dispatch('set-incoming_category', $incoming_request->incoming_category);

        if ($document_history->status == 'completed' || $document_history->status == 'cancelled') {
            $this->dispatch('set-status-disabled', $document_history->status); // Since the status is DONE, we won't allow users to modify the document's status.
            $this->hide_button_if_completed = true;
            $this->dispatch('set-notes');
            // $this->status = 'done';
        } else {
            $this->dispatch('set-status-enable', $document_history->status);
        }

        $this->office_barangay_organization = $incoming_request->office_or_barangay_or_organization;

        $this->dispatch('set-request-date', $incoming_request->request_date);

        if ($incoming_request->category == '14' || $incoming_request->category == '15') {
            $this->show_return_date = true;
        }

        if ($incoming_request->return_date) {
            $this->dispatch('set-return-date', $incoming_request->return_date);
        }

        $this->dispatch('set-category', $incoming_request->category);

        $this->dispatch('set-venue', $incoming_request->venue);

        $this->dispatch('set-start-time', $incoming_request->start_time);

        $this->dispatch('set-end-time', $incoming_request->end_time);

        $this->contact_person = $incoming_request->contact_person;

        $this->contact_person_number = $incoming_request->contact_person_number;

        $this->dispatch('set-description', $incoming_request->description);

        if ($incoming_request->files) {
            foreach (json_decode($incoming_request->files) as $item) {
                $file = File_Data_Model::where('id', $item)
                    ->select(
                        'id',
                        'file_name',
                    )
                    ->first();
                $file->file_size = $this->convertSize($file->file_size);
                $this->attachment[] = $file;
            }
        }

        $this->dispatch('show-requestModal');
    }

    public function update()
    {
        $this->validate();

        $check_contact_person_contact_number = Incoming_Request_CPSO_Model::leftJoin('ref_venues', 'ref_venues.id', '=', 'incoming_request_cpso.venue')
            ->leftJoin('ref_category', 'ref_category.id', '=', 'incoming_request_cpso.category')
            ->where('incoming_request_cpso.incoming_request_id', $this->edit_document_id)
            ->select(
                'incoming_request_cpso.contact_person_number',
                'incoming_request_cpso.contact_person',
                'incoming_request_cpso.office_or_barangay_or_organization',
                'ref_category.category',
                'ref_venues.venue',
                DB::raw("DATE_FORMAT(incoming_request_cpso.request_date, '%b %d, %Y') AS request_date"),
                DB::raw("DATE_FORMAT(incoming_request_cpso.return_date, '%b %d, %Y') AS return_date"),
                'incoming_request_cpso.start_time',
                'incoming_request_cpso.end_time',
            )
            ->first();

        if ($check_contact_person_contact_number) {
            try {
                DB::beginTransaction();

                $sms = new SmsSenderModel();
                $blaster = new NumberMessageModel();

                // Generate the SMS message
                $return_date_message = $check_contact_person_contact_number->return_date
                    ? "\nReturn Date: " . $check_contact_person_contact_number->return_date
                    : "\nReturn Date: Not specified";

                $welcome = "CPSO Management System INFO:";
                $sms->trans_id = time() . '-' . mt_rand();
                $sms->received_id = "DOCUMENT-TRACKING-SYSTEM-CPSO";
                $sms->recipient = $check_contact_person_contact_number->contact_person_number;
                $sms->recipient_message = $welcome . " \nGood day " . $check_contact_person_contact_number->contact_person . "!" .
                    "\n\nRequest: " . strtoupper($check_contact_person_contact_number->category) .
                    "\nRequest Date: " . $check_contact_person_contact_number->request_date .
                    $return_date_message .
                    "\nRequest Status: " . strtoupper($this->status) .
                    "\n\nPLEASE DON'T REPLY.";
                $sms->save();

                $blaster->user_id = $check_contact_person_contact_number->contact_person;
                $blaster->phone_number = $check_contact_person_contact_number->contact_person_number;
                $blaster->sms_trans_id = $sms->trans_id;
                $blaster->sms_status = "SAVED";
                $blaster->save();

                // NOTE - For now, we will update the status only and record the action in our document_history
                $document_history = Document_History_Model::query();
                $data = [
                    'document_id' => $this->edit_document_id,
                    'status' => $this->status,
                    'user_id' => Auth::user()->id,
                    'remarks' => 'updated_by',
                    'notes' => $this->notes
                ];
                $document_history->create($data);

                if ($this->return_date_for_equipment_and_vehicle) {
                    Incoming_Request_CPSO_Model::where('incoming_request_id', $this->edit_document_id)
                        ->update([
                            'return_date' => $this->return_date_for_equipment_and_vehicle
                        ]);
                }

                $this->dispatch('hide-requestModal');
                $this->dispatch('show-success-update-message-toast');
                $this->clear();

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();

                dd($e->getMessage());
                // $this->dispatch('show-something-went-wrong-toast');
            }
        } else {
            $this->dispatch('show-something-went-wrong-toast');
        }
    }

    // Closing attachment preview
    public function clearFileData()
    {
        $this->reset('file_id', 'file_data');
    }

    #[On('preview-attachment')]
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

    public function loadIncomingRequestsCPSO()
    {
        $incoming_requests_cpso = DB::table('incoming_request_cpso')
            ->join(DB::raw('(SELECT document_id, status
                    FROM document_history
                    WHERE id IN (
                        SELECT MAX(id)
                        FROM document_history
                        GROUP BY document_id
                    )) AS latest_document_history'), 'latest_document_history.document_id', '=', 'incoming_request_cpso.incoming_request_id')
            ->join('ref_category', 'ref_category.id', '=', 'incoming_request_cpso.category')
            ->leftJoin('ref_venues', 'ref_venues.id', '=', 'incoming_request_cpso.venue')
            ->where('ref_category.document_type', 'incoming request')
            ->select(
                'incoming_request_cpso.incoming_request_id AS id',
                DB::raw("DATE_FORMAT(incoming_request_cpso.request_date, '%b %d, %Y') AS request_date"),
                DB::raw("DATE_FORMAT(incoming_request_cpso.return_date, '%b %d, %Y') AS return_date"),
                'incoming_request_cpso.office_or_barangay_or_organization',
                'ref_category.category',
                'ref_venues.venue',
                'latest_document_history.status'
            )
            ->where('incoming_request_cpso.office_or_barangay_or_organization', 'like', '%' . $this->search . '%')
            ->when($this->page_type == "dashboard", function ($query) {
                return $query->where('latest_document_history.status', 'pending');
            })
            /**
             * Default behavior: The last when statement checks if $this->filter_status is NULL. If it is, the query adds a condition to exclude records where the status is "done".
             * Filter behavior: When $this->filter_status is set (not NULL), it will show records matching that specific status, including "done".
             */
            ->when($this->filter_status != NULL, function ($query) {
                $query->where('latest_document_history.status', $this->filter_status);
            }, function ($query) {
                // Exclude "done" status by default
                $query->where('latest_document_history.status', '!=', 'completed')
                    ->where('latest_document_history.status', '!=', 'cancelled');
            })
            ->when($this->filter_category != NULL, function ($query) {
                $query->where('incoming_request_cpso.category', $this->filter_category);
            })
            ->orderBy('incoming_request_cpso.request_date', 'ASC')
            ->paginate(10);

        return $incoming_requests_cpso;
    }

    public function loadVenues()
    {
        // For the venues option
        $venues = Ref_Venue_Model::where('is_active', 'yes')
            ->get()
            ->map(function ($item) {
                return [
                    'label' => $item->venue,
                    'value' => $item->id
                ];
            });

        return $venues;
    }

    public function loadCategories()
    {
        $categories = Ref_Category_Model::join('user_offices', 'user_offices.user_id', '=', 'ref_category.created_by')
            ->where('user_offices.office_id', Auth::user()->ref_office->id)
            ->select(
                'ref_category.id',
                'ref_category.category',
                'ref_category.document_type',
                'ref_category.is_active'
            )
            ->where('document_type', 'incoming request')
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

    //NOTE - file_size in KB convert to MB 
    public function convertSize($sizeInKB)
    {
        return round($sizeInKB / 1024, 2); // Convert KB to MB and round to 2 decimal places
    }

    //NOTE - This is for retrieving data from database to pickatime. To avoid errors due to format, we convert the time to minutes.
    public function timeToMinutes($time)
    {
        $hours = intval(date('H', strtotime($time)));
        $minutes = intval(date('i', strtotime($time)));
        return [$hours, $minutes];
    }

    //NOTE - Method to generate a unique number
    private function generateUniqueNumber()
    {
        // Get the current Unix timestamp
        $timestamp = time();

        // NOTE - You can adjust the numbers here.
        // Extract the last eight digits of the timestamp. 
        $uniqueIdentifier = substr($timestamp, -8);

        // Generate four random lowercase letters (a-z)
        $randomLetters = '';
        for ($i = 0; $i < 4; $i++) {
            $randomLetters .= strtoupper(chr(mt_rand(97, 122))); // ASCII values for lowercase letters
        }

        // Concatenate the random letters with the eight-digit number
        $uniqueNumber = $uniqueIdentifier . $randomLetters;

        // Shuffle the unique number (digits and letters)
        $shuffledNumber = str_shuffle($uniqueNumber);

        return $shuffledNumber;
    }
}
