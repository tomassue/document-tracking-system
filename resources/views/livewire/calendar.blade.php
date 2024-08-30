<div>
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">

                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div id="venue" wire:ignore></div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card" wire:ignore>
                        <div class="card-body">
                            <div id='calendar'></div>
                        </div>
                    </div>
                </div>

            </div>
            <!-- row end -->
        </div>
        <!-- content-wrapper ends -->
    </div>
    <!-- main-panel ends -->

    @include('livewire.modals.calendar-modals')
</div>

@script
<script>
    document.addEventListener('livewire:initialized', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            timeZone: 'UTC',
            themeSystem: 'bootstrap5',
            initialView: 'dayGridMonth',
            height: 650,
            headerToolbar: {
                left: 'dayGridMonth,listWeek,timeGridWeek,timeGridDay',
                center: 'title',
                right: 'prev,today,next' // user can switch between the two
            },
            selectable: true,
            events: @json($incoming_request),
            eventClick: function(info) {
                // Change to dayGridDay view on date click
                calendar.changeView('timeGridDay', info.dateStr);
            },
            dateClick: function(info) {
                // Change to dayGridDay view on date click
                calendar.changeView('timeGridDay', info.dateStr);
            }
        });

        calendar.render();
    });

    $wire.on('filter-calendar', (incoming_request) => {
        console.log(incoming_request);
    });

    /* -------------------------------------------------------------------------- */

    VirtualSelect.init({
        ele: '#venue',
        placeholder: 'Venue',
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
        zIndex: 10,
        popupDropboxBreakpoint: '3000px',
    });

    let venue = document.querySelector('#venue');
    venue.addEventListener('change', () => {
        let data = venue.value;
        @this.set('venue', data);
    });
</script>
@endscript