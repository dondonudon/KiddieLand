<!-- General JS Scripts -->
<script src="{{ asset('vendors/general.js') }}"></script>

<script src="{{ asset('assets/js/stisla.js') }}"></script>

<!-- JS Libraies -->
<script src="{{ asset('vendors/plugin.js') }}"></script>

<!-- Template JS File -->
<script src="{{ asset('assets/js/scripts.js') }}"></script>
<script src="{{ asset('assets/js/custom.js') }}"></script>

{{-- Function Log Out --}}
<script>
    let btnLogout = $('#btnLogout');
    btnLogout.click(function (e) {
        e.preventDefault();
        $.ajax({
            url: '{{ url('logout') }}',
            method: 'post',
            success: function (response) {
                if (response === 'success') {
                    window.location.reload();
                } else {
                    console.log(response);
                    Swal.fire({
                        icon: 'warning',
                        title: 'Gagal Logout',
                        text: 'Silahkan coba lagi atau hubungi WAVE Solusi Indonesia',
                    });
                }
            },
            error: function (response) {
                console.log(response);
                Swal.fire({
                    icon: 'error',
                    title: 'System Error!',
                    text: 'Silahkan hubungi WAVE Solusi Indonesia',
                });
            }
        });
    });

    // const platform = new H.service.Platform({
    //     'apikey': 'SVQjU5j3pvx_8Ea6iBG3mjwNwt2v5wNw3h-IWyfpLUg'
    // });
    //
    // const defaultLayers = platform.createDefaultLayers();
</script>