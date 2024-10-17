<div>
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col-md-6">
                                    <h4 class="card-title">Category</h4>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-11">
                                    <input type="text" class="form-control" id="exampleInputSearch" placeholder="Search" wire:model.live="search">
                                </div>
                                <div class="col-md-1 text-end">
                                    <button type="button" class="btn btn-success btn-icon" wire:click="$dispatch('show-categoryModal')">
                                        <i class="mdi mdi mdi-plus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th class="fw-bold" width="5%">No.</th>
                                            <th class="fw-bold" width="28.3%">Venue</th>
                                            <th class="fw-bold" width="28.3%">Active</th>
                                            <th class="fw-bold" width="10%">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <tr>
                                            <td colspan="4" class="text-center">No data</td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3">
                                <!-- links() -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- row end -->
        </div>
        <!-- content-wrapper ends -->
    </div>
</div>