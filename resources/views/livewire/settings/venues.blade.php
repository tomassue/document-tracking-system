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
                                    <button type="button" class="btn btn-success btn-icon" wire:click="$dispatch('show-venueModal')">
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
                                        @forelse($venues as $index=>$item)
                                        <tr wire:key="{{ $item->id }}">
                                            <td class="small-td">{{ $index+1 }}</td>
                                            <td class="small-td">{{ $item->venue }}</td>
                                            <td class="small-td">
                                                <span class="badge rounded-pill {{ $item->is_active == 'yes' ? 'badge-success' : 'badge-danger' }} text-uppercase">{{ $item->is_active }}</span>
                                            </td>
                                            <td class="small-td">
                                                <button type="button" class="btn btn-sm btn-dark btn-icon-text" wire:click="edit({{ $item->id }})">
                                                    Edit
                                                    <i class="mdi mdi-file-check btn-icon-append"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="text-center">No data</td>
                                        </tr>
                                        @endforelse
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

    <!-- venueModal -->
    <div class="modal fade" id="venueModal" tabindex="-1" aria-labelledby="venueModalLabel" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="venueModalLabel">{{ $editMode ? 'Edit' : 'Add' }} User</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:click="clear"></button>
                </div>
                <div class="modal-body">
                    <form class="forms-sample" data-bitwarden-watching="1" wire:submit="{{ $editMode ? 'update' : 'add' }}">
                        <div class="form-group">
                            <label for="exampleVenue">Venue</label>
                            <input type="text" class="form-control" id="exampleVenue" placeholder="Input venue" wire:model="venue">
                            @error('venue') <div class="custom-invalid-feedback"> {{ $message }} </div> @enderror
                        </div>
                        <div class="form-group" style="display: {{ $editMode ? 'block' : 'none' }};">
                            <label for="exampleEmail">Is active</label>
                            <div id="is_active_select" wire:ignore></div>
                            @error('is_active') <div class="custom-invalid-feedback"> {{ $message }} </div> @enderror
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
    $wire.on('show-venueModal', () => {
        $('#venueModal').modal('show');
    });

    $wire.on('hide-venueModal', () => {
        $('#venueModal').modal('hide');
    });

    /* -------------------------------------------------------------------------- */

    VirtualSelect.init({
        ele: '#is_active_select',
        options: [{
                label: 'Yes',
                value: 'yes'
            },
            {
                label: 'No',
                value: 'no'
            }
        ],
        maxWidth: '100%'
    });

    let is_active = document.querySelector('#is_active_select');
    is_active.addEventListener('change', () => {
        let data = is_active.value;
        @this.set('is_active', data);
    });

    $wire.on('is_active_edit', (key) => {
        document.querySelector('#is_active_select').setValue(key);
    });
</script>
@endscript