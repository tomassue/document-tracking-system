<div>
    @include('loading-spinner.load-spinner')

    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                <!-- /* -------------------------------------------------------------------------- */
                /*                                  OUTGOING                                  */
                /* -------------------------------------------------------------------------- */ -->
                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Outgoing</h4>
                            <div class="row g-2 mb-2">
                                <div class="col-md-11">
                                    <input type="text" class="form-control" id="exampleInputSearch" placeholder="Search" wire:model.live="search">
                                </div>
                                <div class="col-md-1 text-end">
                                    <button type="button" class="btn btn-success btn-icon" wire:click="show_outgoingModal">
                                        <i class="mdi mdi mdi-plus"></i>
                                    </button>
                                </div>

                                <div class="row g-2 my-2">
                                    <div class="col-md-12 d-flex align-items-center">
                                        <span class="">Filter</span>
                                    </div>

                                    <div class="col-sm-4 col-md-3 col-lg-2">
                                        <div id="filter_category_select" wire:ignore></div>
                                    </div>

                                    <div class="col-sm-4 col-md-3 col-lg-2">
                                        <div id="filter_status_select" wire:ignore></div>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th class="fw-bold">Category</th>
                                            <th class="fw-bold">Date</th>
                                            <th class="fw-bold">Document No.</th>
                                            <th class="fw-bold">Document</th>
                                            <th class="fw-bold">Destination</th>
                                            <th class="fw-bold text-center" width="10%">Person Responsible</th>
                                            <th class="fw-bold text-center" width="5%">Status</th>
                                            <th class="fw-bold" width="5%">Details</th>
                                            <th class="fw-bold" width="5%">History</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($outgoing_documents as $item)
                                        <tr wire:key="{{ $item->document_no }}">
                                            <td>
                                                @if ($item->category_type == 'App\Models\OutgoingCategoryProcurementModel')
                                                <span>Procurement</span>
                                                @elseif ($item->category_type == 'App\Models\OutgoingCategoryPayrollModel')
                                                <span>Payroll</span>
                                                @elseif ($item->category_type == 'App\Models\OutgoingCategoryVoucherModel')
                                                <span>Voucher</span>
                                                @elseif ($item->category_type == 'App\Models\OutgoingCategoryRISModel')
                                                <span>RIS</span>
                                                @elseif ($item->category_type == 'App\Models\OutgoingCategoryOthersModel')
                                                <span>Others</span>
                                                @endif
                                            </td>
                                            <td>{{ $item->date }}</td>
                                            <td>{{ $item->document_no }}</td>
                                            <td>{{ $item->document_details }}</td>
                                            <td>{{ $item->destination }}</td>
                                            <td class="text-center">{{ $item->person_responsible }}</td>
                                            <td class="text-center text-uppercase">
                                                <span class="badge badge-pill 
                                            @if($item->status == 'returned')
                                            badge-danger
                                            @elseif($item->status == 'processing')
                                            badge-warning
                                            @elseif($item->status == 'forwarded')
                                            badge-dark
                                            @elseif($item->status == 'completed')
                                            badge-success
                                            @endif
                                            ">
                                                    {{ $item->status }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <span role="button" wire:click="edit('{{ $item->document_no }}')">
                                                    <i class="mdi mdi-file icon-md"></i>
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <span role="button" wire:click="history('{{ $item->document_no }}')">
                                                    <i class="mdi mdi-history icon-md"></i>
                                                </span>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="9" class="text-center">No data</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3">
                                {{ $outgoing_documents->links() }}
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /* -------------------------------------------------------------------------- */
                /*                                  OUTGOING                                  */
                /* -------------------------------------------------------------------------- */ -->
            </div>
            <!-- row end -->
        </div>
        <!-- content-wrapper ends -->
    </div>
    <!-- main-panel ends -->

    @include('livewire.history_modal.history_modal')
    @include('livewire.CPSO.modals.outgoing-modals')
</div>

@script
<script>
    /* -------------------------------------------------------------------------- */
    /*                              Reusable scripts                              */
    /* -------------------------------------------------------------------------- */

    // NOTE - These scripts here are commonly used regardless of what category the user selected.

    $wire.on('show-outgoingModal', () => {
        $('#outgoingModal').modal('show');
    });

    $wire.on('hide-outgoingModal', () => {
        $('#outgoingModal').modal('hide');
    });

    /* -------------------------------------------------------------------------- */

    VirtualSelect.init({
        ele: '#outgoing-status-select',
        options: [{
            label: 'Processing',
            value: 'processing'
        }, {
            label: 'Forwarded',
            value: 'forwarded'
        }, {
            label: 'Returned',
            value: 'returned'
        }, {
            label: 'Completed',
            value: 'completed'
        }],
        maxWidth: '100%',
        zIndex: 10,
        // popupDropboxBreakpoint: '3000px',
    });

    let status = document.querySelector('#outgoing-status-select');
    status.addEventListener('change', () => {
        let data = status.value;
        @this.set('status', data);
    });

    // NOTE - Edit Mode
    $wire.on('set-outgoing-status-select-enable', (key) => {
        document.querySelector('#outgoing-status-select').enable();
        document.querySelector('#outgoing-status-select').setValue(key[0]);
    });

    $wire.on('set-outgoing-status-select-disable', (key) => {
        document.querySelector('#outgoing-status-select').disable();
        document.querySelector('#outgoing-status-select').setValue(key[0]);
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

    $wire.on('set_notes-enabled', () => {
        $('#summernote_notes').summernote('enable');
    })

    /* -------------------------------------------------------------------------- */

    VirtualSelect.init({
        ele: '#outgoing-category-select',
        options: @json($categories),
        maxWidth: '100%',
        zIndex: 10,
        // popupDropboxBreakpoint: '3000px',
    });

    let outgoing_category = document.querySelector('#outgoing-category-select');
    outgoing_category.addEventListener('change', () => {
        let data = outgoing_category.value;
        @this.set('outgoing_category', data);
    });

    //NOTE - Edit Mode
    $wire.on('set-outgoing-category-select', (key) => {
        document.querySelector('#outgoing-category-select').setValue(key[0]);
        document.querySelector('#outgoing-category-select').disable();
    });

    /* -------------------------------------------------------------------------- */

    $('.date').pickadate({
        klass: {
            holder: 'picker__holder',
        }
    });

    $('.date').on('change', function(event) {
        let picker = $(this).pickadate('picker');
        let selectedDate = picker.get('select', 'yyyy-mm-dd');
        @this.set('date', selectedDate);
        // console.log(selectedDate);
    });

    // NOTE - Edit Mode
    $wire.on('set-date', (key) => {
        $('.date').each(function() {
            let picker = $(this).pickadate('picker'); //NOTE - clear out the values
            picker.clear();
            $('.date').attr('disabled', 'disabled'); // NOTE - disables the input field date

            let date_key = key[0]; //NOTE - unset it from an array (key[0]);
            picker.set('select', date_key, {
                format: 'yyyy-mm-dd'
            }); //NOTE - you need the format, so that it will be correctly displayed in the input field.
        });
        // console.log(key[0]);
    });

    /* -------------------------------------------------------------------------- */

    // tinymce.init({
    //     selector: 'input#document_details', // Replace this CSS selector to match the placeholder element for TinyMCE
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
    //             document.getElementById('document_details').value = plainText;
    //             @this.set('document_details', plainText); // Update Livewire property
    //         });
    //     }
    // });

    // // NOTE - Edit Mode
    // $wire.on('set-document_details', (key) => {
    //     tinymce.activeEditor.getBody().setAttribute('contenteditable', false);
    //     tinymce.get("document_details").setContent(key[0]); //NOTE - We set the content dynamically from the database. We already initialized is so, we only have to setContent().
    //     // console.log(key[0]);
    // });

    $('#document_details').summernote({
        toolbar: false,
        disableDragAndDrop: true,
        tabsize: 2,
        height: 120,
        callbacks: {
            onChange: function(contents, $editable) {
                // Create a temporary div element to strip out HTML tags
                var plainText = $('<div>').html(contents).text();
                @this.set('document_details', plainText);
            }
        }
    });

    $wire.on('set-document_details', (key) => {
        $('#document_details').summernote('code', key[0]);
        $('#document_details').summernote('disable');
    });

    /* -------------------------------------------------------------------------- */

    // Register the plugin 
    FilePond.registerPlugin(FilePondPluginFileValidateType); // for file type validation
    FilePond.registerPlugin(FilePondPluginFileValidateSize); // for file size validation

    // Turn input element into a pond with configuration options
    $('.documents-my-pond-attachment').filepond({
        // required: true,
        allowFileTypeValidation: true,
        acceptedFileTypes: ['application/pdf'],
        labelFileTypeNotAllowed: 'File of invalid type',
        allowFileSizeValidation: true,
        maxFileSize: '10MB',
        labelMaxFileSizeExceeded: 'File is too large',
        server: {
            // This will assign the data to the attachments[] property.
            process: (fieldName, file, metadata, load, error, progress, abort) => {
                @this.upload('attachments', file, load, error, progress);
            },
            revert: (uniqueFileId, load, error) => {
                @this.removeUpload('attachments', uniqueFileId, load, error);
            }
        }
    });

    /* -------------------------------------------------------------------------- */
    /*                              Reusable scripts                              */
    /* -------------------------------------------------------------------------- */


    /* -------------------------------------------------------------------------- */

    // NOTE - when the clear() method's triggered, it will dispatch an event to clear or reset the plug-ins
    $wire.on('clear_plugins', () => {
        document.querySelector('#outgoing-category-select').reset();

        document.querySelector('#outgoing-status-select').reset();

        document.querySelector('#outgoing_payroll_type_select').reset();

        $('.date').each(function() {
            $(this).pickadate('picker').clear();
        });

        $('#document_details').filepond('removeFiles');

        $('#summernote_notes').each(function() {
            $(this).summernote('reset');
        });

        // Clear FilePond
        $('.documents-my-pond-attachment').each(function() {
            $(this).filepond('removeFiles');
        });
    });

    /* -------------------------------------------------------------------------- */

    /**
     * NOTE
     * During editMode, we disable the plug-ins. However, when we prompt the modal again, the plugins remains disabled and it requires the component to be refreshed.
     * To address this, we will fire an event to enable them again when we add a new record.
     */

    $wire.on('enable-plugins', () => {
        document.querySelector('#outgoing-category-select').enable();
        document.querySelector('#outgoing_payroll_type_select').enable();
        $('.date').removeAttr('disabled');
        $('.documents-my-pond-attachment').each(function() {
            $(this).filepond('removeFiles');
        });
        $('#document_details').each(function() {
            $(this).summernote('reset');
            $(this).summernote('enable');
        });
        // tinymce.activeEditor.getBody().setAttribute('contenteditable', true);
    });

    /* --------------------------------- FILTER --------------------------------- */

    VirtualSelect.init({
        ele: '#filter_status_select',
        placeholder: 'Status (All)',
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
            }
        ],
    });

    let filter_status = document.querySelector('#filter_status_select');
    filter_status.addEventListener('change', () => {
        let data = filter_status.value;
        @this.set('filter_status', data);
    });

    VirtualSelect.init({
        ele: '#filter_category_select',
        placeholder: 'Category (All)',
        options: [{
                label: 'Procurement',
                value: 'procurement'
            },
            {
                label: 'Payroll',
                value: 'payroll'
            },
            {
                label: 'Voucher',
                value: 'voucher'
            },
            {
                label: 'RIS',
                value: 'ris'
            },
            {
                label: 'Other',
                value: 'other'
            }
        ],
    });

    let filter_category = document.querySelector('#filter_category_select');
    filter_category.addEventListener('change', () => {
        let data = filter_category.value;
        @this.set('filter_category', data);
    });

    /* ------------------------------- END FILTER ------------------------------- */
</script>
@endscript

@include('livewire.CPSO.modals.outgoing-modals-payroll-scripts')