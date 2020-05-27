@extends('dashboard.layout')

@php
$menu = \App\Http\Controllers\c_Dashboard::sidebar();
@endphp

@section('content')
    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-6 col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Tambah User Baru</h4>
                    </div>
                    <form id="formData">
                        <input type="hidden" name="type" value="baru">
                        <div class="card-body">
                            <div class="form-group">
                                <label>Username</label>
                                <input name="username" type="text" class="form-control" autofocus>
                                <small>Password untuk user baru sama dengan username. Setiap user dapat mengganti password melalui menu User Profile.</small>
                            </div>
                            <div class="form-group">
                                <label>Nama</label>
                                <input name="name" type="text" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Email</label>
                                <input name="email" type="text" class="form-control">
                                <small>Anda tidak harus mengisi email.</small>
                            </div>
                            {{-- <div class="form-group">
                                <label for="iSystem">System</label>
                                <select class="form-control" id="iSystem" name="system">
                                    <option value="0">Android & Website</option>
                                    <option value="1">Android</option>
                                    <option value="2">Website</option>
                                </select>
                            </div> --}}
                            <hr>
                            <h5>Permission</h5>
                            @foreach($menu as $g)
                                <hr>
                                @if($g['group']['status'] !== 1)
                                    <div class="row">
                                        <div class="col-lg-3">
                                            <h6 class="ml-5">{{ $g['group']['name'] }}</h6>
                                        </div>
                                        <div class="col-lg-9">
                                            <div class="row">
                                                @foreach($g['menu'] as $m)
                                                    <div class="col-lg-4">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" name="permission[]" class="custom-control-input" id="permission_{{ $m['id'] }}" value="{{ $m['id'] }}">
                                                            <label class="custom-control-label" for="permission_{{ $m['id'] }}">{{ $m['name'] }}</label>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                        <div class="card-footer bg-whitesmoke">
                            <div class="row justify-content-end">
                                <div class="col-sm-12 col-lg-2 mt-2 mb-lg-0">
                                    <button type="button" class="btn btn-block btn-outline-danger" onclick="window.location = '{{ url('master/user-management') }}'">
                                        <i class="fas fa-times mr-2"></i>Cancel
                                    </button>
                                </div>
                                <div class="col-sm-12 col-lg-2 mt-2 mb-lg-0">
                                    <button type="submit" class="btn btn-block btn-success"><i class="fas fa-check mr-2"></i>Simpan</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        let formData = $('#formData');

        $(document).ready(function () {
            $('#listTable').DataTable({
                responsive: true
            });

            formData.submit(function (e) {
                e.preventDefault();
                $.ajax({
                    url: '{{ url('dashboard/master/user-management/submit') }}',
                    method: 'post',
                    data: $(this).serialize(),
                    success: function (response) {
                        if (response === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Data Tersimpan',
                                showConfirmButton: false,
                                timer: 1000,
                                onClose: function () {
                                    window.location = '{{ url('dashboard/master/user-management') }}';
                                }
                            });
                        } else {
                            console.log(response);
                            Swal.fire({
                                icon: 'warning',
                                title: 'Data Gagal Tersimpan',
                                text: response,
                            });
                        }
                    },
                    error: function (response) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Data Gagal Tersimpan',
                            text: response,
                        });
                    }
                })
            })
        });
    </script>
@endsection
