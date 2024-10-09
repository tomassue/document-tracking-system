<div>
    <div class="main-panel">

        <div class="content-wrapper">
            <div class="row">
                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col-md-6">
                                    <h4 class="card-title">Users</h4>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-11">
                                    <input type="text" class="form-control" id="exampleInputSearch" placeholder="Search" wire:model.live="search">
                                </div>
                                <div class="col-md-1 text-end">
                                    <button type="button" class="btn btn-success btn-icon" wire:click="$dispatch('show-userManagementModal')">
                                        <i class="mdi mdi mdi-plus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th class="fw-bold" width="5%">No.</th>
                                            <th class="fw-bold" width="25%">Name</th>
                                            <th class="fw-bold" width="25%">Office</th>
                                            <th class="fw-bold" width="25%">Status</th>
                                            <th class="fw-bold" width="20%">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($users as $key=>$item)
                                        <tr wire:key="{{ $item->id }}">
                                            <td class="small-td">{{ $key+1 }}</td>
                                            <td class="small-td">{{ $item->name }}</td>
                                            <td class="small-td">{{ $item->office_name }}</td>
                                            <td class="small-td">
                                                <span class="badge badge-pill {{ $item->status == 'Active' ? 'badge-success' : 'badge-danger' }}">
                                                    {{ $item->status }}
                                                </span>
                                            </td>
                                            <td class="small-td">
                                                <button type="button" class="btn btn-sm btn-dark btn-icon-text" wire:click="edit({{ $item->id }})">
                                                    Edit
                                                    <i class="mdi mdi-file-check btn-icon-append"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-danger btn-icon-text text-white" wire:click="$dispatch('confirm-reset-password', { id: {{ $item->id }} })">
                                                    Reset &nbsp;
                                                    <i class="mdi mdi-account-key "></i>
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
                                {{ $users->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- row end -->
        </div>
        <!-- content-wrapper ends -->

        <!-- userManagementModal -->
        <div class="modal fade" id="userManagementModal" tabindex="-1" aria-labelledby="userManagementModalLabel" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true" wire:ignore.self>
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="userManagementModalLabel">{{ $editMode ? 'Edit' : 'Add' }} User</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:click="clear"></button>
                    </div>
                    <div class="modal-body">
                        <form class="forms-sample" data-bitwarden-watching="1" wire:submit="{{ $editMode ? 'update' : 'add' }}">
                            <div class="form-group">
                                <label for="exampleFullName">Full Name</label>
                                <input type="text" class="form-control" id="exampleFullName" placeholder="Input full name" wire:model="full_name">
                                @error('full_name') <div class="custom-invalid-feedback"> {{ $message }} </div> @enderror
                            </div>
                            <div class="form-group">
                                <label for="exampleFullName">Office</label>
                                <div id="office-select" wire:ignore></div>
                                @error('selectedRefOffice') <div class="custom-invalid-feedback"> {{ $message }} </div> @enderror
                            </div>
                            <div class="form-group">
                                <label for="exampleEmail">Username</label>
                                <!-- <input type="email" class="form-control" id="exampleEmail" placeholder="username@mail.com" wire:model="username"> -->
                                <input type="text" class="form-control" id="username" placeholder="Input username" wire:model="username">
                                @error('username') <div class="custom-invalid-feedback"> {{ $message }} </div> @enderror
                            </div>
                            <div class="form-group" style="display: {{ $editMode ? 'block' : 'none' }};">
                                <label for="exampleEmail">Is active</label>
                                <div id="is_active_select" wire:ignore></div>
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
</div>

@script
<script>
    $wire.on('show-userManagementModal', () => {
        $('#userManagementModal').modal('show');
    })

    $wire.on('hide-userManagementModal', () => {
        $('#userManagementModal').modal('hide');
    });

    /* -------------------------------------------------------------------------- */

    VirtualSelect.init({
        ele: '#office-select',
        options: @json($ref_offices),
        search: true,
        markSearchResults: true,
        maxWidth: '100%'
    });

    let selectedRefOffice = document.querySelector('#office-select');
    selectedRefOffice.addEventListener('change', () => {
        let data = selectedRefOffice.value;
        @this.set('selectedRefOffice', data);
    });

    //NOTE - Reset the virtual select.
    $wire.on('reset-virtual-select', () => {
        document.querySelector('#office-select').reset();
    });

    $wire.on('office_id-edit', (key) => {
        document.querySelector('#office-select').destroy();

        VirtualSelect.init({
            ele: '#office-select',
            options: @json($ref_offices),
            search: true,
            markSearchResults: true,
            maxWidth: '100%'
        });

        document.querySelector('#office-select').setValue(key.value); //NOTE - Without key.value, it returns as an object. We used key.value to specify the item.
        // console.log(key.value);
    });

    /* -------------------------------------------------------------------------- */

    VirtualSelect.init({
        ele: '#is_active_select',
        options: [{
                label: 'Yes',
                value: '1'
            },
            {
                label: 'No',
                value: '0'
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
        document.querySelector('#is_active_select').setValue(key.value);
    });

    /* -------------------------------------------------------------------------- */

    $wire.on('confirm-reset-password', (id) => {
        Swal.fire({
            title: "Are you sure you want to reset the password for this user?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Reset password!"
        }).then((result) => {
            if (result.isConfirmed) {
                $wire.dispatch('reset-password', {
                    id: id
                });
                Swal.fire({
                    title: "Reset password succesfully!",
                    text: "The user's password has been reset.",
                    icon: "success"
                });
            }
        });
    });
</script>
@endscript