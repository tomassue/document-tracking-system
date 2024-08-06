<div>
    <div class="content-wrapper">
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <h4 class="card-title">Requests</h4>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-11">
                                <input type="text" class="form-control" id="exampleInputSearch" placeholder="Search" wire:model.live="search">
                            </div>
                            <div class="col-md-1 text-end">
                                <button type="button" class="btn btn-inverse-success btn-icon" wire:click="$dispatch('show-requestModal')">
                                    <i class="mdi mdi mdi-plus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th class="fw-bold">Request Date</th>
                                        <th class="fw-bold">Office/Barangay/Organization</th>
                                        <th class="fw-bold">Category</th>
                                        <th class="fw-bold">Sub-category</th>
                                        <th class="fw-bold text-center">Status</th>
                                        <th class="fw-bold text-center">Details</th>
                                        <th class="fw-bold">History</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($incoming_requests as $item)
                                    <tr wire:key="{{ $item->id }}">
                                        <td>{{ $item->request_date }}</td>
                                        <td>{{ $item->office_or_barangay_or_organization }}</td>
                                        <td class="text-capitalize">{{ $item->category }}</td>
                                        <td class="text-capitalize">{{ $item->venue }}</td>
                                        <td class="text-center text-uppercase">
                                            <span class="badge badge-pill 
                                            @if($item->status == 'pending')
                                            badge-success
                                            @elseif($item->status == 'processed')
                                            badge-warning
                                            @elseif($item->status == 'forwarded')
                                            badge-dark
                                            @elseif($item->status == 'done')
                                            badge-success
                                            @endif
                                            ">
                                                {{ $item->status }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span role="button" wire:click="$dispatch('edit-mode', { key: '{{ $item->id }}' })">
                                                <i class="mdi mdi-file icon-md"></i>
                                            </span>
                                        </td>
                                        <td>No data</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No data</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- row end -->
    </div>
    <!-- content-wrapper ends -->

    <!-- /* -------------------------------------------------------------------------- */ -->

    <!-- requestModal -->
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
                            Personal info
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
                        </div>
                        <hr>
                        <div class="row pt-5">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    @error('office_barangay_organization') <span class="custom-invalid-feedback">{{ $message }}</span> @enderror
                                    <label class="col-sm-3 col-form-label" style="padding-top: 0px;padding-bottom: 0px;">Office/Barangay/Organization</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" wire:model="office_barangay_organization">
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
                                @if ($editMode == false)
                                <div class="form-group row">
                                    @error('attachment') <span class="custom-invalid-feedback">{{ $message }}</span> @enderror
                                    <label class="col-sm-2 col-form-label">Attachment</label>
                                    <div class="col-sm-10" wire:ignore>
                                        <input type="file" class="form-control my-pond-attachment" multiple data-allow-reorder="true">
                                    </div>
                                </div>
                                @elseif ($editMode == true)
                                <div class="row">
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
                                                <tr>
                                                    <td>{{ $index+1 }}</td>
                                                    <td>{{ $file->file_name }}</td>
                                                    <td class="text-center">
                                                        <button type="button" class="btn btn-dark btn-rounded btn-icon">
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
                                @endif
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
</div>

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

    /* -------------------------------------------------------------------------- */
    tinymce.init({
        selector: 'input#myeditorinstance', // Replace this CSS selector to match the placeholder element for TinyMCE
        // plugins: 'table lists fullscreen',
        // toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | indent outdent | bullist numlist | table | fullscreen',
        height: 150,
        menubar: false,
        toolbar: false,
        setup: function(editor) {
            // NOTE - This code inlcudes the html tags and the contents.
            // editor.on('Change', function(e) {
            //     let description = editor.getContent();
            //     @this.set('description', description);
            // });

            // NOTE - This code strips out html tags in our editor. 
            editor.on('input', function() {
                var plainText = tinymce.activeEditor.getContent({
                    format: 'text'
                });
                document.getElementById('myeditorinstance').value = plainText;
                @this.set('description', plainText); // Update Livewire property
            });
        }
    });

    //NOTE - Edit Mode (input#myeditorinstance)
    $wire.on('set-myeditorinstance', (key) => {
        tinymce.get("myeditorinstance").setContent(key[0]); //NOTE - We set the content dynamically from the database. We already initialized is so, we only have to setContent().
        // console.log(key[0]);
    });
    /* -------------------------------------------------------------------------- */

    /* -------------------------------------------------------------------------- */
    VirtualSelect.init({
        ele: '#incoming-category-select',
        options: [{
                label: 'Request',
                value: 'request'
            },
            {
                label: 'Meetings',
                value: 'meeting'
            },
            {
                label: 'Training',
                value: 'training'
            },
            {
                label: 'Other',
                value: 'other'
            }
        ],
        maxWidth: '100%',
        zIndex: 10,
        popupDropboxBreakpoint: '3000px',
    });

    let incoming_category = document.querySelector('#incoming-category-select');
    incoming_category.addEventListener('change', () => {
        let data = incoming_category.value;
        @this.set('incoming_category', data);
    });

    //NOTE - EDIT MODE
    $wire.on('set-incoming_category', (key) => {
        document.querySelector('#incoming-category-select').destroy();

        VirtualSelect.init({
            ele: '#incoming-category-select',
            options: [{
                    label: 'Request',
                    value: 'request'
                },
                {
                    label: 'Meetings',
                    value: 'meeting'
                },
                {
                    label: 'Training',
                    value: 'training'
                },
                {
                    label: 'Other',
                    value: 'other'
                }
            ],
            maxWidth: '100%',
            zIndex: 10,
            popupDropboxBreakpoint: '3000px',
        });
        let incoming_category = key[0]; //NOTE - unset it from the array.
        document.querySelector('#incoming-category-select').setValue(incoming_category);
        // document.querySelector('#incoming-category-select').disable();
        // console.log(incoming_category);
    });
    /* -------------------------------------------------------------------------- */

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

    //NOTE - Edit Mode (category-select)
    $wire.on('set-category', (key) => {
        document.querySelector('#category-select').reset();

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
        document.querySelector('#category-select').setValue(key[0]); //NOTE - a shorter code of what we did in #category-select (Edit Mode)
        // console.log(key[0]);
    });

    // NOTE - This select will be initialized when the event is triggered.
    $wire.on('initialize-venue-select', function() {
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
    });

    // NOTE - An event will be dispatch from the component and triggers this code.
    $wire.on('destroy-venue-select', () => {
        document.querySelector('#venue-select').reset();
        document.querySelector('#venue-select').destroy();
    });

    //NOTE - Edit mode (#venue-select)
    $wire.on('set-venue', (key) => {
        // document.querySelector('#venue-select').reset();

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

        document.querySelector('#venue-select').setValue(key[0]);
        // console.log(key[0]);
    });
    /* -------------------------------------------------------------------------- */

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

    //NOTE - Edit Mode
    $wire.on('set-request-date', (key) => {
        $('.request-date').each(function() {
            let picker = $(this).pickadate('picker'); //NOTE - clear out the values
            picker.clear();

            let request_date_key = key[0]; //NOTE - unset it from an array (key[0]);
            picker.set('select', request_date_key, {
                format: 'yyyy-mm-dd'
            }); //NOTE - you need the format, so that it will be correctly displayed in the input field.
        });
        // console.log(key[0]);
    });
    /* -------------------------------------------------------------------------- */

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

    //NOTE - Edit Mode (.from-time)
    $wire.on('set-from-time', (key) => {
        $('.from-time').each(function() {
            let picker = $(this).pickatime('picker'); // Use pickatime instead of pickadate
            picker.clear();

            let start_time_key = key[0]; // Get the time value from the array
            picker.set('select', start_time_key);
        });
        // console.log(key[0]);
    });
    /* -------------------------------------------------------------------------- */

    /* -------------------------------------------------------------------------- */
    $('.end-time').pickatime({
        interval: 1,
        editable: false
    });

    $('.end-time').on('change', function(event) {
        let picker = $(this).pickatime('picker');
        let selectedEndTime = picker.get('select', 'HH:i');
        @this.set('end_time', selectedEndTime);
    });

    // NOTE - Edit Mode (.end-time)
    $wire.on('set-end-time', (key) => {
        $('.end-time').each(function() {
            let picker = $(this).pickatime('picker');
            picker.clear();

            picker.set('select', key[0]);
        });
        // console.log(key[0]);
    });
    /* -------------------------------------------------------------------------- */

    /* -------------------------------------------------------------------------- */
    // Turn input element into a pond with configuration options
    $('.my-pond-attachment').filepond({
        required: true,
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
    // Clear plugins
    $wire.on('clear-plugins', () => {
        document.querySelector('#incoming-category-select').reset();

        document.querySelector('#category-select').reset();

        tinyMCE.activeEditor.setContent('');

        $('.request-date').each(function() {
            $(this).pickadate('picker').clear();
        });

        $('.from-time').each(function() {
            $(this).pickatime('picker').clear();
        });

        $('.end-time').each(function() {
            $(this).pickatime('picker').clear();
        });

        // Clear FilePond
        $('.my-pond-attachment').each(function() {
            $(this).filepond('removeFiles');
        });
    });
    /* -------------------------------------------------------------------------- */
</script>
@endscript