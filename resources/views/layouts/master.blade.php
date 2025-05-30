<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="utf-8" />
    @yield('title')

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- csrf token-->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- csrf token-->
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ url('assets/images/favicon.ico') }}">

    <!-- Daterangepicker css -->
    <link rel="stylesheet" href="{{ url('assets/vendor/daterangepicker/daterangepicker.css') }}">

    <!-- Vector Map css -->
    <link rel="stylesheet"
        href="{{ url('assets/vendor/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.css') }}">

    <!-- Theme Config Js -->
    <script src="{{ url('assets/js/hyper-config.js') }}"></script>

    <!-- App css -->
    <link href="{{ url('assets/css/app-saas.min.css') }}" rel="stylesheet" type="text/css" id="app-style" />

    <!-- Icons css -->
    <link href="{{ url('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

    <!-- sweetalert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!--end-->
    <link href="{{ url('assets/vendor/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />

    @yield('css')
</head>

<body>
    <!-- Begin page -->
    <div class="wrapper">


        <!-- ========== Topbar Start ========== -->
        @include('layouts.partials.navbar')
        <!-- ========== Topbar End ========== -->

        <!-- ========== Left Sidebar Start ========== -->
        @include('layouts.partials.sidebar')
        <!-- ========== Left Sidebar End ========== -->
        <!-- ============================================================== -->
        <!-- Start Page Content here -->
        <!-- ============================================================== -->

        <div class="content-page">
            <div class="content">

                @yield('content')
            </div>
            <!-- content -->



        </div>

        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->
    </div>
    <!-- END wrapper -->


    <!-- Vendor js -->
    <script src="{{ url('assets/js/vendor.min.js') }}"></script>

    <!-- plugin js -->

    <script src="{{ url('assets/vendor/select2/js/select2.min.js') }}"></script>
    <!-- Daterangepicker js -->
    <script src="{{ url('assets/vendor/daterangepicker/moment.min.js') }}"></script>
    <script src="{{ url('assets/vendor/daterangepicker/daterangepicker.js') }}"></script>


    <!-- App js -->
    <script src="{{ url('assets/js/app.min.js') }}"></script>
    <script src="{{ url('assets/js/service.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @yield('js')
    @include('sweetalert::alert')
    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            },
            // didClose: () => {
            //     // location.reload()
            // }
        });
    </script>

</body>


</html>
