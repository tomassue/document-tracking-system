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
                        <div class="col-lg-6" style="display: {{ $editMode ? '' : 'none' }}">
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label">Status</label>
                                <div class="col-lg-9">
                                    <div id="status-select" wire:ignore></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 {{ ($status == 'completed' && $show_return_date) ? 'custom-input-bg' : '' }}" style="display: {{ (($status == 'completed' && $show_return_date) || $show_return_date) ? '' : 'none' }}; pointer-events: {{ ($status == 'completed' && $show_return_date) ? '' : 'none' }};">
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label">Return Date</label>
                                <div class="col-lg-9">
                                    <div wire:ignore>
                                        <input class="form-control return-date-for-equipments-and-vehicle" placeholder="" />
                                    </div>
                                    <span class="text-muted" style="font-size: smaller;">Make sure to input here the return date before updating the status to DONE.</span>
                                    @error('return_date_for_equipment_and_vehicle') <span class="custom-invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6" style="display: {{ $editMode  ? '' : 'none' }}">
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label">Remarks/Notes</label>
                                <div class="col-lg-9">
                                    <div wire:ignore>
                                        <div id="summernote_notes"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr style="display: {{ $editMode ? 'block' : 'none' }}">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label text-break"
                                    data-toggle="tooltip"
                                    title="Office/Barangay/Organization"
                                    style="padding-top: 0px; padding-bottom: 0px;">
                                    Office/Brgy/Org
                                </label>
                                <div class="col-lg-9">
                                    <input type="text" class="form-control" wire:model="office_barangay_organization" {{ $editMode ? 'disabled' : '' }}>
                                    @error('office_barangay_organization') <span class="custom-invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label">Date</label>
                                <div class="col-lg-4 {{ $editMode ? '' : 'custom-input-bg' }}">
                                    <div wire:ignore>
                                        <input class="form-control request-date" placeholder="" />
                                    </div>
                                    @error('request_date') <span class="custom-invalid-feedback">{{ $message }}</span> @enderror
                                </div>

                                <div class="col-lg-4 {{ $editMode ? '' : 'custom-input-bg' }}" style="display: {{ $category == '9' ? '' : 'none' }};">
                                    <div wire:ignore>
                                        <input class="form-control return-date" placeholder="" />
                                    </div>
                                    @error('return_date') <span class="custom-invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label">Category</label>
                                <div class="col-lg-9">
                                    <div id="category-select" wire:ignore></div>
                                    <div style="display: {{ $category == '9' ? '' : 'none' }}" class="mt-2">
                                        <div id="venue-select" wire:ignore></div>
                                        @error('venue') <span class="custom-invalid-feedback">{{ $message }}</span> @enderror
                                    </div>
                                    @error('category') <span class="custom-invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6 {{ $editMode ? '' : 'custom-input-bg' }}">
                            <div class="form-group row" style="pointer-events: {{ $editMode ? 'none' : '' }};">
                                @php
                                $timeError = $errors->first('start_time') ?: $errors->first('end_time');
                                @endphp

                                <label class="col-lg-3 col-form-label">Time</label>
                                <div class="col-lg-4">
                                    <div wire:ignore>
                                        <input class="form-control flatpickr-start-time" placeholder="Start">
                                    </div>
                                    @if ($timeError)
                                    <span class="custom-invalid-feedback">{{ $timeError }}</span>
                                    @endif
                                </div>
                                <div class="col-lg-4" wire:ignore>
                                    <input class="form-control flatpickr-end-time" placeholder="End">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label">Contact Person</label>
                                <div class="col-lg-9">
                                    <input type="text" class="form-control" wire:model="contact_person" {{ $editMode ? 'disabled' : '' }}>
                                    @error('contact_person') <span class="custom-invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label">Contact Number</label>
                                <div class="col-lg-9">
                                    <input type="text" class="form-control" oninput="this.value = '09' + this.value.slice(2);" placeholder="09XXXXXXXXX" wire:model="contact_person_number" {{ $editMode ? 'disabled' : '' }}>
                                    @error('contact_person_number') <span class="custom-invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
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
                        <div class="col-lg-12">
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
                                <div class="col-md-6" style="height: 560px; overflow: auto;">
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
                                                        <button type="button" class="btn btn-dark btn-rounded btn-icon" style="display: {{ $file_data && ($file_id == $file->id) ? 'none' : 'inline-block' }}" wire:click="$dispatch('preview-attachment', { key: {{ $file->id }} } )">
                                                            <i class="mdi mdi mdi-eye"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-dark btn-rounded btn-icon" style="display: {{ $file_data && ($file_id == $file->id) ? 'inline-block' : 'none' }}" wire:click="clearFileData">
                                                            <i class="mdi mdi-eye-off"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="4" class="text-center">No files found.</td>
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
                <button type="submit" class="btn btn-primary" wire:loading.attr="disabled" style="display: {{ $hide_button_if_completed ? 'none' : '' }}">{{ $editMode ? 'Update' : 'Save' }}</button>
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
                label: 'Completed',
                value: 'completed'
            },
            {
                label: 'Cancelled',
                value: 'cancelled'
            }
        ],
        maxWidth: '100%',
        zIndex: 10,
        // popupDropboxBreakpoint: '3000px',
    });

    let status = document.querySelector('#status-select');
    status.addEventListener('change', () => {
        let data = status.value;
        @this.set('status', data);
    });

    $wire.on('set-status-enable', (key) => {
        document.querySelector('#status-select').enable();
        document.querySelector('#status-select').setValue(key[0]);
    });

    $wire.on('set-status-disabled', (key) => {
        document.querySelector('#status-select').disable();
        document.querySelector('#status-select').setValue(key[0]);
    });

    // This is for categories like equipment and vehicle. The input field will appear when user selects done.
    $('.return-date-for-equipments-and-vehicle').pickadate({
        klass: {
            holder: 'picker__holder',
        }
    });

    // Handling Pickadate (.return-date-for-equipments-and-vehicle) change event
    $('.return-date-for-equipments-and-vehicle').on('change', function(event) {
        let picker = $(this).pickadate('picker');
        let selectedDate = picker.get('select', 'yyyy-mm-dd'); // Adjust format as needed
        @this.set('return_date_for_equipment_and_vehicle', selectedDate);
    });

    $wire.on('reset-return_date_for_equipment_and_vehicle', () => {
        $('.return-date-for-equipments-and-vehicle').each(function() {
            let picker = $(this).pickadate('picker'); //NOTE - clear out the values
            picker.clear();
        });
    });

    $('#summernote_notes').summernote({
        toolbar: false,
        disableDragAndDrop: true,
        tabsize: 2,
        height: 120,
        callbacks: {
            onChange: function(contents, $editable) {
                // Create a temporary div element to strip out HTML tags
                var plainText = $('<div>').html(contents).text();
                @this.set('notes', plainText);
            }
        }
    });

    $wire.on('set-notes', (key) => {
        $('#summernote_notes').summernote('code', key[0]);
        $('#summernote_notes').summernote('disable');
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

    $('.return-date').pickadate({
        klass: {
            holder: 'picker__holder',
        }
    });

    // Handling Pickadate (.return-date) change event
    $('.return-date').on('change', function(event) {
        let picker = $(this).pickadate('picker');
        let selectedDate = picker.get('select', 'yyyy-mm-dd'); // Adjust format as needed
        @this.set('return_date', selectedDate);
    });

    $wire.on('set-return-date', (key) => {
        $('.return-date').each(function() {
            let picker = $(this).pickadate('picker'); //NOTE - clear out the values
            picker.clear();
            $('.return-date').attr('disabled', 'disabled');

            let return_date_key = key[0]; //NOTE - unset it from an array (key[0]);
            picker.set('select', return_date_key, {
                format: 'yyyy-mm-dd'
            }); //NOTE - you need the format, so that it will be correctly displayed in the input field.
        });

        $('.return-date-for-equipments-and-vehicle').each(function() {
            let picker = $(this).pickadate('picker'); //NOTE - clear out the values
            picker.clear();
            $('.return-date-for-equipments-and-vehicle').attr('disabled', 'disabled');

            let return_date_key = key[0]; //NOTE - unset it from an array (key[0]);
            picker.set('select', return_date_key, {
                format: 'yyyy-mm-dd'
            }); //NOTE - you need the format, so that it will be correctly displayed in the input field.
        });
    });

    /* -------------------------------------------------------------------------- */

    VirtualSelect.init({
        ele: '#category-select',
        options: @json($categories),
        maxWidth: '100%',
        zIndex: 10,
        // popupDropboxBreakpoint: '3000px',
    });

    let category = document.querySelector('#category-select');
    category.addEventListener('change', () => {
        let data = category.value;
        @this.set('category', data);
    });

    $wire.on('set-category', (key) => {
        document.querySelector('#category-select').disable();
        document.querySelector('#category-select').setValue(key[0]); //NOTE - a shorter code of what we did in #category-select (Edit Mode)
    });

    /* -------------------------------------------------------------------------- */

    VirtualSelect.init({
        ele: '#venue-select',
        placeholder: 'Select venue',
        options: @json($venues),
        maxWidth: '100%',
        zIndex: 10,
        // popupDropboxBreakpoint: '3000px',
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

    var startPicker = $(".flatpickr-start-time").flatpickr({
        enableTime: true,
        noCalendar: true,
        dateFormat: "h:i K", // display in 12-hour format
        onChange: function(selectedDates, dateStr, instance) {
            if (selectedDates.length > 0) { // Check if there is a selected date
                let timeIn24HourFormat = instance.formatDate(selectedDates[0], "H:i");
                @this.set('start_time', timeIn24HourFormat);
            } else {
                // console.warn("No date selected for start time.");
            }
        }
    });

    var endPicker = $('.flatpickr-end-time').flatpickr({
        enableTime: true,
        noCalendar: true,
        dateFormat: "h:i K",
        onChange: function(selectedDates, dateStr, instance) {
            if (selectedDates.length > 0) { // Check if there is a selected date
                let timeIn24HourFormat = instance.formatDate(selectedDates[0], "H:i");
                @this.set('end_time', timeIn24HourFormat);
            } else {
                // console.warn("No date selected for end time.");
            }
        }
    });

    $wire.on('set-start-time', (key) => {
        // Update the Flatpickr instance with the new default date/time
        startPicker.setDate(key, true); // Set the new default time and ensure formatting is applied. The div of this element is disabled through setting the pointer-events to none.
    });

    $wire.on('set-end-time', (key) => {
        // Update the Flatpickr instance with the new default date/time
        endPicker.setDate(key, true); // Set the new default time and ensure formatting is applied. The div of this element is disabled through setting the pointer-events to none.
    });

    /* -------------------------------------------------------------------------- */

    $('#summernote_description').summernote({
        toolbar: false,
        disableDragAndDrop: true,
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

    // Register the plugin 
    FilePond.registerPlugin(FilePondPluginFileValidateType); // for file type validation
    FilePond.registerPlugin(FilePondPluginFileValidateSize); // for file size validation

    // Turn input element into a pond with configuration options
    $('.my-pond-attachment').filepond({
        // required: true,
        allowFileTypeValidation: true,
        acceptedFileTypes: ['application/pdf'],
        labelFileTypeNotAllowed: 'File of invalid type',
        allowFileSizeValidation: true,
        maxFileSize: '10MB',
        labelMaxFileSizeExceeded: 'File is too large',
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
        $('.request-date').each(function() {
            $(this).pickadate('picker').clear();
            $(this).removeAttr('disabled');
        });
        $('.return-date').each(function() {
            $(this).pickadate('picker').clear();
            $(this).removeAttr('disabled');
        });
        $('.return-date-for-equipments-and-vehicle').each(function() {
            $(this).pickadate('picker').clear();
            $(this).removeAttr('disabled');
        });
        $('#summernote_description').each(function() {
            $(this).summernote('reset');
            $(this).summernote('enable');
        });
        $('#summernote_notes').each(function() {
            $(this).summernote('reset');
            $(this).summernote('enable');
        });
        $('.my-pond-attachment').each(function() {
            $(this).filepond('removeFiles');
        });
        document.querySelector('#category-select').reset();
        document.querySelector('#category-select').enable();
        document.querySelector('#venue-select').reset();
        document.querySelector('#venue-select').enable();

        startPicker.clear();
        endPicker.clear();
    });
</script>
@endscript