<div>
    <div class="content-wrapper" @if($page_type=='dashboard' ) style="padding-top: 0px;" @endif>
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <h4 class="card-title">Documents</h4>
                            </div>
                        </div>

                        <div style="display: {{ $page_type == 'dashboard' ? 'none' : 'block' }}">
                            <div class="row mb-2">
                                <div class="col-md-11">
                                    <input type="text" class="form-control" id="exampleInputSearch" placeholder="Search" wire:model.live="search">
                                </div>
                                <div class="col-md-1 text-end">
                                    <button type="button" class="btn btn-inverse-success btn-icon" wire:click="show_documentsModal">
                                        <i class="mdi mdi mdi-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- CPSO -->
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
                                        <td class="text-capitalize">{{ $item->incoming_document_category }}</td>
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
                                            <span role="button" wire:click="viewDetails('{{ $item->document_no }}')">
                                                <i class="mdi mdi-file icon-md"></i>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span role="button" wire:click="history('{{ $item->document_no }}')">
                                                <i class="mdi mdi-history icon-md"></i>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span role="button" wire:click="edit('{{ $item->document_no }}')">
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
                        <!-- CPSO -->

                    </div>
                </div>
            </div>
        </div>
        <!-- row end -->
    </div>
    <!-- content-wrapper ends -->

    @include('livewire.incoming.cpso_modals.documents_cpso')
    @include('livewire.history_modal.history_modal')
</div>