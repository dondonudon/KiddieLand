@extends('dashboard.layout')

@section('page_menu')
<li class="nav-item {{ (request()->segment(4) == null) ? 'active' : '' }}">
    <a href="{{ url(request()->segment(1).'/'.request()->segment(2).'/'.request()->segment(3)) }}" class="nav-link">
        <i class="fas fa-plus-circle mr-2" style="font-size: x-large; vertical-align: middle;"></i>
        <div class="d-none d-lg-inline-block d-xl-inline-block">Tambah User</div>
    </a>
</li>
<li class="nav-item {{ (request()->segment(4) == 'list') ? 'active' : '' }}">
    <a href="{{ url(request()->segment(1).'/'.request()->segment(2).'/'.request()->segment(3)) }}/list"
        class="nav-link">
        <i class="fas fa-table mr-2" style="font-size: x-large; vertical-align: middle;"></i>
        <span class="d-none d-lg-inline-block d-xl-inline-block">
            Daftar User
        </span>
    </a>
</li>
@endsection

@section('title','Master User')

@php
$menu = \App\Http\Controllers\c_Dashboard::sidebar();
@endphp

@section('content')
<div class="section-body">
    <div class="row">
        <div class="col-12 col-md-6 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4>Edit User</h4>
                </div>
                <form id="formData">
                    <input type="hidden" name="type" value="edit">
                    <div class="card-body">
                        <div class="form-group">
                            <label>Username</label>
                            <input name="username" type="text" class="form-control" value="{{ $data->username }}"
                                readonly>
                        </div>
                        <div class="form-group">
                            <label>Nama</label>
                            <input name="name" type="text" class="form-control" value="{{ $data->name }}">
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input name="email" type="text" class="form-control" value="{{ $data->email }}">
                            <small>
                                Anda tidak harus mengisi email.
                            </small>
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
                                            @if(in_array($m['id'],$check))
                                            <input type="checkbox" name="permission[]" class="custom-control-input"
                                                id="permission_{{ $m['id'] }}" value="{{ $m['id'] }}" checked>
                                            @else
                                            <input type="checkbox" name="permission[]" class="custom-control-input"
                                                id="permission_{{ $m['id'] }}" value="{{ $m['id'] }}">
                                            @endif
                                            <label class="custom-control-label"
                                                for="permission_{{ $m['id'] }}">{{ $m['name'] }}</label>
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
                                <button type="button" class="btn btn-block btn-outline-danger"
                                    onclick="window.location = '{{ url('dashboard/master/user-management/list') }}'">
                                    <i class="fas fa-times mr-2"></i>Cancel
                                </button>
                            </div>
                            <div class="col-sm-12 col-lg-2 mt-2 mb-lg-0">
                                <button type="submit" id="btnBaru" class="btn btn-block btn-success">
                                    <i class="fas fa-check mr-2"></i>Simpan
                                </button>
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
        const iSystem = $('#iSystem');

        $(document).ready(function () {
            iSystem.val('{{ $data->system }}');
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
                                icon: 'error',
                                title: 'Data Gagal Tersimpan',
                                text: 'Silahkan coba lagi atau hubungi WAVE Solusi Indonesia',
                            });
                        }
                    }
                })
            })
        });
</script>
@endsection
