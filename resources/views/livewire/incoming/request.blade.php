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
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th class="fw-bold">Request Date</th>
                                        <th class="fw-bold">Office/Barangay/Organization</th>
                                        <th class="fw-bold">Category</th>
                                        <th class="fw-bold">Sub-category</th>
                                        <th class="fw-bold">Status</th>
                                        <th class="fw-bold">Details</th>
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
                        <div class="mt-3">

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- row end -->
    </div>
    <!-- content-wrapper ends -->

    <!-- requestModal -->
    <div class="modal fade" id="requestModal" tabindex="-1" aria-labelledby="requestModalLabel" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="requestModalLabel">{{ $editMode ? 'Edit' : 'Add' }} Request</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:click="clear"></button>
                </div>
                <div class="modal-body">
                    <form class="form-sample">
                        <p class="card-description">
                            Personal info
                        </p>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Category</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" placeholder="Request" disabled>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row pt-5">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label" style="padding-top: 0px;padding-bottom: 0px;">Office/Barangay/Organization</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Request Date</label>
                                    <div class="col-sm-9">
                                        <input type="date" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Category</label>
                                    <div class="col-sm-9">
                                        <select class="form-control">
                                            <option>Catgeory</option>
                                            <option>Catgeory</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Time</label>
                                    <div class="col-sm-9">
                                        <input class="form-control" placeholder="dd/mm/yyyy">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Description</label>
                                    <div class="col-sm-9">
                                        <input class="form-control" placeholder="dd/mm/yyyy">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Attachment</label>
                                    <div class="col-sm-9">
                                        <input type="file" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" wire:click="clear">Close</button>
                            <button type="submit" class="btn btn-primary">{{ $editMode ? 'Save Changes' : 'Save' }}</button>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@script
<script>
    $wire.on('show-requestModal', () => {
        $('#requestModal').modal('show');
    });
</script>
@endscript