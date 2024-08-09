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
                                    <input type="text" class="form-control" placeholder="Display here the system generated doc no." disabled>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Request Date</label>
                                <div class="col-sm-9" wire:ignore>
                                    <input class="form-control document-incoming-date" required></input>
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
                                    <input type="file" class="form-control documents-my-pond-attachment" multiple data-allow-reorder="true">
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

    /* -------------------------------------------------------------------------- */

    VirtualSelect.init({
        ele: '#incoming-category-documents-select',
        options: [{
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

    let incoming_document_category = document.querySelector('#incoming-category-documents-select');
    incoming_document_category.addEventListener('change', () => {
        let data = incoming_document_category.value;
        @this.set('incoming_document_category', data);
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

        console.log('cleared');
    });

    /* -------------------------------------------------------------------------- */
</script>
@endscript