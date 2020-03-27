<!DOCTYPE html>
<html lang="id">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>Trans Jateng {{ request()->segment(2) ? ' - '.ucfirst(request()->segment(2)).' '.ucfirst(request()->segment(3)) : '' }}</title>

    @include('dashboard._partials.head')
    @yield('style')
</head>

<body>
<div id="app">
    <div class="main-wrapper">
        <div class="navbar-bg"></div>
        @include('dashboard._partials.navbar2')
        @include('dashboard._partials.sidebar')

        <!-- Main Content -->
        <div class="main-content">
            <section class="section">
                @include('dashboard._partials.section-header')
                @yield('content')
            </section>
        </div>
        @include('dashboard._partials.footer')
    </div>
    @yield('modal')
</div>

@include('dashboard._partials.footer-script')
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>

@yield('script')
</body>
</html>
