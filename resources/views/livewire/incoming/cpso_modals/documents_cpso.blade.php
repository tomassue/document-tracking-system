<!-- /* -------------------------------------------------------------------------- */
/*                          viewDetailsDocumentsModal                         */
/* -------------------------------------------------------------------------- */ -->

<div class="modal fade" id="viewDetailsDocumentsModal" tabindex="-1" aria-labelledby="viewDetailsDocumentsModalLabel" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="viewDetailsDocumentsModalLabel">{{ $editMode ? 'Edit' : 'Add' }} Request</h1>
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
                            Document No.:
                        </div>
                        <div class="col-md-9">
                            <span>{{ $document_no }}</span>
                        </div>
                    </div>
                    <div class="row col-md-6">
                        <div class="col-md-3">
                            Request Date:
                        </div>
                        <div class="col-md-9">
                            <span>{{ $date }}</span>
                        </div>
                    </div>
                </div>
                <div class="row py-3">
                    <div class="row col-md-6">
                        <div class="col-md-3">
                            Document Info:
                        </div>
                        <div class="col-md-9">
                            <span>{{ $document_info }}</span>
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

<!-- /* -------------------------------------------------------------------------- */
/*                          viewDetailsDocumentsModal                         */
/* -------------------------------------------------------------------------- */ -->


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
                                    <input type="text" class="form-control" wire:model="document_info" {{ $editMode ? 'disabled' : '' }}>
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

                    <div class="row" style="display: {{ $editMode ? 'none' : 'block' }}">
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
/*                               documentsModal                               */
/* -------------------------------------------------------------------------- */ -->

@script
<script>
    $wire.on('show-documentsModal', () => {
        $('#documentsModal').modal('show');
    });

    $wire.on('hide-documentsModal', () => {
        $('#documentsModal').modal('hide');
    });

    /* -------------------------------------------------------------------------- */

    /* -------------------------------------------------------------------------- */

    $wire.on('show-viewDetailsDocumentsModal', () => {
        $('#viewDetailsDocumentsModal').modal('show');
    });

    /* -------------------------------------------------------------------------- */

    VirtualSelect.init({
        ele: '#incoming-category-documents-select',
        options: @json($categories),
        maxWidth: '100%',
        zIndex: 10,
        popupDropboxBreakpoint: '3000px',
    });

    let incoming_document_category = document.querySelector('#incoming-category-documents-select');
    incoming_document_category.addEventListener('change', () => {
        let data = incoming_document_category.value;
        @this.set('incoming_document_category', data);
    });

    // NOTE - Edit Mode
    $wire.on('set-incoming-category-documents-select', (key) => {
        document.querySelector('#incoming-category-documents-select').setValue(key[0]);
        document.querySelector('#incoming-category-documents-select').disable();
        // console.log(key[0]);
    });

    /* -------------------------------------------------------------------------- */

    /* -------------------------------------------------------------------------- */

    //NOTE - Edit mode (document-status-select). Status select will only be initialized during editMode.
    $wire.on('set-document-status-select', (key) => {
        VirtualSelect.init({
            ele: '#document-status-select',
            options: [{
                    label: 'Pending',
                    value: 'pending'
                },
                {
                    label: 'Processed',
                    value: 'processed'
                },
                {
                    label: 'Forwarded',
                    value: 'forwarded'
                },
                {
                    label: 'Done',
                    value: 'done'
                }
            ],
            maxWidth: '100%',
            zIndex: 10,
            popupDropboxBreakpoint: '3000px',
        });

        let status = document.querySelector('#document-status-select');
        status.addEventListener('change', () => {
            let data = status.value;
            @this.set('status', data);
        });

        document.querySelector('#document-status-select').setValue(key[0]);
    });

    /* -------------------------------------------------------------------------- */

    /* -------------------------------------------------------------------------- */

    $('.document-incoming-date').pickadate({
        klass: {
            holder: 'picker__holder',
        }
    });

    // Handling Pickadate (.document-incoming-date) change event
    $('.document-incoming-date').on('change', function(event) {
        let picker = $(this).pickadate('picker');
        let selectedDate = picker.get('select', 'yyyy-mm-dd'); // Adjust format as needed
        @this.set('date', selectedDate);
    });

    // NOTE - Edit Mode
    $wire.on('set-document-incoming-date', (key) => {
        $('.document-incoming-date').each(function() {
            let picker = $(this).pickadate('picker'); //NOTE - clear out the values
            picker.clear();
            $('.document-incoming-date').attr('disabled', 'disabled');

            let request_date_key = key[0]; //NOTE - unset it from an array (key[0]);
            picker.set('select', request_date_key, {
                format: 'yyyy-mm-dd'
            }); //NOTE - you need the format, so that it will be correctly displayed in the input field.
        });
        // console.log(key[0]);
    });

    /* -------------------------------------------------------------------------- */

    /* -------------------------------------------------------------------------- */

    $wire.on('set-status', (key) => {
        VirtualSelect.init({
            ele: '#document-status-select',
            options: [{
                    label: 'Pending',
                    value: 'pending'
                },
                {
                    label: 'Processed',
                    value: 'processed'
                },
                {
                    label: 'Forwarded',
                    value: 'forwarded'
                },
                {
                    label: 'Done',
                    value: 'done'
                }
            ],
            maxWidth: '100%',
            zIndex: 10,
            popupDropboxBreakpoint: '3000px',
        });

        // console.log(key[0]);
    });

    /* -------------------------------------------------------------------------- */

    /* -------------------------------------------------------------------------- */

    FilePond.registerPlugin(FilePondPluginFileValidateType);
    $('.documents-my-pond-attachment').filepond({
        // required: true,
        acceptedFileTypes: ['application/pdf'],
        server: {
            // This will assign the data to the attachment[] property.
            process: (fieldName, file, metadata, load, error, progress, abort) => {
                @this.upload('attachment', file, load, error, progress);
            },
            revert: (uniqueFileId, load, error) => {
                @this.removeUpload('attachment', uniqueFileId, load, error);
            }
        }
    });

    /* -------------------------------------------------------------------------- */

    /* -------------------------------------------------------------------------- */

    //NOTE - Clear plugins
    $wire.on('clear-plugins', () => {
        document.querySelector('#incoming-category-documents-select').reset();

        // document.querySelector('#document-status-select').destroy();

        $('.document-incoming-date').each(function() {
            $(this).pickadate('picker').clear();
        });

        // Clear FilePond
        $('.documents-my-pond-attachment').each(function() {
            $(this).filepond('removeFiles');
        });

        // console.log('cleared');
    });

    /* -------------------------------------------------------------------------- */

    $wire.on('enable-plugins', () => {
        document.querySelector('#incoming-category-documents-select').enable();
        $('.document-incoming-date').removeAttr('disabled');
    });
</script>
@endscript