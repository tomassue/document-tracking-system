<div class="modal fade" id="viewDetailsModal" tabindex="-1" aria-labelledby="viewDetailsModalLabel" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="viewDetailsModalLabel">{{ $editMode ? 'Edit' : 'Add' }} Request</h1>
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
                            @if($status == 'pending')
                            badge-danger
                            @elseif($status == 'processed')
                            badge-warning
                            @elseif($status == 'forwarded')
                            badge-dark
                            @elseif($status == 'done')
                            badge-success
                            @endif
                            ">
                                {{ $status }}
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
                            <span>{{ $office_or_barangay_or_organization }}</span>
                        </div>
                    </div>
                    <div class="row col-md-6">
                        <div class="col-md-3">
                            Request Date:
                        </div>
                        <div class="col-md-9">
                            <span>{{ $request_date }}</span>
                        </div>
                    </div>
                </div>
                <div class="row py-3">
                    <div class="row col-md-6">
                        <div class="col-md-3">
                            Venue:
                        </div>
                        <div class="col-md-9">
                            <span class="text-capitalize">{{ $incoming_request_venue }}</span>
                        </div>
                    </div>
                    <div class="row col-md-6">
                        <div class="col-md-3">
                            Time:
                        </div>
                        <div class="col-md-9">
                            <span>{{ $start_time . ' - ' . $end_time }}</span>
                        </div>
                    </div>
                </div>
                <div class="row py-3">
                    <div class="row col-md-6">
                        <div class="col-md-3">
                            Description:
                        </div>
                        <div class="col-md-9">
                            <span>{{ $description }}</span>
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
                                    @forelse($files as $index=>$file)
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