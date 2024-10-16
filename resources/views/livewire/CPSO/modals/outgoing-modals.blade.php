<!-- /* -------------------------------------------------------------------------- */
/*                               outgoingModal                               */
/* -------------------------------------------------------------------------- */ -->

<div class="modal fade" id="outgoingModal" tabindex="-1" aria-labelledby="outgoingModalLabel" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="outgoingModalLabel">{{ $editMode ? 'Edit' : 'Add' }} Document</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:click="clear"></button>
            </div>
            <div class="modal-body">
                <form class="form-sample" wire:submit="{{ $editMode ? 'update' : 'add' }}">
                    <p class="card-description">
                        <!-- Personal info -->
                    </p>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label">Category</label>
                                <div class="col-lg-9">
                                    <div id="outgoing-category-select" wire:ignore></div>
                                    @error('outgoing_category') <span class="custom-invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label" style="padding-top: 0px;">Person Responsible</label>
                                <div class="col-lg-9">
                                    <input type="text" class="form-control" wire:model="person_responsible" @if($editMode || empty($outgoing_category)) disabled @endif>
                                    @error('person_responsible') <span class="custom-invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label">Destination</label>
                                <div class="col-lg-9">
                                    <input type="text" class="form-control" wire:model="destination" @if($editMode || empty($outgoing_category)) disabled @endif>
                                    @error('destination') <span class="custom-invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- NOTE - the shorthand condition here will display the input fields. Plugins will be initialized immediately and will wait for the changes on the $outgoing_category then set the display to block. Othewise, none.
                    If we use if() condition, there are issues such as the plugins won't render because, I think it ignores the contents of that condition. Thus, plugins need to be reinitialized again if the condition returns true.
                    If we manipulate the form through CSS display property, the component will initialize all plugins upon the rendering the component then we display: block or none the form inputs depending on the condition we set. -->

                    <div style="display: {{ empty($outgoing_category) ? 'none' : 'block' }};">
                        <hr>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label">Document No.</label>
                                    <div class="col-lg-9">
                                        <!-- Document No's input is system generated. Thus, it will be manipulated in our component -->
                                        <input type="text" class="form-control" placeholder="" wire:model="document_no" {{ $editMode ? 'disabled' : '' }}>
                                        @error('document_no') <span class="custom-invalid-feedback">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6 {{ $editMode ? '' : 'custom-input-bg' }}">
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label">Date</label>
                                    <div class="col-lg-9">
                                        <div wire:ignore>
                                            <input class="form-control date"></input>
                                        </div>
                                        @error('date') <span class="custom-invalid-feedback">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- /* ------------------------------- PROCUREMENT ------------------------------ */ -->
                            <div class="col-lg-6" style="display: {{ ($outgoing_category == '4' ? 'block' : 'none') }} ">
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label">P.R. No.</label>
                                    <div class="col-lg-9">
                                        <input type="text" class="form-control" placeholder="" wire:model="PR_no" @if($editMode) disabled @endif>
                                        @error('PR_no') <span class="custom-invalid-feedback">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                            <!-- /* ------------------------------- PROCUREMENT ------------------------------ */ -->

                            <!-- /* --------------------------------- PAYROLL -------------------------------- */ -->
                            <div class="col-lg-6" style="display: {{ ($outgoing_category == '5' ? 'block' : 'none') }} ">
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label">Payroll Type</label>
                                    <div class="col-lg-9">
                                        <div id="outgoing_payroll_type_select" wire:ignore></div>
                                        @error('payroll_type') <span class="custom-invalid-feedback">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                            <!-- /* --------------------------------- PAYROLL -------------------------------- */ -->

                            <!-- /* --------------------------------- VOUCHER -------------------------------- */ -->
                            <div class="col-lg-6" style="display: {{ ($outgoing_category == '6' ? 'block' : 'none') }} ">
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label">Voucher Name</label>
                                    <div class="col-lg-9">
                                        <input type="text" class="form-control" wire:model="voucher_name" @if($editMode) disabled @endif>
                                        @error('voucher_name') <span class="custom-invalid-feedback">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                            <!-- /* --------------------------------- VOUCHER -------------------------------- */ -->

                            <!-- /* ------------------------------ OTHERS or RIS ----------------------------- */ -->
                            <div class="col-lg-6" style="display: {{ ($outgoing_category == '8' || $outgoing_category == '7' ? 'block' : 'none') }} ">
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label">Document Name</label>
                                    <div class="col-lg-9">
                                        <input type="text" class="form-control" wire:model="document_name" @if($editMode) disabled @endif>
                                        @error('document_name') <span class="custom-invalid-feedback">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                            <!-- /* ------------------------------ OTHERS or RIS ----------------------------- */ -->

                            <div class="col-lg-6" style="display: {{ $editMode ? 'block' : 'none' }}">
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label">Status</label>
                                    <div class="col-lg-9">
                                        <div id="outgoing-status-select" wire:ignore></div>
                                        @error('status') <span class="custom-invalid-feedback">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- /* ------------------------------- PROCUREMENT ------------------------------ */ -->
                            <div class="col-lg-6" style="display: {{ ($outgoing_category == '4' ? 'block' : 'none') }} ">
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label">P.O. No.</label>
                                    <div class="col-lg-9">
                                        <input type="text" class="form-control" placeholder="" wire:model="PO_no" @if($editMode) disabled @endif>
                                        @error('PO_no') <span class="custom-invalid-feedback">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                            <!-- /* ------------------------------- PROCUREMENT ------------------------------ */ -->

                            <!-- /* ----------------------------------- RIS ---------------------------------- */ -->
                            <div class="col-lg-6" style="display: {{ ($outgoing_category == '7' ? 'block' : 'none') }} ">
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label">PPMP Code</label>
                                    <div class="col-lg-9">
                                        <input type="text" class="form-control" wire:model="ppmp_code" @if($editMode) disabled @endif>
                                        @error('ppmp_code') <span class="custom-invalid-feedback">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                            <!-- /* ----------------------------------- RIS ---------------------------------- */ -->

                            <div class="col-lg-6" style="display: {{ $editMode ? 'block' : 'none' }}">
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label">Remarks/Notes</label>
                                    <div class="col-lg-9">
                                        <div wire:ignore>
                                            <div id="summernote_notes"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group row">
                                    <label class="col-lg-2 col-form-label">Document Details</label>
                                    <div class="col-lg-12" wire:ignore>
                                        <div id="document_details"></div>
                                    </div>
                                    @error('document_details') <span class="custom-invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row" style="display: {{ ($editMode ? 'none' : 'block') }} ">
                            <div class="col-lg-12">
                                <div class="form-group row">
                                    <label class="col-lg-12 col-form-label">Attachment</label>
                                    <div class="col-lg-12" wire:ignore>
                                        <input type="file" accept="application/pdf" class="form-control documents-my-pond-attachment" multiple data-allow-reorder="true">
                                    </div>
                                    @error('attachments') <span class="custom-invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- /* ------------------------------- Attachments ------------------------------ */ -->

                    @if ($editMode == true)
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="col-form-label">Attachments</label>
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>No.</th>
                                                    <th>File Name</th>
                                                    <th class="text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($attachments as $index => $file)
                                                <tr wire:key="{{ $file->id }}">
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $file->file_name }}</td>
                                                    <td class="text-center">
                                                        <!-- Preview button (eye icon) -->
                                                        <button type="button" class="btn btn-dark btn-rounded btn-icon {{ $file_data && ($file_id == $file->id) ? 'd-none' : '' }}"
                                                            wire:click="previewAttachment({{ $file->id }})">
                                                            <i class="mdi mdi-eye"></i>
                                                        </button>

                                                        <!-- Clear button (eye-off icon) -->
                                                        <button type="button" class="btn btn-dark btn-rounded btn-icon {{ $file_data && ($file_id == $file->id) ? '' : 'd-none' }}"
                                                            wire:click="clearFileData">
                                                            <i class="mdi mdi-eye-off"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="4" class="text-center">No files found.</td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-md-6 d-flex justify-content-center align-items-center">
                                    @if ($file_data)
                                    <embed wire:loading.remove src="data:application/pdf;base64,{{ $file_data }}" title="{{ $file_title }}" type="application/pdf" style="height: 70vh; width: 100%;">
                                    @else
                                    <span>Preview file</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- /* ------------------------------- Attachments ------------------------------ */ -->


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" wire:click="clear">Close</button>
                <button type="submit" class="btn btn-primary" wire:loading.attr="disabled" style="display: {{ $hide_button_if_completed ? 'none' : '' }}">{{ $editMode ? 'Update' : 'Save' }}</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- /* -------------------------------------------------------------------------- */
/*                               outgoingModal                               */
/* -------------------------------------------------------------------------- */ -->