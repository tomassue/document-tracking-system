<div>
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                <!-- /* -------------------------------------------------------------------------- */
                /*                                  REQUESTS                                  */
                /* -------------------------------------------------------------------------- */ -->
                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Requests</h4>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th class="fw-bold">Request Date</th>
                                            <th class="fw-bold">Office/Barangay/Organization</th>
                                            <th class="fw-bold">Category</th>
                                            <th class="fw-bold">Sub-category</th>
                                            <th class="fw-bold text-center">Status</th>
                                            <th class="fw-bold text-center" width="5%">Details</th>
                                            <th class="fw-bold" width="5%">History</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($incoming_requests_cpso as $item)
                                        <tr wire:key="{{ $item->id }}">
                                            <td>{{ $item->request_date }}</td>
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
                                            @elseif($item->status == 'done')
                                            badge-success
                                            @endif
                                            ">
                                                    {{ $item->status }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <span role="button" wire:click="editIncomingRequests('{{ $item->id }}')">
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
                                            <td colspan="7" class="text-center">No data</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3">
                                {{ $incoming_requests_cpso->links() }}
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /* -------------------------------------------------------------------------- */
                /*                                  REQUESTS                                  */
                /* -------------------------------------------------------------------------- */ -->


                <!-- /* -------------------------------------------------------------------------- */
                /*                                  DOCUMENTS                                 */
                /* -------------------------------------------------------------------------- */ -->
                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Documents</h4>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th class="fw-bold">Category</th>
                                            <th class="fw-bold">Document No.</th>
                                            <th class="fw-bold">Document Details</th>
                                            <th class="fw-bold text-center">Status</th>
                                            <th class="fw-bold text-center" width="5%">Details</th>
                                            <th class="fw-bold text-center" width="5%">History</th>
                                            <th class="fw-bold text-center" width="5%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($incoming_documents as $item)
                                        <tr wire:key="{{ $item->document_no }}">
                                            <td class="text-capitalize">{{ $item->category }}</td>
                                            <td>{{ $item->document_no }}</td>
                                            <td>{{ $item->document_info }}</td>
                                            <td class="text-center text-uppercase">
                                                <span class="badge badge-pill 
                                            @if($item->status == 'pending')
                                            badge-danger
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
                                                <span role="button" wire:click="details('{{  $item->document_no }}')">
                                                    <i class="mdi mdi-file icon-md"></i>
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <span role="button" wire:click="history('{{ $item->document_no }}')">
                                                    <i class="mdi mdi-history icon-md"></i>
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <span role="button" wire:click="editIncomingDocuments('{{ $item->document_no }}')">
                                                    <i class="mdi mdi-pencil icon-md"></i>
                                                </span>
                                            </td>
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
                                {{ $incoming_documents->links() }}
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /* -------------------------------------------------------------------------- */
                /*                                  DOCUMENTS                                 */
                /* -------------------------------------------------------------------------- */ -->
            </div>
            <!-- row end -->
        </div>
        <!-- content-wrapper ends -->
    </div>
    <!-- main-panel ends -->

    @include('livewire.modals.dashboard-modals')
    @include('livewire.history_modal.history_modal')
</div>