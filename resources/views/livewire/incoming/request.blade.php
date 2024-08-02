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
                                        <th class="fw-bold">Status</th>
                                        <th class="fw-bold">Details</th>
                                        <th class="fw-bold">History</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="7" class="text-center">No data</td>
                                    </tr>
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

    <!-- requestModal -->
    <div class="modal fade" id="requestModal" tabindex="-1" aria-labelledby="requestModalLabel" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="requestModalLabel">{{ $editMode ? 'Edit' : 'Add' }} Request</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                                        <input type="text" class="form-control" placeholder="Request" disabled>
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
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">{{ $editMode ? 'Save Changes' : 'Save' }}</button>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@script
<script>
    $wire.on('show-requestModal', () => {
        $('#requestModal').modal('show');
    });

    $wire.on('hide-requestModal', () => {
        $('#requestModal').modal('hide');
    });

    tinymce.init({
        selector: 'input#myeditorinstance', // Replace this CSS selector to match the placeholder element for TinyMCE
        // plugins: 'table lists fullscreen',
        // toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | indent outdent | bullist numlist | table | fullscreen',
        setup: function(editor) {
            editor.on('Change', function(e) {
                let description = editor.getContent();
                @this.set('description', description);
            });
        }
    });

    VirtualSelect.init({
        ele: '#category-select',
        options: [{
                label: 'Equipment',
                value: 'equipment'
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

    $('.from-time').pickatime({
        interval: 1
    });

    $('.from-time').on('change', function(event) {
        let picker = $(this).pickatime('picker');
        let selectedFromTime = picker.get('select', 'HH:i');
        @this.set('start_time', selectedFromTime);
    });

    $('.end-time').pickatime({
        interval: 1
    });

    $('.end-time').on('change', function(event) {
        let picker = $(this).pickatime('picker');
        let selectedEndTime = picker.get('select', 'HH:i');
        @this.set('end_time', selectedEndTime);
    });

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

    // Clear plugins
    $wire.on('clear-plugins', () => {
        document.querySelector('#category-select').reset();

        tinyMCE.activeEditor.setContent('');

        var requestDate = $('.request-date').pickadate();
        var fromTime = $('.from-time').pickatime();
        var endTime = $('.end-time').pickatime();

        requestDate.clear();
        fromTime.clear();
        endTime.clear();

        console.log('Clear');
    });
</script>
@endscript