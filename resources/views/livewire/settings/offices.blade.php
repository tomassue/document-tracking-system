<div>
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body" wire:ignore>
                            <div class="row mb-2">
                                <div class="col-md-6">
                                    <h4 class="card-title">Offices</h4>
                                </div>
                                <div class="col-md-6 text-end">
                                    <button type="button" class="btn btn-inverse-success btn-icon" wire:click="$dispatch('show-officeModal')">
                                        <i class="mdi mdi mdi-plus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table id="officesSettingsTable" class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th class="fw-bold" width="5%">No.</th>
                                            <th class="fw-bold">Office</th>
                                            <th class="fw-bold">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>Row 1 Data 2</td>
                                            <td>Row 1 Data 2</td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td>Row 1 Data 2</td>
                                            <td>Row 1 Data 2</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- row end -->
        </div>
        <!-- content-wrapper ends -->
    </div>

    <!-- officeModal -->
    <div class="modal fade" id="officeModal" tabindex="-1" aria-labelledby="officeModalLabel" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="officeModalLabel">Add Office</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="forms-sample" data-bitwarden-watching="1" wire:submit="add">
                        <div class="form-group">
                            <label for="exampleInputOffice">Office</label>
                            <input type="text" class="form-control" id="exampleInputOffice" placeholder="Input office" wire:model="office_name">
                            @error('office_name') <div class="custom-invalid-feedback"> {{ $message }} </div> @enderror
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
                </form>
            </div>
        </div>
    </div>

</div>

@script
<script>
    $(document).ready(function() {
        $('#officesSettingsTable').DataTable();
    });

    $wire.on('show-officeModal', () => {
        $('#officeModal').modal('show');
    })
</script>
@endscript