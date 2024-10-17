<div>
    @include('loading-spinner.load-spinner')
    <div class="content-wrapper" @if($page_type=='dashboard' ) style="padding-bottom: 0px;" @endif>
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <h4 class="card-title">Requests</h4>
                            </div>
                        </div>

                        <div style="display: {{ $page_type == 'dashboard' ? 'none' : 'block' }}">
                            <div class="row g-2 mb-2">
                                <div class="col-md-11">
                                    <input type="text" class="form-control" id="exampleInputSearch" placeholder="Search" wire:model.live="search">
                                </div>
                                <div class="col-md-1 text-end">
                                    <button type="button" class="btn btn-success btn-icon" wire:click="openRequestModal">
                                        <i class="mdi mdi mdi-plus"></i>
                                    </button>
                                </div>
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

                        <!-- CPSO -->
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th class="fw-bold">Request Date</th>
                                        <th class="fw-bold">Return Date</th>
                                        <th class="fw-bold">Office/Barangay/Organization</th>
                                        <th class="fw-bold">Category</th>
                                        <th class="fw-bold">Sub-category</th>
                                        <th class="fw-bold text-center">Status</th>
                                        <th class="fw-bold text-center">Details</th>
                                        <th class="fw-bold">History</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($incoming_requests_cpso as $item)
                                    <tr wire:key="{{ $item->id }}">
                                        <td>{{ $item->request_date }}</td>
                                        <td>{{ $item->return_date }}</td>
                                        <td>{{ $item->office_or_barangay_or_organization }}</td>
                                        <td class="text-capitalize">{{ $item->category }}</td>
                                        <td class="text-capitalize">{{ $item->venue }}</td>
                                        <td class="text-center text-uppercase">
                                            <span class="badge badge-pill 
                                            @if($item->status == 'pending')
                                            badge-danger
                                            @elseif($item->status == 'processed')
                                            badge-warning
                                            @elseif($item->status == 'forwarded')
                                            badge-dark
                                            @elseif($item->status == 'completed')
                                            badge-success
                                            @elseif($item->status == 'cancelled')
                                            badge-secondary
                                            @endif
                                            ">
                                                {{ $item->status }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span role="button" wire:click="edit('{{ $item->id }}')">
                                                <i class="mdi mdi-file icon-md"></i>
                                            </span>
                                        </td>
                                        <td>
                                            <span role="button" wire:click="history('{{ $item->id }}')">
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
                            {{ $incoming_requests_cpso->links() }}
                        </div>
                        <!-- CPSO -->

                    </div>
                </div>
            </div>
        </div>
        <!-- row end -->
    </div>
    <!-- content-wrapper ends -->

    @include('livewire.history_modal.history_modal')
    @include('livewire.CPSO.incoming.cpso_modals.requests_cpso')
</div>

@script
<script>
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
            },
            {
                label: 'Cancelled',
                value: 'cancelled'
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
        options: @json($categories)
    });

    let filter_category = document.querySelector('#filter_category_select');
    filter_category.addEventListener('change', () => {
        let data = filter_category.value;
        @this.set('filter_category', data);
    });

    /* ------------------------------- END FILTER ------------------------------- */
</script>
@endscript