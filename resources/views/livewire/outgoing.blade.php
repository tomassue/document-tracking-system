<div>
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
                            <div class="row mb-2">
                                <div class="col-md-11">
                                    <input type="text" class="form-control" id="exampleInputSearch" placeholder="Search" wire:model.live="search">
                                </div>
                                <div class="col-md-1 text-end">
                                    <button type="button" class="btn btn-inverse-success btn-icon" wire:click="$dispatch('show-outgoingModal')">
                                        <i class="mdi mdi mdi-plus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th class="fw-bold">Category</th>
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
                                            <td>{{ $item->document_no }}</td>
                                            <td>No data</td>
                                            <td>No data</td>
                                            <td class="text-center">No data</td>
                                            <td class="text-center">No data</td>
                                            <td class="text-center">
                                                <span role="button" wire:click="">
                                                    <i class="mdi mdi-file icon-md"></i>
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <span role="button" wire:click="">
                                                    <i class="mdi mdi-history icon-md"></i>
                                                </span>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="8" class="text-center">No data</td>
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
                <!-- /* -------------------------------------------------------------------------- */
                /*                                  OUTGOING                                  */
                /* -------------------------------------------------------------------------- */ -->
            </div>
            <!-- row end -->
        </div>
        <!-- content-wrapper ends -->
    </div>
    <!-- main-panel ends -->

    @include('livewire.modals.outgoing-modals')
</div>

@include('livewire.modals.outgoing-modals-payroll-scripts')

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
            label: 'Done',
            value: 'done'
        }, {
            label: 'Processing',
            value: 'processing'
        }, {
            label: 'Forwarded',
            value: 'forwarded'
        }, {
            label: 'Return',
            value: 'return'
        }],
        maxWidth: '100%',
        zIndex: 10,
        popupDropboxBreakpoint: '3000px',
    });

    /* -------------------------------------------------------------------------- */

    VirtualSelect.init({
        ele: '#outgoing-category-select',
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
        maxWidth: '100%',
        zIndex: 10,
        popupDropboxBreakpoint: '3000px',
    });

    let outgoing_category = document.querySelector('#outgoing-category-select');
    outgoing_category.addEventListener('change', () => {
        let data = outgoing_category.value;
        @this.set('outgoing_category', data);
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
        console.log(selectedDate);
    });

    /* -------------------------------------------------------------------------- */

    tinymce.init({
        selector: 'input#document_details', // Replace this CSS selector to match the placeholder element for TinyMCE
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
                document.getElementById('document_details').value = plainText;
                @this.set('document_details', plainText); // Update Livewire property
            });
        }
    });

    /* -------------------------------------------------------------------------- */

    // Turn input element into a pond with configuration options
    $('.documents-my-pond-attachment').filepond({
        // required: true,
        acceptedFileTypes: ['application/pdf'],
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

        document.querySelector('#outgoing_payroll_type_select').reset();

        $('.date').each(function() {
            $(this).pickadate('picker').clear();
        });

        tinyMCE.activeEditor.setContent('');

        // Clear FilePond
        $('.documents-my-pond-attachment').each(function() {
            $(this).filepond('removeFiles');
        });
    });
</script>
@endscript