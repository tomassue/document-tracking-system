@script
<script>
    /* -------------------------------------------------------------------------- */
    /*                               documentsModal                               */
    /* -------------------------------------------------------------------------- */

    $wire.on('show-documentsModal', () => {
        $('#documentsModal').modal('show');
    });

    $wire.on('hide-documentsModal', () => {
        $('#documentsModal').modal('hide');
    });

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

    // NOTE - Edit Mode
    $wire.on('set-incoming-category-documents-select', (key) => {
        document.querySelector('#incoming-category-documents-select').setValue(key[0]);
        // console.log(key[0]);
    });

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
            @this.set('incoming_document_status', data);
        });

        document.querySelector('#document-status-select').setValue(key[0]);
    });

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
        @this.set('incoming_document_date', selectedDate);
    });

    // NOTE - Edit Mode
    $wire.on('set-document-incoming-date', (key) => {
        $('.document-incoming-date').each(function() {
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
</script>
@endscript