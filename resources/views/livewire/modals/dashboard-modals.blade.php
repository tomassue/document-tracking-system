<!-- /* -------------------------------------------------------------------------- */
/*                          viewDetailsRequestModal                         */
/* -------------------------------------------------------------------------- */ -->

<!-- NOTE - This is not final yet. This is incase income request will have a modal just for VIEWING the details. For now, we will adapt the same functionality what is in the incoming>request page -->
<div class="modal fade" id="viewDetailsRequestModal" tabindex="-1" aria-labelledby="viewDetailsRequestModalLabel" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="viewDetailsRequestModalLabel">View Request</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:click="clear"></button>
            </div>
            <div class="modal-body">
                <div class="row py-3">
                    <div class="row col-md-6">
                        <div class="col-md-3">
                            Category:
                        </div>
                        <div class="col-md-9">
                            <span class="text-capitalize">{{ $incoming_request_category }}</span>
                        </div>
                    </div>
                    <div class="row col-md-6">
                        <div class="col-md-3">
                            Status:
                        </div>
                        <div class="col-md-9">
                            <span class="text-uppercase badge badge-pill
                            @if($incoming_request_status == 'pending')
                            badge-danger
                            @elseif($incoming_request_status == 'processed')
                            badge-warning
                            @elseif($incoming_request_status == 'forwarded')
                            badge-dark
                            @elseif($incoming_request_status == 'done')
                            badge-success
                            @endif
                            ">
                                {{ $incoming_request_status }}
                            </span>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row py-3">
                    <div class="row col-md-6">
                        <div class="col-md-3">
                            Office/Barangay/Organization:
                        </div>
                        <div class="col-md-9">
                            <span>{{ $incoming_request_office_barangay_organization }}</span>
                        </div>
                    </div>
                    <div class="row col-md-6">
                        <div class="col-md-3">
                            Request Date:
                        </div>
                        <div class="col-md-9">
                            <span>{{ $incoming_request_date }}</span>
                        </div>
                    </div>
                </div>
                <div class="row py-3">
                    <div class="row col-md-6">
                        <div class="col-md-3">
                            Category:
                        </div>
                        <div class="col-md-9">
                            <span class="text-capitalize">{{ $incoming_request_category_2 }}</span>
                        </div>
                    </div>
                    <div class="row col-md-6">
                        <div class="col-md-3">
                            Time:
                        </div>
                        <div class="col-md-9">
                            <span>{{ $incoming_request_start_time . ' - ' . $incoming_request_end_time }}</span>
                        </div>
                    </div>
                </div>
                <div class="row py-3">
                    <div class="row col-md-6">
                        <div class="col-md-3">
                            Sub-category:
                        </div>
                        <div class="col-md-9">
                            <span class="text-capitalize">{{ $incoming_request_venue }}</span>
                        </div>
                    </div>
                </div>
                <div class="row py-3">
                    <div class="row col-md-6">
                        <div class="col-md-3">
                            Description:
                        </div>
                        <div class="col-md-9">
                            <span>{{ $incoming_request_description }}</span>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row py-3">
                    <div class="col-md-6">
                        <label class="col-form-label">Attachments</label>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>File Name</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($attachment as $index=>$file)
                                    <tr wire:key="{{ $file->id }}">
                                        <td>{{ $index+1 }}</td>
                                        <td>{{ $file->file_name }}</td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-dark btn-rounded btn-icon" wire:click="previewAttachment({{ $file->id }})">
                                                <i class="mdi mdi mdi-eye "></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td class="text-center" colspan="4">No files found.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-6 d-flex justify-content-center align-items-center">
                        @if ($file_data)
                        <embed wire:loading.remove src="data:application/pdf;base64,{{ $file_data }}" title="{{ $file_title }}" type="application/pdf" style="height: 70vh; width: 100%;">
                        @else
                        <span>Preview file</span>
                        @endif
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" wire:click="clear">Close</button>
            </div>
        </div>
    </div>
</div>





<!-- /* -------------------------------------------------------------------------- */
/*                          viewDetailsDocumentsModal                         */
/* -------------------------------------------------------------------------- */ -->

<div class="modal fade" id="viewDetailsDocumentsModal" tabindex="-1" aria-labelledby="viewDetailsDocumentsModalLabel" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="viewDetailsDocumentsModalLabel">View Details</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:click="clear"></button>
            </div>
            <div class="modal-body">
                <div class="row py-3">
                    <div class="row col-md-6">
                        <div class="col-md-3">
                            Category:
                        </div>
                        <div class="col-md-9">
                            <span class="text-capitalize">{{ $incoming_document_category }}</span>
                        </div>
                    </div>
                    <div class="row col-md-6">
                        <div class="col-md-3">
                            Status:
                        </div>
                        <div class="col-md-9">
                            <span class="text-uppercase badge badge-pill
                            @if($incoming_document_status == 'pending')
                            badge-danger
                            @elseif($incoming_document_status == 'processed')
                            badge-warning
                            @elseif($incoming_document_status == 'forwarded')
                            badge-dark
                            @elseif($incoming_document_status == 'done')
                            badge-success
                            @endif
                            ">
                                {{ $incoming_document_status }}
                            </span>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row py-3">
                    <div class="row col-md-6">
                        <div class="col-md-3">
                            Document No.:
                        </div>
                        <div class="col-md-9">
                            <span>{{ $incoming_document_no }}</span>
                        </div>
                    </div>
                    <div class="row col-md-6">
                        <div class="col-md-3">
                            Request Date:
                        </div>
                        <div class="col-md-9">
                            <span>{{ $incoming_document_date }}</span>
                        </div>
                    </div>
                </div>
                <div class="row py-3">
                    <div class="row col-md-6">
                        <div class="col-md-3">
                            Document Info:
                        </div>
                        <div class="col-md-9">
                            <span>{{ $incoming_document_info }}</span>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <label class="col-form-label">Attachments</label>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>File Name</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($attachment as $index=>$file)
                                    <tr wire:key="{{ $file->id }}">
                                        <td>{{ $index+1 }}</td>
                                        <td>{{ $file->file_name }}</td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-dark btn-rounded btn-icon" wire:click="previewAttachment({{ $file->id }})">
                                                <i class="mdi mdi mdi-eye "></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td class="text-center" colspan="4">No files found.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-6 d-flex justify-content-center align-items-center">
                        @if ($file_data)
                        <embed wire:loading.remove src="data:application/pdf;base64,{{ $file_data }}" title="{{ $file_title }}" type="application/pdf" style="height: 70vh; width: 100%;">
                        @else
                        <span>Preview file</span>
                        @endif
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" wire:click="clear">Close</button>
            </div>
        </div>
    </div>
</div>





<!-- /* -------------------------------------------------------------------------- */
/*                               documentsModal                               */
/* -------------------------------------------------------------------------- */ -->

<div class="modal fade" id="documentsModal" tabindex="-1" aria-labelledby="documentsModalLabel" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="documentsModalLabel">{{ $editMode ? 'Edit' : 'Add' }} Request</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:click="clear"></button>
            </div>
            <div class="modal-body">
                <form class="form-sample" wire:submit="{{ $editMode ? 'update' : 'add' }}">
                    <p class="card-description">
                        <!-- Personal info -->
                    </p>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Category</label>
                                <div class="col-sm-9">
                                    <div id="incoming-category-documents-select" wire:ignore></div>
                                    @error('incoming_document_category') <span class="custom-invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Document No.</label>
                                <div class="col-sm-9">
                                    <!-- Document No's input is system generated. Thus, it will be manipulated in our component -->
                                    <input type="text" class="form-control" placeholder="{{ $document_no }}" disabled>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Request Date</label>
                                <div class="col-sm-9">
                                    <div wire:ignore>
                                        <input class="form-control document-incoming-date" required></input>
                                    </div>
                                    @error('date') <span class="custom-invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Document Info</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" wire:model="document_info">
                                    @error('document_info') <span class="custom-invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">{{ $editMode ? 'Status' : '' }}</label>
                                <div class="col-sm-9">
                                    <div id="document-status-select" wire:ignore></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group row">
                                <label class="col-sm-12 col-form-label">Attachment</label>
                                <div class="col-sm-12" wire:ignore>
                                    <input type="file" accept="application/pdf" class="form-control documents-my-pond-attachment" multiple data-allow-reorder="true">
                                </div>
                                @error('attachment') <span class="custom-invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" wire:click="clear">Close</button>
                <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">{{ $editMode ? 'Update' : 'Save' }}</button>
                </form>
            </div>
        </div>
    </div>
</div>




<!-- /* -------------------------------------------------------------------------- */
    /*                                requestModal                                */
    /* -------------------------------------------------------------------------- */ -->

<div class="modal fade" id="requestModal" tabindex="-1" aria-labelledby="requestModalLabel" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="requestModalLabel">{{ $editMode ? 'Edit' : 'Add' }} Request</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:click="clear"></button>
            </div>
            <div class="modal-body">
                <form class="form-sample" wire:submit="{{ $editMode ? 'update' : 'add' }}">
                    <p class="card-description">
                        <!-- Personal info -->
                    </p>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Category</label>
                                <div class="col-sm-9">
                                    <div id="incoming-category-select" wire:ignore></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">{{ $editMode ? 'Status' : '' }}</label>
                                <div class="col-sm-9">
                                    <div id="status-select" wire:ignore></div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <hr>
                    <div class="row pt-5">
                        <div class="col-md-6">
                            <div class="form-group row">
                                @error('incoming_request_office_barangay_organization') <span class="custom-invalid-feedback">{{ $message }}</span> @enderror
                                <label class="col-sm-3 col-form-label" style="padding-top: 0px;padding-bottom: 0px;">Office/Barangay/Organization</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" wire:model="incoming_request_office_barangay_organization">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                @error('request_date') <span class="custom-invalid-feedback">{{ $message }}</span> @enderror
                                <label class="col-sm-3 col-form-label">Request Date</label>
                                <div class="col-sm-9" wire:ignore>
                                    <input class="form-control request-date" required></input>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                @error('category') <span class="custom-invalid-feedback">{{ $message }}</span> @enderror
                                <label class="col-sm-3 col-form-label">Category</label>
                                <div class="col-sm-9">
                                    <div id="category-select" wire:ignore></div>
                                </div>

                                <!-- NOTE - This will be initialized when an event is triggered. -->
                                <!-- LINK - app\Livewire\Incoming\Request.php#updatedCategory() -->
                                <label class="col-sm-3 col-form-label"></label>
                                <div class="col-sm-9">
                                    <div id="venue-select" wire:ignore></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group row">
                                @php
                                $timeError = $errors->first('start_time') ?: $errors->first('end_time');
                                @endphp

                                @if ($timeError)
                                <span class="custom-invalid-feedback">{{ $timeError }}</span>
                                @endif
                                <label class="col-sm-3 col-form-label">Time</label>
                                <div class="col-sm-4" wire:ignore>
                                    <input class="form-control from-time" placeholder="From" required>
                                </div>
                                <div class="col-sm-4" wire:ignore>
                                    <input class="form-control end-time" placeholder="To" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group row">
                                @error('description') <span class="custom-invalid-feedback">{{ $message }}</span> @enderror
                                <label class="col-sm-2 col-form-label">Description</label>
                                <div class="col-sm-12" wire:ignore>
                                    <input id="myeditorinstance"></input>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group row">
                                @error('attachment') <span class="custom-invalid-feedback">{{ $message }}</span> @enderror
                                <label class="col-sm-2 col-form-label">Attachment</label>
                                <div class="col-sm-10" wire:ignore>
                                    <input type="file" class="form-control my-pond-attachment" multiple data-allow-reorder="true">
                                </div>
                            </div>
                            @if ($editMode == true)
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="col-form-label">Attachments</label>
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>No.</th>
                                                    <th>File Name</th>
                                                    <th class="text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($attachment as $index=>$file)
                                                <tr wire:key="{{ $file->id }}">
                                                    <td>{{ $index+1 }}</td>
                                                    <td>{{ $file->file_name }}</td>
                                                    <td class="text-center">
                                                        <button type="button" class="btn btn-dark btn-rounded btn-icon" wire:click="previewAttachment('{{ $file->id }}')">
                                                            <i class="mdi mdi mdi-eye "></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td class="text-center" colspan="4">No files found.</td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-md-6 d-flex justify-content-center align-items-center">
                                    @if ($file_data)
                                    <embed wire:loading.remove src="data:application/pdf;base64,{{ $file_data }}" title="{{ $file_title }}" type="application/pdf" style="height: 70vh; width: 100%;">
                                    @else
                                    <span>Preview file</span>
                                    @endif
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" wire:click="clear">Close</button>
                <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">{{ $editMode ? 'Update' : 'Save' }}</button>
                </form>
            </div>
        </div>
        /div>
    </div>
</div>





@include('livewire.modals.dashboard-modal-documentsModal-scripts')
@include('livewire.modals.dashboard-modal-requestModal-scripts')

@script
<script>
    /* ---------------------- show-viewDetailsRequestModal ---------------------- */

    //LINK - resources\views\livewire\modals\dashboard-modals.blade.php:5
    $wire.on('show-viewDetailsRequestModal', () => {
        $('#viewDetailsRequestModal').modal('show');
    });

    $wire.on('hide-viewDetailsRequestModal', () => {
        $('#viewDetailsRequestModal').modal('hide');
    });


    /* --------------------- show-viewDetailsDocumentsModal --------------------- */

    $wire.on('show-viewDetailsDocumentsModal', () => {
        $('#viewDetailsDocumentsModal').modal('show');
    });

    $wire.on('hide-viewDetailsDocumentsModal', () => {
        $('#viewDetailsDocumentsModal').modal('hide');
    });

    /* ---------------------------- show-requestModal --------------------------- */

    $wire.on('show-requestModal', () => {
        $('#requestModal').modal('show');
    });

    $wire.on('hide-requestModal', () => {
        $('#requestModal').modal('hide');
    });
</script>
@endscript