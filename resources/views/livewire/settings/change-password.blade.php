<div>
    <div class="main-panel">

        <div class="content-wrapper">
            <div class="row">
                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Change Password</h4>
                            <p class="card-description">
                                Passwords must have at least 8 characters and contains of the following: upper case letters, lower case letters, numbers and symbols.
                            </p>
                            <form class="forms-sample" wire:submit="update">
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Current Password</label>
                                    <input type="password" class="form-control" id="exampleInputPassword1" wire:model="current_password">
                                    @error('current_password') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class=" form-group">
                                    <label for="exampleInputNewPassword">New Password</label>
                                    <input type="password" class="form-control" id="exampleInputNewPassword" wire:model="new_password">
                                    @error('new_password') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class=" form-group">
                                    <label for="exampleInputConfirmPassword">Confirm Password</label>
                                    <input type="password" class="form-control" id="exampleInputConfirmPassword" wire:model="confirm_password">
                                    @error('confirm_password') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <button type="submit" class="btn btn-primary me-2">Submit</button>
                                <button class="btn btn-light">Cancel</button>
                            </form>
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
                        <h1 class="modal-title fs-5" id="userManagementModalLabel">User</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:click="clear"></button>
                    </div>
                    <div class="modal-body">
                        <form class="forms-sample" data-bitwarden-watching="1" wire:submit="">
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
                                <input type="email" class="form-control" id="exampleEmail" placeholder="username@mail.com" wire:model="username">
                                @error('username') <div class="custom-invalid-feedback"> {{ $message }} </div> @enderror
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" wire:click="clear">Close</button>
                        <button type="submit" class="btn btn-primary">Change Password</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>