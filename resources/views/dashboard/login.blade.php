<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>{{ config('app.name') }} &mdash; {{ request()->segment(1) }}</title>

    @include('dashboard._partials.head')
</head>

<body>
<div id="app">
    <section class="section">
        <div class="container mt-3">
            <div class="row justify-content-center">
                {{--                <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">--}}
                <div class="col-12 col-sm-8 col-md-6 col-lg-6 col-xl-4">
                    <div class="login-brand">
                        <img id="logo" src="{{ asset('assets/logo/with-name/logo-1000.png') }}" alt="logo" style="width: 100%">
                    </div>

                    <div class="row" id="pageLoading">
                        <div class="col-12 text-center">
                            <div class="spinner-border text-danger" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                        <div class="col-12 text-danger text-center">
                            LOADING ALL ASSETS...
                        </div>
                    </div>

                    <div id="loginCard" class="card card-danger d-none">
                        <div class="card-header"><h4>Login</h4></div>

                        <div class="card-body">
                            <form id="formLogin" class="needs-validation" novalidate="">
                                <div class="form-group">
                                    <label for="username">Username</label>
                                    <input id="username" type="text" class="form-control" name="username" tabindex="1" style="text-align: center" required>
                                    <div class="invalid-feedback">
                                        Please fill in your username
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="d-block">
                                        <label for="password" class="control-label">Password</label>
                                    </div>
                                    <input id="password" type="password" class="form-control" name="password" tabindex="2" style="text-align: center" required>
                                    <div class="invalid-feedback">
                                        please fill in your password
                                    </div>
                                </div>

                                <div class="form-group text-center">
                                    <button id="btnSubmit" type="submit" class="btn btn-danger btn-lg btn-block" tabindex="4">
                                        Login
                                    </button>
                                    <div id="loading" class="spinner-border text-primary d-none" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </div>
                            </form>
                            {{--                            <hr>--}}
                            {{--                            <div class="text-center mt-4 mb-3">--}}
                            {{--                                <div class="text-job text-muted">Login With Social</div>--}}
                            {{--                            </div>--}}
                            {{--                            <div class="row justify-content-center">--}}
                            {{--                                <div class="col-lg-6 col-sm-12">--}}
                            {{--                                    <button class="btn btn-danger btn-block">--}}
                            {{--                                        <i class="fab fa-google mr-3"></i> Google--}}
                            {{--                                    </button>--}}
                            {{--                                </div>--}}
                            {{--                            </div>--}}

                        </div>
                    </div>
                    <div class="simple-footer">
                        <i class="fas fa-copyright"></i> {{ date('Y') }} {{ config('app.name') }}
                        <p>
                            Developed by <a href="http://waveitsolution.com">{{ config('app.developer') }}</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@include('dashboard._partials.footer-script')
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>
<script>
    let formLogin = $('#formLogin');
    let btnSubmit = $('#btnSubmit');
    let loading = $('#loading');

    let pageLoading =$('#pageLoading');
    let loginCard = $('#loginCard');
    let logo = $('#logo');

    $(document).ready(function () {
        pageLoading.addClass('d-none');
        logo.css('width','40%');
        loginCard.removeClass('d-none');

        document.getElementById('username').autofocus = true;

        formLogin.submit(function (e) {
            btnSubmit.addClass('d-none');
            loading.removeClass('d-none');
            e.preventDefault();
            $.ajax({
                url: '{{ url('login/submit') }}',
                method: 'post',
                data: $(this).serialize(),
                success: function (response) {
                    if (response === 'success') {
                        btnSubmit.removeClass('d-none');
                        loading.addClass('d-none');
                        Swal.fire({
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 800,
                            onClose: function () {
                                window.location = '{{ url('dashboard') }}';
                            }
                        });
                    } else {
                        btnSubmit.removeClass('d-none');
                        loading.addClass('d-none');
                        Swal.fire({
                            icon: 'warning',
                            title: 'Login Failed',
                            text: response,
                        });
                        console.log(response);
                    }
                },
                error: function (response) {
                    console.log(response);
                    btnSubmit.removeClass('d-none');
                    loading.addClass('d-none');
                    Swal.fire({
                        icon: 'error',
                        title: 'System error',
                        text: 'Silahkan coba lagi atau hubungi WAVE Solusi Indonesia',
                    });
                }
            });
        });
    });
</script>
</body>
</html>
