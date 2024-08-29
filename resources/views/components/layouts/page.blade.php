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

    <!-- Summernote -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <link href="{{ asset('plugins/summernote/summernote-bs5.css') }}" rel="stylesheet">

    <!-- pickadate -->
    <link rel="stylesheet" href="{{ asset('plugins/pickadate.js-3.6.2/lib/themes/classic.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/pickadate.js-3.6.2/lib/themes/classic.date.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/pickadate.js-3.6.2/lib/themes/classic.time.css') }}">

    <!-- FilePond -->
    <link href="{{ asset('plugins/jquery-filepond-master/filepond.css') }}" rel="stylesheet" />

    <!-- Fullcalendar -->
    <script src="{{ asset('plugins/fullcalendar-6.1.14/dist/index.global.min.js') }}"></script>

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

        /* custom-badges */
        .badge-danger {
            border: 1px solid #f83e37;
            color: #fff;
            background-color: #f93e37;
        }

        .badge-warning {
            border: 1px solid #ffbf36;
            color: #fff;
            background-color: #ffbf36;
        }

        .badge-dark {
            border: 1px solid #282f3a;
            color: #fff;
            background-color: #282f3a;
        }

        .badge-success {
            border: 1px solid #00d082;
            color: #fff;
            background-color: #00d082;
        }

        /* Timeline custom css */
        .timeline {
            border-left: 3px solid #727cf5;
            border-bottom-right-radius: 4px;
            border-top-right-radius: 4px;
            background: rgba(114, 124, 245, 0.09);
            margin: 0 auto;
            letter-spacing: 0.2px;
            position: relative;
            line-height: 1.4em;
            font-size: 1.03em;
            padding: 50px;
            list-style: none;
            text-align: left;
            /* max-width: 40%; */
            margin-left: 10px;
        }

        @media (max-width: 2000px) {
            .timeline {
                max-width: 98%;
                padding: 25px;
            }
        }

        .timeline h1 {
            font-weight: 300;
            font-size: 1.4em;
        }

        .timeline h2,
        .timeline h3 {
            font-weight: 600;
            font-size: 1rem;
            margin-bottom: 10px;
        }

        .timeline .event {
            border-bottom: 1px dashed #e8ebf1;
            padding-bottom: 25px;
            margin-bottom: 25px;
            position: relative;
        }

        @media (max-width: 2000px) {
            .timeline .event {
                padding-top: 30px;
            }
        }

        .timeline .event:last-of-type {
            padding-bottom: 0;
            margin-bottom: 0;
            border: none;
        }

        .timeline .event:before,
        .timeline .event:after {
            position: absolute;
            display: block;
            top: 0;
        }

        .timeline .event:before {
            left: -207px;
            content: attr(data-date);
            text-align: right;
            font-weight: 100;
            font-size: 0.9em;
            min-width: 120px;
        }

        @media (max-width: 2000px) {
            .timeline .event:before {
                left: 0px;
                text-align: left;
            }
        }

        .timeline .event:after {
            -webkit-box-shadow: 0 0 0 3px #727cf5;
            box-shadow: 0 0 0 3px #727cf5;
            left: -55.8px;
            background: #fff;
            border-radius: 50%;
            height: 9px;
            width: 9px;
            content: "";
            top: 5px;
        }

        @media (max-width: 2000px) {
            .timeline .event:after {
                left: -31.8px;
            }
        }

        .rtl .timeline {
            border-left: 0;
            text-align: right;
            border-bottom-right-radius: 0;
            border-top-right-radius: 0;
            border-bottom-left-radius: 4px;
            border-top-left-radius: 4px;
            border-right: 3px solid #727cf5;
        }

        .rtl .timeline .event::before {
            left: 0;
            right: -170px;
        }

        .rtl .timeline .event::after {
            left: 0;
            right: -55.8px;
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

    <!-- summernote -->
    <script src="{{ asset('plugins/summernote/summernote-bs5.js') }}"></script>

    <!-- FilePond -->
    <script src="{{ asset('plugins/jquery-filepond-master/filepond.min.js') }}"></script>
    <script src="{{ asset('plugins/jquery-filepond-master/filepond.jquery.js') }}"></script>
    <script src="{{ asset('plugins/jquery-filepond-master/filepond-plugin-file-validate-type.js') }}"></script>
    <script src="{{ asset('plugins/jquery-filepond-master/filepond.js') }}"></script>

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

            /* -------------------------------------------------------------------------- */

            /**
             * NOTE
             * Interactions for the history modal is made global because history modal would likely come accross in every pages.
             * I also made it as an independent file. I mean we will just `include` it if it's applicable.
             * 
             * LINK - resources\views\livewire\history_modal\history_modal.blade.php
             * LINK - resources\views\livewire\incoming\documents.blade.php#96
             */

            Livewire.on('show-historyModal', (event) => {
                $('#historyModal').modal('show');
            });

            Livewire.on('hide-historyModal', (event) => {
                $('#historyModal').modal('hide');
            });

            /* -------------------------------------------------------------------------- */
        });
    </script>
</body>

</html>