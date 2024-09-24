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
                                    <button type="button" class="btn btn-inverse-success btn-icon" wire:click="$dispatch('show-categoryModal')">
                                        <i class="mdi mdi mdi-plus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th class="fw-bold" width="5%">No.</th>
                                            <th class="fw-bold" width="28.3%">Category</th>
                                            <th class="fw-bold" width="28.3%">Document Type</th>
                                            <th class="fw-bold" width="28.3%">Active</th>
                                            <th class="fw-bold" width="10%">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($categories as $index=>$item)
                                        <tr wire:key="{{ $item->id }}">
                                            <td>{{ $index+1 }}</td>
                                            <td class="text-capitalize">{{ $item->category }}</td>
                                            <td class="text-capitalize">{{ $item->document_type }}</td>
                                            <td class="text-uppercase">{{ $item->is_active }}</td>
                                            <td>
                                                <button type="button" class="btn btn-dark btn-icon-text" wire:click="edit({{ $item->id }})">
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

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- row end -->
        </div>
        <!-- content-wrapper ends -->
    </div>

    <!-- categoryModal -->
    <div class="modal fade" id="categoryModal" tabindex="-1" aria-labelledby="categoryModalLabel" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="categoryModalLabel">{{ $editMode ? 'Edit' : 'Add' }} Category {{ $id_category }}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:click="clear"></button>
                </div>
                <div class="modal-body">
                    <form class="forms-sample" data-bitwarden-watching="1" wire:submit="{{ $editMode ? 'update' : 'add' }}">
                        <div class="form-group">
                            <label for="exampleInputOffice">Document Type</label>
                            <div id="document-type-select" wire:ignore></div>
                            @error('document_type') <div class="custom-invalid-feedback"> {{ $message }} </div> @enderror
                        </div>
                        <div class="form-group">
                            <label for="exampleInputOffice">Category</label>
                            <input type="text" class="form-control" wire:model="category">
                            @error('category') <div class="custom-invalid-feedback"> {{ $message }} </div> @enderror
                        </div>
                        <div class="form-group">
                            <label for="exampleInputOffice">Is Active?</label>
                            <div id="is-active-select" wire:ignore></div>
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
    $wire.on('show-categoryModal', () => {
        $('#categoryModal').modal('show');
    });

    $wire.on('hide-categoryModal', () => {
        $('#categoryModal').modal('hide');
    });

    /* -------------------------------------------------------------------------- */

    VirtualSelect.init({
        ele: '#document-type-select',
        options: [{
                label: 'Incoming',
                value: 'incoming'
            },
            {
                label: 'Outgoing',
                value: 'outgoing'
            }
        ],
        maxWidth: '100%'
    });

    let document_type = document.querySelector('#document-type-select');
    document_type.addEventListener('change', () => {
        let data = document_type.value;
        @this.set('document_type', data);
    });

    $wire.on('set_document_type', (key) => {
        document.querySelector('#document-type-select').setValue(key);
    });

    /* -------------------------------------------------------------------------- */

    VirtualSelect.init({
        ele: '#is-active-select',
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

    let is_active = document.querySelector('#is-active-select');
    is_active.addEventListener('change', () => {
        let data = is_active.value;
        @this.set('is_active', data);
    });

    $wire.on('set_is_active', (key) => {
        document.querySelector('#is-active-select').setValue(key);
    });

    /* -------------------------------------------------------------------------- */

    $wire.on('clear-plugins', () => {
        document.querySelector('#document-type-select').reset();
        document.querySelector('#is-active-select').reset();
    });
</script>
@endscript