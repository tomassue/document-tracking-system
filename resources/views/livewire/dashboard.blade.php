<div>
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body" wire:ignore>
                            <h4 class="card-title">Requests</h4>
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
                                            <td>Row 1 Data 1</td>
                                            <td>Row 1 Data 2</td>
                                            <td>Row 1 Data 2</td>
                                            <td>Row 1 Data 2</td>
                                            <td>Row 1 Data 2</td>
                                            <td>Row 1 Data 2</td>
                                            <td>Row 1 Data 2</td>
                                        </tr>
                                        <tr>
                                            <td>Row 2 Data 1</td>
                                            <td>Row 2 Data 2</td>
                                            <td>Row 1 Data 2</td>
                                            <td>Row 1 Data 2</td>
                                            <td>Row 1 Data 2</td>
                                            <td>Row 1 Data 2</td>
                                            <td>Row 1 Data 2</td>
                                        </tr>
                                        <tr>
                                            <td>Row 2 Data 1</td>
                                            <td>Row 2 Data 2</td>
                                            <td>Row 1 Data 2</td>
                                            <td>Row 1 Data 2</td>
                                            <td>Row 1 Data 2</td>
                                            <td>Row 1 Data 2</td>
                                            <td>Row 1 Data 2</td>
                                        </tr>
                                        <tr>
                                            <td>Row 2 Data 1</td>
                                            <td>Row 2 Data 2</td>
                                            <td>Row 1 Data 2</td>
                                            <td>Row 1 Data 2</td>
                                            <td>Row 1 Data 2</td>
                                            <td>Row 1 Data 2</td>
                                            <td>Row 1 Data 2</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body" wire:ignore>
                            <h4 class="card-title">Documents</h4>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th class="fw-bold">Category</th>
                                            <th class="fw-bold">Document No.</th>
                                            <th class="fw-bold">Document</th>
                                            <th class="fw-bold">Status</th>
                                            <th class="fw-bold">Details</th>
                                            <th class="fw-bold">History</th>
                                            <th class="fw-bold">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Row 1 Data 1</td>
                                            <td>Row 1 Data 2</td>
                                            <td>Row 1 Data 2</td>
                                            <td>Row 1 Data 2</td>
                                            <td>Row 1 Data 2</td>
                                            <td>Row 1 Data 2</td>
                                            <td>Row 1 Data 2</td>
                                        </tr>
                                        <tr>
                                            <td>Row 1 Data 1</td>
                                            <td>Row 1 Data 2</td>
                                            <td>Row 1 Data 2</td>
                                            <td>Row 1 Data 2</td>
                                            <td>Row 1 Data 2</td>
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
    <!-- main-panel ends -->
</div>

@script
<script>
    $(document).ready(function() {
        $('#dashboardRequestTable').DataTable();
    });

    $(document).ready(function() {
        $('#dashboardDocumentsTable').DataTable();
    });
</script>
@endscript