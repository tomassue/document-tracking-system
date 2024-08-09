<div>
    <div class="content-wrapper">
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <h4 class="card-title">Requests</h4>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-11">
                                <input type="text" class="form-control" id="exampleInputSearch" placeholder="Search" wire:model.live="search">
                            </div>
                            <div class="col-md-1 text-end">
                                <button type="button" class="btn btn-inverse-success btn-icon" wire:click="$dispatch('show-documentsModal')">
                                    <i class="mdi mdi mdi-plus"></i>
                                </button>
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
                                        <th class="fw-bold">Status</th>
                                        <th class="fw-bold text-center">Details</th>
                                        <th class="fw-bold text-center">History</th>
                                        <th class="fw-bold">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="7" class="text-center">No data</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">
                            <!-- PAGINATION HERE -->
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
</div>