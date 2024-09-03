@script
<script>
    /* -------------------------------------------------------------------------- */
    /*                                requestModal                                */
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
        @this.set('incoming_request_category', data);
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
    //NOTE - Edit mode (status-select). Status select will only be initialized during editMode.
    $wire.on('set-status', (key) => {
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
            @this.set('incoming_request_status', data);
        });

        document.querySelector('#status-select').setValue(key[0]);
        // console.log(key[0]);
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
        @this.set('incoming_request_category_2', data);
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
            @this.set('incoming_request_venue', data);
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
        @this.set('incoming_request_date', selectedDate);
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
        @this.set('incoming_request_start_time', selectedFromTime);
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
        @this.set('incoming_request_end_time', selectedEndTime);
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
</script>
@endscript