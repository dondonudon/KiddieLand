@extends('dashboard.layout')

@section('page_menu')
<li class="nav-item {{ (request()->segment(4) == null) ? 'active' : '' }}">
    <a href="{{ url(request()->segment(1).'/'.request()->segment(2).'/'.request()->segment(3)) }}" class="nav-link">
        <i class="fas fa-plus-circle mr-2" style="font-size: x-large; vertical-align: middle;"></i>
        <div class="d-none d-lg-inline-block d-xl-inline-block">Tambah {{ ucfirst(request()->segment(3)) }}</div>
    </a>
</li>
<li class="nav-item {{ (request()->segment(4) == 'list') ? 'active' : '' }}">
    <a href="{{ url(request()->segment(1).'/'.request()->segment(2).'/'.request()->segment(3)) }}/list"
        class="nav-link">
        <i class="fas fa-table mr-2" style="font-size: x-large; vertical-align: middle;"></i>
        <span class="d-none d-lg-inline-block d-xl-inline-block">
            Daftar {{ ucfirst(request()->segment(3)) }}
        </span>
    </a>
</li>
@endsection

@section('content')
<div class="section-body">
    <div class="row">
        <div class="col-12 col-md-6 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4>Edit {{ ucfirst(request()->segment(3)) }}</h4>
                </div>
                <form id="formData">
                    <input type="hidden" name="type" value="edit">
                    <input type="hidden" id="iID" name="id" value="{{ $data->id }}">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="iKodeSiswa">Kode Pegawai</label>
                            <input type="text" class="form-control" id="iKodeSiswa" name="kode_guru"
                                value="{{ $data->kode_guru }}">
                        </div>
                        <div class="form-group">
                            <label for="iNama">Nama</label>
                            <input type="text" class="form-control" id="iNama" name="nama" value="{{ $data->nama }}">
                        </div>
                        <div class="form-group">
                            <label for="iAlamat">Alamat</label>
                            <input type="text" class="form-control" id="iAlamat" name="alamat"
                                value="{{ $data->alamat }}">
                        </div>
                        <div class="form-group">
                            <label for="iJenKel">Jenis Kelamin</label>
                            <select class="form-control" id="iJenKel" name="jenkel" value="{{ $data->jenis_kelamin }}">
                                <option>--</option>
                                <option value="1">Laki-laki</option>
                                <option value="2">Perempuan</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="iTempatLahir">Tempat Lahir</label>
                            <input type="text" class="form-control" id="iTempatLahir" name="tempat_lahir"
                                value="{{ $data->tempat_lahir }}">
                        </div>
                        <div class="form-group">
                            <label for="iTglLahir">Tgl Lahir</label>
                            <input type="text" class="form-control" id="iTglLahir" name="tgl_lahir"
                                value="{{ date('d-m-Y',strtotime($data->tgl_lahir)) }}">
                        </div>
                        <div class="form-group">
                            <label for="iAgama">Agama</label>
                            <select class="form-control" id="iAgama" name="agama" value="{{ $data->agama }}">
                                <option>--</option>
                                <option value="1">Islam</option>
                                <option value="2">Kristen</option>
                                <option value="3">Katholik</option>
                                <option value="4">Hindu</option>
                                <option value="5">Budha</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="iJabatan">Jabatan</label>
                            <input type="text" class="form-control" id="iJabatan" name="jabatan"
                                value="{{ $data->jabatan }}">
                        </div>
                        <div class="form-group">
                            <label for="iKeterangan">Keterangan</label>
                            <input type="text" class="form-control" id="iKeterangan" name="keterangan"
                                value="{{ $data->ket }}">
                        </div>
                    </div>
                    <div class="card-footer bg-whitesmoke">
                        <div class="row justify-content-end">
                            <div class="col-sm-12 col-lg-2 mt-2 mb-lg-0">
                                <button type="button" class="btn btn-block btn-outline-danger"
                                    onclick="window.location = '{{ url()->previous() }}'">
                                    <i class="fas fa-arrow-left mr-2"></i>Kembali
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
        const iTglLahir = $('#iTglLahir').daterangepicker({
            singleDatePicker: true,
            locale: {
                format: 'DD-MM-YYYY'
            }
        });
        const iJenKel = $('#iJenKel');
        const iAgama = $('#iAgama');

        $(document).ready(function () {
            iJenKel.val('{{ $data->jenis_kelamin }}');
            $('#listTable').DataTable({
                responsive: true
            });

            iAgama.val('{{ $data->agama }}');
            $('#listTable').DataTable({
                responsive: true
            });

            formData.submit(function (e) {
                e.preventDefault();
                $.ajax({
                    url: '{{ url('dashboard/master/pegawai/submit') }}',
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
                                    window.location = '{{ url()->previous() }}';
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
