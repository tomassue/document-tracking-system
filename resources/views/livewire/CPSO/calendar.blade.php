<div>
    @include('loading-spinner.load-spinner')
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

    @include('livewire.CPSO.modals.calendar-modals')
</div>

@script
<script>
    document.addEventListener('livewire:initialized', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            timeZone: 'local',
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

            // // Customize the time display format to show both start and end times
            // eventTimeFormat: {
            //     hour: 'numeric',
            //     minute: '2-digit',
            //     meridiem: 'short'
            // },

            // eventContent: function(arg) {
            //     let startTime = FullCalendar.formatDate(arg.event.start, {
            //         hour: 'numeric',
            //         minute: '2-digit',
            //         meridiem: 'short'
            //     });
            //     let endTime = FullCalendar.formatDate(arg.event.end, {
            //         hour: 'numeric',
            //         minute: '2-digit',
            //         meridiem: 'short'
            //     });

            //     // Combine the start and end time in the display
            //     let timeHtml = startTime + ' - ' + endTime;

            //     return {
            //         html: '<div class="fc-event-time">' + timeHtml + '</div><div class="fc-event-title">' + arg.event.title + '</div>'
            //     };
            // },

            eventContent: function(arg) {
                let startTime = FullCalendar.formatDate(arg.event.start, {
                    hour: 'numeric',
                    minute: '2-digit',
                    meridiem: 'short'
                });
                let endTime = FullCalendar.formatDate(arg.event.end, {
                    hour: 'numeric',
                    minute: '2-digit',
                    meridiem: 'short'
                });

                // Combine the start and end time in the display
                let timeHtml = `${startTime} - ${endTime}`;

                // Return the HTML structure with the dot and background color
                return {
                    html: `
                        <div class="fc-event-main" style="background-color: ${arg.event.backgroundColor}; color: #fff; padding: 5px; border-radius: 4px; overflow: hidden; display: flex; align-items: top;">
                            <div class="fc-event-dot" style="margin-right: 5px;"></div>
                            <div class="fc-event-time" style="margin-right: 10px;">${timeHtml}</div>
                            <div class="fc-event-title">${arg.event.title}</div>
                        </div>
                    `
                };
            },

            eventDidMount: function(info) {
                // This ensures that the dot (event indicator) is visible
                const dotEl = info.el.querySelector('.fc-event-dot');
                if (dotEl) {
                    dotEl.style.display = 'inline-block'; // Ensure the dot is displayed
                }
            },

            eventClick: function(info) {
                // Trigger Livewire event to show details
                $wire.dispatch('show-details', {
                    key: info.event.id
                });
            },

            dateClick: function(info) {
                // Change to dayGridDay view on date click
                calendar.changeView('timeGridDay', info.dateStr);
            }
        });

        calendar.render();

        $wire.on('filter-calendar', function(incoming_request) {
            try {
                const events = incoming_request[0]; // Destructure the nested array
                calendar.removeAllEvents();
                calendar.addEventSource(events);
                calendar.refetchEvents();
                // console.log('refreshed');
                console.log(events);
            } catch (e) {
                console.error('Error parsing meetings data', e)
            }
        });

        /* -------------------------------------------------------------------------- */

        VirtualSelect.init({
            ele: '#venue',
            placeholder: 'Venue (all)',
            options: @json($filter_venues),
            zIndex: 10,
            // popupDropboxBreakpoint: '3000px',
        });

        let venue = document.querySelector('#venue');
        venue.addEventListener('change', () => {
            let data = venue.value;
            @this.set('venue', data);
        });

        document.querySelector('#venue').setValue('1');

        /* -------------------------------------------------------------------------- */

        $wire.on('show-details', () => {
            $('#viewDetailsModal').modal('show');
        });
    });
</script>
@endscript