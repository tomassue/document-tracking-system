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
                                    @error('incoming_category') <span class="custom-invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6" style="display: {{ $editMode ? 'block' : 'none' }}">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Status</label>
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
                                <label class="col-sm-3 col-form-label" style="padding-top: 0px;padding-bottom: 0px;">Office/Barangay/Organization</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" wire:model="office_barangay_organization" {{ $editMode ? 'disabled' : '' }}>
                                    @error('office_barangay_organization') <span class="custom-invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Request Date</label>
                                <div class="col-sm-9">
                                    <div wire:ignore>
                                        <input class="form-control request-date" required></input>
                                    </div>
                                    @error('request_date') <span class="custom-invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Category</label>
                                <div class="col-sm-9">
                                    <div id="category-select" wire:ignore></div>
                                    <div style="display: {{ $category == 'venue' ? 'display' : 'none' }}" class="mt-2">
                                        <div id="venue-select" wire:ignore></div>
                                    </div>
                                    @error('category') <span class="custom-invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group row">
                                @php
                                $timeError = $errors->first('start_time') ?: $errors->first('end_time');
                                @endphp

                                <label class="col-sm-3 col-form-label">Time</label>
                                <div class="col-sm-4">
                                    <div wire:ignore>
                                        <input class="form-control from-time" placeholder="From" required>
                                    </div>
                                    @if ($timeError)
                                    <span class="custom-invalid-feedback">{{ $timeError }}</span>
                                    @endif
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
                                <label class="col-sm-2 col-form-label">Description</label>
                                <div class="col-sm-12">
                                    <!-- <textarea name="" id="" class="form-control" style="height: 110px;"></textarea> -->
                                    <!-- <input type="text" class="form-control"> -->
                                    <div wire:ignore>
                                        <div id="summernote_description"></div>
                                    </div>
                                    @error('description') <span class="custom-invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div style="display : {{ $editMode ? 'none' : 'block' }}">
                                <div class="form-group row">
                                    <label class="col-sm-12 col-form-label">Attachment</label>
                                    <div class="col-sm-12">
                                        <div wire:ignore>
                                            <input type="file" class="form-control my-pond-attachment" multiple data-allow-reorder="true">
                                        </div>
                                        @error('attachment') <span class="custom-invalid-feedback">{{ $message }}</span> @enderror
                                    </div>
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
                                                        <button type="button" class="btn btn-dark btn-rounded btn-icon" wire:click="$dispatch('preview-attachment', { key: {{ $file->id }} } )">
                                                            <i class="mdi mdi mdi-eye "></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="4">No files found.</td>
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
    </div>
</div>

<!-- /* -------------------------------------------------------------------------- */
    /*                              end requestModal                              */
    /* -------------------------------------------------------------------------- */ -->

@script
<script>
    /* -------------------------------------------------------------------------- */

    $wire.on('show-requestModal', () => {
        $('#requestModal').modal('show');
    });

    $wire.on('hide-requestModal', () => {
        $('#requestModal').modal('hide');
    });

    /* -------------------------------------------------------------------------- */

    VirtualSelect.init({
        ele: '#status-select',
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

    let status = document.querySelector('#status-select');
    status.addEventListener('change', () => {
        let data = status.value;
        @this.set('status', data);
    });

    $wire.on('set-status', (key) => {
        document.querySelector('#status-select').setValue(key[0]);
        // document.querySelector('#status-select').disable();
    });

    /* -------------------------------------------------------------------------- */

    VirtualSelect.init({
        ele: '#incoming-category-select',
        options: @json($categories),
        maxWidth: '100%',
        zIndex: 10,
        popupDropboxBreakpoint: '3000px',
    });

    let incoming_category = document.querySelector('#incoming-category-select');
    incoming_category.addEventListener('change', () => {
        let data = incoming_category.value;
        @this.set('incoming_category', data);
    });

    $wire.on('set-incoming_category', (key) => {
        document.querySelector('#incoming-category-select').setValue(key[0]);
        document.querySelector('#incoming-category-select').disable();
    });

    /* -------------------------------------------------------------------------- */

    $('.request-date').pickadate({
        klass: {
            holder: 'picker__holder',
        }
    });

    // Handling Pickadate (.request-date) change event
    $('.request-date').on('change', function(event) {
        let picker = $(this).pickadate('picker');
        let selectedDate = picker.get('select', 'yyyy-mm-dd'); // Adjust format as needed
        @this.set('request_date', selectedDate);
    });

    $wire.on('set-request-date', (key) => {
        $('.request-date').each(function() {
            let picker = $(this).pickadate('picker'); //NOTE - clear out the values
            picker.clear();
            $('.request-date').attr('disabled', 'disabled');

            let request_date_key = key[0]; //NOTE - unset it from an array (key[0]);
            picker.set('select', request_date_key, {
                format: 'yyyy-mm-dd'
            }); //NOTE - you need the format, so that it will be correctly displayed in the input field.
        });
    });

    /* -------------------------------------------------------------------------- */

    VirtualSelect.init({
        ele: '#category-select',
        options: [{
                label: 'Equipment',
                value: 'equipment'
            },
            {
                label: 'Venue',
                value: 'venue'
            },
            {
                label: 'Vehicle',
                value: 'vehicle'
            },
            {
                label: 'Band',
                value: 'band'
            },
            {
                label: 'Others',
                value: 'others'
            }
        ],
        maxWidth: '100%',
        zIndex: 10,
        popupDropboxBreakpoint: '3000px',
    });

    let category = document.querySelector('#category-select');
    category.addEventListener('change', () => {
        let data = category.value;
        @this.set('category', data);
    });

    $wire.on('set-category', (key) => {
        document.querySelector('#category-select').setValue(key[0]); //NOTE - a shorter code of what we did in #category-select (Edit Mode)
        document.querySelector('#category-select').disable();
    });

    /* -------------------------------------------------------------------------- */

    VirtualSelect.init({
        ele: '#venue-select',
        placeholder: 'Select venue',
        options: [{
                label: 'Tourism Hall',
                value: 'tourism hall'
            },
            {
                label: 'Mini Park',
                value: 'mini park'
            },
            {
                label: 'Amphitheater',
                value: 'amphitheater'
            },
            {
                label: 'Quadrangle',
                value: 'quadrangle'
            }
        ],
        maxWidth: '100%',
        zIndex: 10,
        popupDropboxBreakpoint: '3000px',
    });

    let venue = document.querySelector('#venue-select');
    venue.addEventListener('change', () => {
        let data = venue.value;
        @this.set('venue', data);
    });

    $wire.on('set-venue', (key) => {
        document.querySelector('#venue-select').setValue(key[0]);
        document.querySelector('#venue-select').disable();
    });

    /* -------------------------------------------------------------------------- */

    $('.from-time').pickatime({
        interval: 1,
        editable: false
    });

    $('.from-time').on('change', function(event) {
        let picker = $(this).pickatime('picker');
        let selectedFromTime = picker.get('select', 'HH:i');
        @this.set('start_time', selectedFromTime);
    });

    $wire.on('set-from-time', (key) => {
        $('.from-time').each(function() {
            let picker = $(this).pickatime('picker'); // Use pickatime instead of pickadate
            picker.clear();
            $('.from-time').attr('disabled', 'disabled');

            picker.set('select', key[0]);
        });
    });

    $('.end-time').pickatime({
        interval: 1,
        editable: false
    });

    $('.end-time').on('change', function(event) {
        let picker = $(this).pickatime('picker');
        let selectedEndTime = picker.get('select', 'HH:i');
        @this.set('end_time', selectedEndTime);
    });

    $wire.on('set-end-time', (key) => {
        $('.end-time').each(function() {
            let picker = $(this).pickatime('picker');
            picker.clear();
            $('.end-time').attr('disabled', 'disabled');

            picker.set('select', key[0]);
        });
    });

    /* -------------------------------------------------------------------------- */

    // tinymce.init({
    //     selector: 'input#myeditorinstance', // Replace this CSS selector to match the placeholder element for TinyMCE
    //     // plugins: 'table lists fullscreen',
    //     // toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | indent outdent | bullist numlist | table | fullscreen',
    //     height: 150,
    //     menubar: false,
    //     toolbar: false,
    //     setup: function(editor) {
    //         // NOTE - This code inlcudes the html tags and the contents.
    //         // editor.on('Change', function(e) {
    //         //     let description = editor.getContent();
    //         //     @this.set('description', description);
    //         // });

    //         // NOTE - This code strips out html tags in our editor. 
    //         editor.on('input', function() {
    //             var plainText = tinymce.activeEditor.getContent({
    //                 format: 'text'
    //             });
    //             document.getElementById('myeditorinstance').value = plainText;
    //             @this.set('description', plainText); // Update Livewire property
    //         });
    //     }
    // });

    $('#summernote_description').summernote({
        toolbar: false,
        tabsize: 2,
        height: 120,
        callbacks: {
            onChange: function(contents, $editable) {
                // Create a temporary div element to strip out HTML tags
                var plainText = $('<div>').html(contents).text();
                @this.set('description', plainText);
            }
        }
    });

    $wire.on('set-description', (key) => {
        $('#summernote_description').summernote('code', key[0]);
        $('#summernote_description').summernote('disable');
    });

    /* -------------------------------------------------------------------------- */

    // Turn input element into a pond with configuration options
    $('.my-pond-attachment').filepond({
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

    $wire.on('refresh-plugin', () => {
        document.querySelector('#status-select').reset();
        document.querySelector('#incoming-category-select').reset();
        document.querySelector('#incoming-category-select').enable();
        $('.request-date').each(function() {
            $(this).pickadate('picker').clear();
            $(this).removeAttr('disabled');
        });
        $('.from-time').each(function() {
            $(this).pickatime('picker').clear();
            $(this).removeAttr('disabled');
        });
        $('.end-time').each(function() {
            $(this).pickatime('picker').clear();
            $(this).removeAttr('disabled');
        });
        $('#summernote_description').each(function() {
            $(this).summernote('reset');
            $(this).summernote('enable');
        });
        document.querySelector('#category-select').reset();
        document.querySelector('#category-select').enable();
        document.querySelector('#venue-select').reset();
        document.querySelector('#venue-select').enable();
    });
</script>
@endscript