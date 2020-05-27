<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>{{ config('app.name') }}</title>

    @include('dashboard._partials.head')
</head>

<body>
<div id="app">
    <section class="section">
        <div class="container mt-5">
            <div class="row">
                <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
                    <div class="login-brand">
                        <img src="{{ asset('assets/img/password.svg') }}" alt="logo" width="100">
                    </div>

                    <div class="card card-primary">
                        <div class="card-header"><h4>Reset Password</h4></div>

                        <div class="card-body">
                            <form id="formReset">
                                <div class="form-group">
                                    <label for="oldPassword">Password Lama</label>
                                    <input id="oldPassword" type="password" class="form-control" name="password_lama" tabindex="1" required autofocus>
                                </div>

                                <div class="form-group">
                                    <label for="newPassword">Password Baru</label>
                                    <input id="newPassword" type="password" class="form-control" name="password_baru" tabindex="1" required autofocus>
                                </div>

                                <div class="form-group">
                                    <label for="repeatNewPassword">Ulangi Password Baru</label>
                                    <input id="repeatNewPassword" type="password" class="form-control" name="ulangi_password" tabindex="1" required autofocus>
                                    <small id="feedbackInvalid" class="invalid-feedback"></small>
                                    <small id="feedbackValid" class="valid-feedback"></small>
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="4">
                                        Reset Password
                                    </button>
                                </div>
                            </form>
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

    let formReset = $('#formReset');

    let status = false;
    let old = $('#oldPassword');
    let newPass = $('#newPassword');
    let repeatNew = $('#repeatNewPassword');
    let feedbackInvalid = $('#feedbackInvalid');
    let feedbackValid = $('#feedbackValid');

    $(document).ready(function () {
        repeatNew.keyup(function (e) {
            e.preventDefault();
            if (newPass.val() === repeatNew.val()) {
                repeatNew.addClass('is-valid');
                repeatNew.removeClass('is-invalid');
                feedbackInvalid.html('');
                feedbackValid.html('Password sama');
                status = true;
            } else {
                repeatNew.removeClass('is-valid');
                repeatNew.addClass('is-invalid');
                feedbackInvalid.html('Password Berbeda');
                feedbackValid.html('');
                status = false;
            }
        });

        formReset.submit(function (e) {
            e.preventDefault();
            if (status === false) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Data salah',
                    text: 'Silahkan perbaiki data diatas.',
                });
            } else {
                $.ajax({
                    url: '{{ url('reset-password/submit') }}',
                    method: 'post',
                    data: $(this).serialize(),
                    success: function (response) {
                        if (response === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Password baru tersimpan',
                                text: 'Anda akan kembali ke halaman login! Silahkan gunakan password baru anda.',
                                onClose: function () {
                                    window.location.reload();
                                }
                            });
                        } else if (response === 'password salah') {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Password lama salah',
                                text: 'Silahkan coba lagi',
                            });
                        }
                    },
                    error: function (response) {
                        console.log(response);
                        Swal.fire({
                            icon: 'error',
                            title: 'System Error',
                            text: 'Silahkan hubungi WAVE Solusi Indonesia!',
                        });
                    }
                })
            }
        });
    });
</script>
</body>
</html>
