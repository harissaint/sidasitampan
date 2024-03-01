<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--favicon-->
    <link rel="icon" href="{{ asset('app/assets/images/icon-mo.png') }}" type="image/png" />
    <!--plugins-->
    <link href="{{ asset('app/assets/plugins/vectormap/jquery-jvectormap-2.0.2.css') }}" rel="stylesheet" />
    <link href="{{ asset('app/assets/plugins/simplebar/css/simplebar.css') }}" rel="stylesheet" />
    <link href="{{ asset('app/assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css') }}" rel="stylesheet" />
    <link href="{{ asset('app/assets/plugins/metismenu/css/metisMenu.min.css') }}" rel="stylesheet" />
    <!-- loader-->
    <link href="{{ asset('app/assets/css/pace.min.css') }}" rel="stylesheet" />
    <script src="{{ asset('app/assets/js/pace.min.js') }}"></script>
    <!-- Bootstrap CSS -->
    @routes
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link href="{{ asset('app/assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('app/assets/css/bootstrap-extended.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link href="{{ asset('app/assets/css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('app/assets/css/icons.css') }}" rel="stylesheet">
    <!-- Theme Style CSS -->
    <link rel="stylesheet" href="{{ asset('app/assets/css/dark-theme.css') }}" />
    <link rel="stylesheet" href="{{ asset('app/assets/css/semi-dark.css') }}" />
    <link rel="stylesheet" href="{{ asset('app/assets/css/header-colors.css') }}" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') - Sidasi Tampan Kota Cimahi</title>

    @yield('style')
</head>

<body>
    <!--wrapper-->
    <div class="wrapper">
        <!--sidebar wrapper -->
        @include('partials.app.sidebar')
        <!--end sidebar wrapper -->
        <!--start header -->
        @include('partials.app.header')
        <!--end header -->
        <!--start page wrapper -->
        <div class="page-wrapper">
            <div class="page-content">

                <!--breadcrumb-->
                @yield('breadcrumb')
                <!--end breadcrumb-->

                @yield('content')
            </div>
        </div>
        <!--end page wrapper -->
        <!--start overlay-->
        <div class="overlay toggle-icon"></div>
        <!--end overlay-->
        <!--Start Back To Top Button-->
        <a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
        <!--End Back To Top Button-->
        <footer class="page-footer">
            <p class="mb-0">Copyright © {{ date('Y') }}. All right reserved.</p>
        </footer>
    </div>
    <!--end wrapper-->
    <!--start switcher-->
    @include('partials.app.switcher')
    <!--end switcher-->
    <!-- Bootstrap JS -->
    <script src="{{ asset('app/assets/js/bootstrap.bundle.min.js') }}"></script>
    <!--plugins-->
    <script src="{{ asset('app/assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('app/assets/plugins/simplebar/js/simplebar.min.js') }}"></script>
    <script src="{{ asset('app/assets/plugins/metismenu/js/metisMenu.min.js') }}"></script>
    <script src="{{ asset('app/assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('app/assets/plugins/vectormap/jquery-jvectormap-2.0.2.min.js') }}"></script>
    <script src="{{ asset('app/assets/plugins/vectormap/jquery-jvectormap-world-mill-en.js') }}"></script>
    <script src="{{ asset('app/assets/plugins/chartjs/js/Chart.min.js') }}"></script>
    <script src="{{ asset('app/assets/plugins/chartjs/js/Chart.extension.js') }}"></script>
    @yield('script')
    <!--app JS-->
    <script src="{{ asset('app/assets/js/app.js') }}"></script>
</body>

</html>
