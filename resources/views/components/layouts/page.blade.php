<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? 'Page Title' }}</title>

    <!-- jquery -->
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>

    <!-- base:css -->
    <link rel="stylesheet" href="{{ asset('vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/css/vendor.bundle.base.css') }}">
    <!-- endinject -->
    <!-- plugin css for this page -->
    <!-- End plugin css for this page -->

    <!-- inject:css -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <!-- endinject -->
    <link rel="shortcut icon" href="{{ asset('images/favicon.png') }}" />

    <!-- SweetAlert2 -->
    <script src="{{ asset('plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('plugins/sweetalert2/sweetalert2.min.css') }}">

    <!-- Virtual Select -->
    <link rel="stylesheet" href="{{ asset('plugins/virtual-select/virtual-select.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/virtual-select/tooltip.min.css') }}">

    <!-- Tiny MCE -->
    <script src="https://cdn.tiny.cloud/1/oesmgpb85r1zhz03towvnev67dhrse0olv9o0ai5146b77g6/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>

    <!-- pickadate -->
    <link rel="stylesheet" href="{{ asset('plugins/pickadate.js-3.6.2/lib/themes/classic.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/pickadate.js-3.6.2/lib/themes/classic.date.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/pickadate.js-3.6.2/lib/themes/classic.time.css') }}">

    <style>
        .custom-invalid-feedback {
            width: 100%;
            margin-top: .25rem;
            font-size: .875em;
            color: #dc3545;
        }

        /* NOTE - Virtual-select custom CSS */
        .vscomp-toggle-button {
            border: 1px solid #f3f3f3 !important;
            height: 50px;
        }

        /* pickadate */
        .picker__holder {
            width: 30%;
        }
    </style>
</head>

<body>

    <div class="container-scroller d-flex">

        <!-- <div class="row p-0 m-0 proBanner" id="proBanner">
            <div class="col-md-12 p-0 m-0">
                <div class="card-body card-body-padding d-flex align-items-center justify-content-between">
                    <div class="ps-lg-1">
                        <div class="d-flex align-items-center justify-content-between">
                            <p class="mb-0 font-weight-medium me-3 buy-now-text">Free 24/7 customer support, updates, and more with this template!</p>
                            <a href="https://www.bootstrapdash.com/product/spica-admin/?utm_source=organic&utm_medium=banner&utm_campaign=buynow_demo" target="_blank" class="btn me-2 buy-now-btn border-0">Get Pro</a>
                        </div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <a href="https://www.bootstrapdash.com/product/spica-admin/"><i class="mdi mdi-home me-3 text-white"></i></a>
                        <button id="bannerClose" class="btn border-0 p-0">
                            <i class="mdi mdi-close text-white mr-0"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div> -->

        <livewire:template.sidebar />

        <div class="container-fluid page-body-wrapper">
            <livewire:template.banner />

            {{ $slot }}

            <livewire:template.footer />
        </div>
        <!-- page-body-wrapper ends -->

    </div>
    <!-- container-scroller -->

    <!-- base:js -->
    <script src="{{ asset('vendors/js/vendor.bundle.base.js') }}"></script>
    <!-- endinject -->

    <!-- Plugin js for this page-->
    <script src="{{ asset('vendors/chart.js/Chart.min.js') }}"></script>
    <script src="{{ asset('js/jquery.cookie.js') }}" type="text/javascript"></script>
    <!-- End plugin js for this page-->

    <!-- inject:js -->
    <script src="{{ asset('js/off-canvas.js') }}"></script>
    <script src="{{ asset('js/hoverable-collapse.js') }}"></script>
    <script src="{{ asset('js/template.js') }}"></script>
    <!-- endinject -->

    <!-- plugin js for this page -->
    <script src="{{ asset('js/jquery.cookie.js') }}" type="text/javascript"></script>
    <!-- End plugin js for this page -->

    <!-- Custom js for this page-->
    <script src="{{ asset('js/dashboard.js') }}"></script>
    <!-- End custom js for this page-->

    <!-- Virtual Select -->
    <script src="{{ asset('plugins/virtual-select/virtual-select.min.js') }}"></script>
    <script src="{{ asset('plugins/virtual-select/tooltip.min.js') }}"></script>

    <!-- pickadate -->
    <script src="{{ asset('plugins/pickadate.js-3.6.2/lib/picker.js') }}"></script>
    <script src="{{ asset('plugins/pickadate.js-3.6.2/lib/picker.date.js') }}"></script>
    <script src="{{ asset('plugins/pickadate.js-3.6.2/lib/picker.time.js') }}"></script>

    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('show-success-save-message-toast', (event) => {
                const Toast = Swal.mixin({
                    toast: true,
                    position: "top-end",
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.onmouseenter = Swal.stopTimer;
                        toast.onmouseleave = Swal.resumeTimer;
                    }
                });
                Toast.fire({
                    icon: "success",
                    title: "Saved successfully."
                });
            });

            Livewire.on('show-success-update-message-toast', (event) => {
                const Toast = Swal.mixin({
                    toast: true,
                    position: "top-end",
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.onmouseenter = Swal.stopTimer;
                        toast.onmouseleave = Swal.resumeTimer;
                    }
                });
                Toast.fire({
                    icon: "success",
                    title: "Record updated successfully."
                });
            });

            Livewire.on('show-success-update-message-toast', (event) => {
                const Toast = Swal.mixin({
                    toast: true,
                    position: "top-end",
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.onmouseenter = Swal.stopTimer;
                        toast.onmouseleave = Swal.resumeTimer;
                    }
                });
                Toast.fire({
                    icon: "success",
                    title: "Updated successfully."
                });
            });

            Livewire.on('show-error-duplicate-entry-message-toast', (event) => {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Duplicate entry."
                });
            });

            Livewire.on('show-something-went-wrong-toast', (event) => {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Something went wrong."
                });
            });
        });
    </script>
</body>

</html>