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
                                <button type="button" class="btn btn-inverse-success btn-icon" wire:click="$dispatch('show-requestModal')">
                                    <i class="mdi mdi mdi-plus"></i>
                                </button>
                            </div>
                        </div>

                        <!-- CPSO -->
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th class="fw-bold">Request Date</th>
                                        <th class="fw-bold">Office/Barangay/Organization</th>
                                        <th class="fw-bold">Category</th>
                                        <th class="fw-bold">Sub-category</th>
                                        <th class="fw-bold text-center">Status</th>
                                        <th class="fw-bold text-center">Details</th>
                                        <th class="fw-bold">History</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="7" class="text-center">No data</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- CPSO -->

                        <!-- OFFICE 2 -->
                        <!-- OFFICE 2 -->

                        <div class="mt-3">

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- row end -->
    </div>
    <!-- content-wrapper ends -->
</div>