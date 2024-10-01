<div>
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col-md-6">
                                    <h4 class="card-title">Offices</h4>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-11">
                                    <input type="text" class="form-control" id="exampleInputSearch" placeholder="Search" wire:model.live="search">
                                </div>
                                <div class="col-md-1 text-end">
                                    <button type="button" class="btn btn-inverse-success btn-icon" wire:click="$dispatch('show-officeModal')">
                                        <i class="mdi mdi mdi-plus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th class="fw-bold" width="5%">No.</th>
                                            <th class="fw-bold" width="85%">Office</th>
                                            <th class="fw-bold" width="10%">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($ref_offices as $key=>$item)
                                        <tr wire:key="{{ $item->id }}">
                                            <td>{{ $key+1 }}</td>
                                            <td>{{ $item->office_name }}</td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-dark btn-icon-text" wire:click="edit({{ $item->id }})">
                                                    Edit
                                                    <i class="mdi mdi-file-check btn-icon-append"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="3" class="text-center">No data</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3">
                                {{ $ref_offices->links() }}
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
                    <h1 class="modal-title fs-5" id="officeModalLabel">{{ $editMode ? 'Edit' : 'Add' }} Office</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:click="clear"></button>
                </div>
                <div class="modal-body">
                    <form class="forms-sample" data-bitwarden-watching="1" wire:submit="{{ $editMode ? 'update' : 'add' }}">
                        <div class="form-group">
                            <label for="exampleInputOffice">Office</label>
                            <input type="text" class="form-control" id="exampleInputOffice" placeholder="Input office" wire:model="office_name">
                            @error('office_name') <div class="custom-invalid-feedback"> {{ $message }} </div> @enderror
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" wire:click="clear">Close</button>
                    <button type="submit" class="btn btn-primary">{{ $editMode ? 'Save Changes' : 'Save' }}</button>
                </div>
                </form>
            </div>
        </div>
    </div>

</div>

@script
<script>
    $wire.on('show-officeModal', () => {
        $('#officeModal').modal('show');
    })

    $wire.on('show-officeModal-edit', () => {
        $('#officeModal').modal('show');
    })

    $wire.on('hide-officeModal', () => {
        $('#officeModal').modal('hide');
    })
</script>
@endscript