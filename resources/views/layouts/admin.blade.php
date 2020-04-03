<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} | @yield('title')</title>
    <link rel="stylesheet" href="{{ asset('admin/css/admin.css') }}">
    @yield('css')
</head>

<body class="sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed" style="height: auto;">
    <div class="wrapper">
        <!-- Navbar -->
        @include('admin.partials.commons._navbar')

        <!-- Main Sidebar Container -->
        @include('admin.partials.commons._sidebar')

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- content -->
            @yield('content')
        </div>
        <!-- /.content-wrapper -->

        <!-- Main Footer -->
        @include('admin.partials.commons._bottom')
    </div>
    <script src="{{ asset('admin/js/admin.js') }}"></script>
    @yield('js')
</body>

</html>