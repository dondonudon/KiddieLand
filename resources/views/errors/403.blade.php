<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>{{ config('app.name') }} &mdash; 403</title>

    @include('dashboard._partials.head')
</head>

<body>
<div id="app">
    <section class="section">
        <div class="container mt-5">
            <div class="page-error">
                <div class="page-inner">
                    <h1>403</h1>
                    <div class="page-description">
                        Anda tidak diperkenankan untuk mengakses halaman ini.
                    </div>
                    <div class="page-search">
                        <div class="mt-3">
                            <a href="{{ url()->previous() }}">Kembali ke halaman sebelumnya</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="simple-footer mt-5">
                <i class="fas fa-copyright"></i> {{ date('Y') }} &mdash; {{ config('app.name') }}
            </div>
        </div>
    </section>
</div>

@include('dashboard._partials.footer-script')
</body>
</html>
