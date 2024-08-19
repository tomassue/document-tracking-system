<!-- /* -------------------------------------------------------------------------- */
/*                               outgoingModal                               */
/* -------------------------------------------------------------------------- */ -->

<div class="modal fade" id="outgoingModal" tabindex="-1" aria-labelledby="outgoingModalLabel" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="outgoingModalLabel">{{ $editMode ? 'Edit' : 'Add' }} Request</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:click="clear"></button>
            </div>
            <div class="modal-body">
                <form class="form-sample" wire:submit="{{ $editMode ? 'update' : 'add' }}">
                    <p class="card-description">
                        <!-- Personal info -->
                    </p>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Category</label>
                                <div class="col-sm-9">
                                    <div id="outgoing-category-select" wire:ignore></div>
                                    @error('outgoing_category') <span class="custom-invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label" style="padding-top: 0px;">Person Responsible</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" wire:model="person_responsible">
                                    @error('person_responsible') <span class="custom-invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- NOTE - the shorthand condition here will display the input fields. Plugins will be initialized immediately and will wait for the changes on the $outgoing_category then set the display to block. Othewise, none.
                    If we use if() condition, there are issues such as the plugins won't render because, I think it ignores the contents of that condition. Thus, plugins need to be reinitialized again if the condition returns true.
                    If we manipulate the form through CSS display property, the component will initialize all plugins upon the rendering the component then we display: block or none the form inputs depending on the condition we set. -->


                    <!-- /* -------------------------------------------------------------------------- */
                    /*                               IF PROCUREMENT                               */
                    /* -------------------------------------------------------------------------- */ -->

                    <div style="display: {{ ($outgoing_category == 'procurement' ? 'block' : 'none') }} ">
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Document No.</label>
                                    <div class="col-sm-9">
                                        <!-- Document No's input is system generated. Thus, it will be manipulated in our component -->
                                        <input type="text" class="form-control" placeholder="{{ $document_no }}" disabled>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Date</label>
                                    <div class="col-sm-9">
                                        <div wire:ignore>
                                            <input class="form-control date"></input>
                                        </div>
                                        @error('date') <span class="custom-invalid-feedback">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">P.R. No.</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" placeholder="Auto-generated??" wire:model="PR_no">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group row" style="display: {{ $editMode ? 'block' : 'none' }}">
                                    <label class="col-sm-3 col-form-label">Status</label>
                                    <div class="col-sm-9">
                                        <div id="outgoing-status-select" wire:ignore></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">P.O. No.</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" placeholder="Auto-generated??" wire:model="PO_no">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    @error('document_details') <span class="custom-invalid-feedback">{{ $message }}</span> @enderror
                                    <label class="col-sm-2 col-form-label">Document Details</label>
                                    <div class="col-sm-12" wire:ignore>
                                        <input id="document_details"></input>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label class="col-sm-12 col-form-label">Attachment</label>
                                    <div class="col-sm-12" wire:ignore>
                                        <input type="file" accept="application/pdf" class="form-control documents-my-pond-attachment" multiple data-allow-reorder="true">
                                    </div>
                                    @error('attachments') <span class="custom-invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- /* -------------------------------------------------------------------------- */
                    /*                               IF PROCUREMENT                               */
                    /* -------------------------------------------------------------------------- */ -->


                    <!-- /* -------------------------------------------------------------------------- */
                    /*                                 IF PAYROLL                                 */
                    /* -------------------------------------------------------------------------- */ -->

                    <div style="display: {{ ($outgoing_category == 'payroll' ? 'block' : 'none') }} ">
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Document No.</label>
                                    <div class="col-sm-9">
                                        <!-- Document No's input is system generated. Thus, it will be manipulated in our component -->
                                        <input type="text" class="form-control" placeholder="Auto-generated" disabled>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Date</label>
                                    <div class="col-sm-9">
                                        <div wire:ignore>
                                            <input class="form-control date"></input>
                                        </div>
                                        @error('date') <span class="custom-invalid-feedback">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Payroll Type</label>
                                    <div class="col-sm-9">
                                        <div id="outgoing_payroll_type_select" wire:ignore></div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group row" style="display: {{ $editMode ? 'block' : 'none' }}">
                                    <label class="col-sm-3 col-form-label">Status</label>
                                    <div class="col-sm-9">
                                        <div id="outgoing-status-select" wire:ignore></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    @error('document_details') <span class="custom-invalid-feedback">{{ $message }}</span> @enderror
                                    <label class="col-sm-2 col-form-label">Document Details</label>
                                    <div class="col-sm-12" wire:ignore>
                                        <input id="document_details"></input>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label class="col-sm-12 col-form-label">Attachment</label>
                                    <div class="col-sm-12" wire:ignore>
                                        <input type="file" accept="application/pdf" class="form-control documents-my-pond-attachment" multiple data-allow-reorder="true">
                                    </div>
                                    @error('attachments') <span class="custom-invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- /* -------------------------------------------------------------------------- */
                    /*                                 IF PAYROLL                                 */
                    /* -------------------------------------------------------------------------- */ -->


                    <!-- /* -------------------------------------------------------------------------- */
                    /*                                 IF VOUCHER                                 */
                    /* -------------------------------------------------------------------------- */ -->

                    <div style="display: {{ ($outgoing_category == 'voucher' ? 'block' : 'none') }} ">
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Document No.</label>
                                    <div class="col-sm-9">
                                        <!-- Document No's input is system generated. Thus, it will be manipulated in our component -->
                                        <input type="text" class="form-control" placeholder="Auto-generated" disabled>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Date</label>
                                    <div class="col-sm-9">
                                        <div wire:ignore>
                                            <input class="form-control date"></input>
                                        </div>
                                        @error('date') <span class="custom-invalid-feedback">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Voucher Name</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" wire:model="voucher_name">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group row" style="display: {{ $editMode ? 'block' : 'none' }}">
                                    <label class="col-sm-3 col-form-label">Status</label>
                                    <div class="col-sm-9">
                                        <div id="outgoing-status-select" wire:ignore></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    @error('document_details') <span class="custom-invalid-feedback">{{ $message }}</span> @enderror
                                    <label class="col-sm-2 col-form-label">Document Details</label>
                                    <div class="col-sm-12" wire:ignore>
                                        <input id="document_details"></input>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label class="col-sm-12 col-form-label">Attachment</label>
                                    <div class="col-sm-12" wire:ignore>
                                        <input type="file" accept="application/pdf" class="form-control documents-my-pond-attachment" multiple data-allow-reorder="true">
                                    </div>
                                    @error('attachments') <span class="custom-invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- /* -------------------------------------------------------------------------- */
                    /*                                 IF VOUCHER                                 */
                    /* -------------------------------------------------------------------------- */ -->


                    <!-- /* -------------------------------------------------------------------------- */
                    /*                                   IF RIS                                   */
                    /* -------------------------------------------------------------------------- */ -->

                    <div style="display: {{ ($outgoing_category == 'ris' ? 'block' : 'none') }} ">
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Document No.</label>
                                    <div class="col-sm-9">
                                        <!-- Document No's input is system generated. Thus, it will be manipulated in our component -->
                                        <input type="text" class="form-control" placeholder="Auto-generated" disabled>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Date</label>
                                    <div class="col-sm-9">
                                        <div wire:ignore>
                                            <input class="form-control date"></input>
                                        </div>
                                        @error('date') <span class="custom-invalid-feedback">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Document Name</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" wire:model="document_name">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group row" style="display: {{ $editMode ? 'block' : 'none' }}">
                                    <label class="col-sm-3 col-form-label">Status</label>
                                    <div class="col-sm-9">
                                        <div id="outgoing-status-select" wire:ignore></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">PPMP Code</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" wire:model="ppmp_code">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    @error('document_details') <span class="custom-invalid-feedback">{{ $message }}</span> @enderror
                                    <label class="col-sm-2 col-form-label">Document Details</label>
                                    <div class="col-sm-12" wire:ignore>
                                        <input id="document_details"></input>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label class="col-sm-12 col-form-label">Attachment</label>
                                    <div class="col-sm-12" wire:ignore>
                                        <input type="file" accept="application/pdf" class="form-control documents-my-pond-attachment" multiple data-allow-reorder="true">
                                    </div>
                                    @error('attachments') <span class="custom-invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- /* -------------------------------------------------------------------------- */
                    /*                                   IF RIS                                   */
                    /* -------------------------------------------------------------------------- */ -->


                    <!-- /* -------------------------------------------------------------------------- */
                    /*                                  IF OTHERS                                 */
                    /* -------------------------------------------------------------------------- */ -->

                    <div style="display: {{ ($outgoing_category == 'other' ? 'block' : 'none') }} ">
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Document No.</label>
                                    <div class="col-sm-9">
                                        <!-- Document No's input is system generated. Thus, it will be manipulated in our component -->
                                        <input type="text" class="form-control" placeholder="Auto-generated" disabled>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Date</label>
                                    <div class="col-sm-9">
                                        <div wire:ignore>
                                            <input class="form-control date"></input>
                                        </div>
                                        @error('date') <span class="custom-invalid-feedback">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Document Name</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" wire:model="document_name">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group row" style="display: {{ $editMode ? 'block' : 'none' }}">
                                    <label class="col-sm-3 col-form-label">Status</label>
                                    <div class="col-sm-9">
                                        <div id="outgoing-status-select" wire:ignore></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    @error('document_details') <span class="custom-invalid-feedback">{{ $message }}</span> @enderror
                                    <label class="col-sm-2 col-form-label">Document Details</label>
                                    <div class="col-sm-12" wire:ignore>
                                        <input id="document_details"></input>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label class="col-sm-12 col-form-label">Attachment</label>
                                    <div class="col-sm-12" wire:ignore>
                                        <input type="file" accept="application/pdf" class="form-control documents-my-pond-attachment" multiple data-allow-reorder="true">
                                    </div>
                                    @error('attachments') <span class="custom-invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- /* -------------------------------------------------------------------------- */
                    /*                                  IF OTHERS                                 */
                    /* -------------------------------------------------------------------------- */ -->


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" wire:click="clear">Close</button>
                <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">{{ $editMode ? 'Update' : 'Save' }}</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- /* -------------------------------------------------------------------------- */
/*                               outgoingModal                               */
/* -------------------------------------------------------------------------- */ -->