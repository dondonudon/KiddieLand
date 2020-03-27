@extends('dashboard.layout')

@php($sesi = request()->session())

@section('title','Profile')

@section('content')
    <div class="section-body">
        <h2 class="section-title">Hi, {{ ucfirst( $data['user']->name )  }}!</h2>
        <p class="section-lead">
            Change information about yourself on this page.
        </p>

        <div class="row mt-sm-4">
            <div class="col-12 col-md-12 col-lg-5">
                <div class="card profile-widget">
                    <div class="profile-widget-header">
                        <img alt="image" src="{{ (isset($data['profile']->foto)) ? url('storage/'.$data['profile']->foto) : asset('assets/img/avatar/avatar-1.png') }}" class="rounded-circle profile-widget-picture">
                        <div class="profile-widget-items">
                            <div class="profile-widget-item">
                                <div class="profile-widget-item-label">Terdaftar sejak</div>
                                <div class="profile-widget-item-value">{{ date('d F Y', strtotime( $data['user']->created_at )) }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="profile-widget-description">
                        <table class="table table-sm table-striped table-borderless">
                            <tbody>
                            <tr>
                                <th>Nama</th>
                                <td>{{ $data['user']->name }}</td>
                            </tr>
                            <tr>
                                <th>Username</th>
                                <td>{{ $data['user']->username }}</td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>{{ $data['user']->email }}</td>
                            </tr>
                            <tr>
                                <th>No Telp</th>
                                <td>{{ $data['user']->no_hp }}</td>
                            </tr>
                            <tr>
                                <th>System</th>
                                <td>{{ ($data['user']->system == 1) ? 'Android' : 'Website' }}</td>
                            </tr>
                            </tbody>
                        </table>
                        <hr>
                        @if($data['profile'] !== null)
                            <table class="table table-sm table-striped table-borderless">
                                <tbody>
                                <tr>
                                    <th>No SK</th>
                                    <td>{{ $data['profile']->no_sk }}</td>
                                </tr>
                                <tr>
                                    <th>No SPK</th>
                                    <td>{{ $data['profile']->no_spk }}</td>
                                </tr>
                                <tr>
                                    <th>Tempat Lahir</th>
                                    <td>{{ $data['profile']->tempat_lahir }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal Lahir</th>
                                    <td>{{ $data['profile']->tgl_lahir }}</td>
                                </tr>
                                <tr>
                                    <th>Jenis Kelamin</th>
                                    <td>{{ $data['profile']->jenis_kelamin }}</td>
                                </tr>
                                <tr>
                                    <th>Alamat</th>
                                    <td>{{ $data['profile']->alamat }}</td>
                                </tr>
                                <tr>
                                    <th>Jabatan</th>
                                    <td>{{ $data['profile']->jabatan }}</td>
                                </tr>
                                <tr>
                                    <th>Agama</th>
                                    <td>{{ $data['profile']->agama }}</td>
                                </tr>
                                <tr>
                                    <th>No KTP</th>
                                    <td>{{ $data['profile']->no_ktp }}</td>
                                </tr>
                                <tr>
                                    <th>Tingkat Pendidikan</th>
                                    <td>{{ $data['profile']->tingkat_pendidikan }}</td>
                                </tr>
                                <tr>
                                    <th>Jurusan</th>
                                    <td>{{ $data['profile']->jurusan }}</td>
                                </tr>
                                <tr>
                                    <th>Keterangan</th>
                                    <td>{{ $data['profile']->keterangan }}</td>
                                </tr>
                                <tr>
                                    <th>Foto</th>
                                    <td>{{ $data['profile']->foto }}</td>
                                </tr>
                                </tbody>
                            </table>
                        @else
                            <div class="alert alert-info" role="alert">
                                Silahkan lengkapi Data Profil anda
                            </div>
                        @endif
                    </div>
                    <div class="card-footer text-center">
                        <a href="{{ url('reset-password') }}" class="btn btn-warning">
                            <i class="fas fa-lock mr-2"></i> Ganti Password
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-12 col-lg-7">
                <div class="card">
                    <form id="formEdit">
                        <div class="card-header">
                            <h4>Edit Profile</h4>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="iUsername">Username</label>
                                <input type="text" id="iUsername" name="username" class="form-control" value="{{ $data['user']->username }}" readonly>
                            </div>

                            <div class="row align-items-center">
                                <div class="col-4">
                                    <img alt="image" src="{{ asset('assets/img/avatar/avatar-1.png') }}" class="rounded-circle profile-widget-picture" id="iPreviewFoto" style="width: 100%">
                                </div>
                                <div class="col-8">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="iFoto" name="foto">
                                        <label class="custom-file-label" for="iFoto" id="iPreviewFilename">Pilih Foto</label>
                                    </div>
                                    <small class="mt-5">Ukuran maksimal 500kb.</small>
                                    <small class="mt-5">Format yang diperbolehkan .jpeg atau .jpg.</small>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="iName">Nama</label>
                                        <input type="text" id="iName" name="name" class="form-control" value="{{ $data['user']->name }}" required="">
                                    </div>

                                    <div class="form-group">
                                        <label for="iEmail">Email</label>
                                        <input type="email" id="iEmail" name="email" class="form-control" value="{{ $data['user']->email }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="iNoTelp">No Telp</label>
                                        <input type="text" id="iNoTelp" name="no_hp" class="form-control" value="{{ $data['user']->no_hp }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="iNoTelp">No SK</label>
                                        <input type="text" id="iNoSk" name="no_sk" class="form-control" value="{{ $data['profile']->no_sk ?? '' }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="iNoTelp">No SPK</label>
                                        <input type="text" id="iNoSpk" name="no_spk" class="form-control" value="{{ $data['profile']->no_spk ?? '' }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="iTempatLahir">Tempat Lahir</label>
                                        <input type="text" id="iTempatLahir" name="no_spk" class="form-control" value="{{ $data['profile']->tempat_lahir ?? '' }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="iTglLahir">Tanggal Lahir</label>
                                        <input type="text" id="iTglLahir" name="tempat_lahir" class="form-control" value="{{ $data['profile']->tgl_lahir ?? '' }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="iJenisKelamin">Jenis Kelamin</label>
                                        <select class="form-control" id="iJenisKelamin">
                                            <option value="">Pilih Jenis Kelamin</option>
                                            <option value="l">Laki-laki</option>
                                            <option value="p">Perempuan</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="iAlamat">Alamat</label>
                                        <textarea class="form-control" id="iAlamat" rows="4" style="height: 137px"></textarea>
                                    </div>

                                    <div class="form-group">
                                        <label for="iJabatan">Jabatan</label>
                                        <input type="text" id="iJabatan" name="jabatan" class="form-control" value="{{ $data['profile']->jabatan ?? '' }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="iAgama">Agama</label>
                                        <select class="form-control" id="iAgama">
                                            <option value="">Pilih Agama</option>
                                            <option value="islam">Islam</option>
                                            <option value="kristen">Kristen</option>
                                            <option value="katolik">Katolik</option>
                                            <option value="hindu">Hindu</option>
                                            <option value="budha">Buddha</option>
                                            <option value="khonghucu">Khonghucu</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="iNoKtp">No KTP</label>
                                        <input type="text" id="iNoKtp" name="no_ktp" class="form-control" value="{{ $data['profile']->no_ktp ?? '' }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="iTingkatPendidikan">Tingkat Pendidikan</label>
                                        <select class="form-control" id="iTingkatPendidikan">
                                            <option value="">Pilih Pendidikan Terakhir</option>
                                            <option value="sd">SD</option>
                                            <option value="smp">SMP</option>
                                            <option value="sma/smk">SMA / SMK</option>
                                            <option value="d3">D3</option>
                                            <option value="s1">S1</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="iJurusan">Jurusan</label>
                                        <input type="text" id="iJurusan" name="jurusan" class="form-control" value="{{ $data['profile']->jurusan ?? '' }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="iKeterangan">Keterangan Tambahan</label>
                                        <input type="text" id="iKeterangan" name="keterangan" class="form-control" value="{{ $data['profile']->keterangan ?? '' }}">
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="card-footer text-right">
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        let formEdit = $('#formEdit');
        const iUsername = $('#iUsername');
        const iPreviewFoto = $('#iPreviewFoto');
        const iPreviewFilename = $('#iPreviewFilename');
        const iFoto = document.getElementById('iFoto');
        const iName = $('#iName');
        const iEmail = $('#iEmail');
        const iNoTelp = $('#iNoTelp');
        const iNoSk = $('#iNoSk');
        const iNoSpk = $('#iNoSpk');
        const iTempatLahir = $('#iTempatLahir');
        const iTglLahir = $('#iTglLahir');
        const iJenisKelamin = $('#iJenisKelamin');
        const iAlamat = $('#iAlamat');
        const iJabatan = $('#iJabatan');
        const iAgama = $('#iAgama');
        const iNoKtp = $('#iNoKtp');
        const iTingkatPendidikan = $('#iTingkatPendidikan');
        const iJurusan = $('#iJurusan');
        const iKeterangan = $('#iKeterangan');

        $(document).ready(function () {
            iFoto.addEventListener('change',function () {
                const allowed = ['.jpeg','.jpg'];
                let input = this;
                let image = input.files[0];
                let value = input.value;
                let length = value.length;
                let index = value.lastIndexOf(".");
                let extension = value.substring(index,length).toLowerCase();
                if (!allowed.includes(extension)) {

                    alert('Format yang diperbolehkan yaitu .jpeg atau .jpg');
                } else {
                    if ((image.size/1000) > 200) {
                        input.value = '';
                        alert('Ukuran maksimal 500kb. Ukuran file anda '+(image.size/1000)+'Kb');
                    } else {
                        iPreviewFilename.html(value);
                        // console.log(image);

                        let reader = new FileReader();
                        reader.onload = function(e) {
                            iPreviewFoto.attr('src', e.target.result);
                        };
                        reader.readAsDataURL(input.files[0]);
                    }
                }
            });
            iTglLahir.daterangepicker({
                singleDatePicker: true,
                locale: {
                    format: 'DD MMMM YYYY'
                }
            });
            formEdit.submit(function (e) {
                e.preventDefault();
                let formData = new FormData();
                formData.append('username',iUsername.val());
                formData.append('foto',iFoto.files[0]);
                formData.append('name',iName.val());
                formData.append('email',iEmail.val());
                formData.append('no_hp',iNoTelp.val());
                formData.append('no_sk',iNoSk.val());
                formData.append('no_spk',iNoSpk.val());
                formData.append('tempat_lahir',iTempatLahir.val());
                formData.append('tgl_lahir',iTglLahir.data('daterangepicker').startDate.format('YYYY/MM/DD'));
                formData.append('jenis_kelamin',iJenisKelamin.val());
                formData.append('alamat',iAlamat.val());
                formData.append('jabatan',iJabatan.val());
                formData.append('agama',iAgama.val());
                formData.append('no_ktp',iNoKtp.val());
                formData.append('tingkat_pendidikan',iTingkatPendidikan.val());
                formData.append('jurusan',iJurusan.val());
                formData.append('keterangan',iKeterangan.val());
                $.ajax({
                    url: '{{ url('dashboard/profile/submit') }}',
                    method: 'post',
                    processData: false,
                    contentType: false,
                    data: formData,
                    success: function (response) {
                        if (response === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Data Tersimpan',
                                showConfirmButton: false,
                                timer: 1000,
                                onClose: function () {
                                    window.location.reload();
                                }
                            });
                        } else {
                            console.log(response);
                            Swal.fire({
                                icon: 'warning',
                                title: 'Gagal Tersimpan',
                                text: 'Pastikan koneksi internet anda tidak bermasalah dan coba kembali. Hubungi WAVE Solusi Indonesia jika tetap bermasalah.',
                            });
                        }
                    },
                    error: function (response) {
                        console.log(response);
                        Swal.fire({
                            icon: 'error',
                            title: 'System Error',
                            text: 'Screenshot atau foto halaman ini dan hubungi WAVE Solusi Indonesia',
                        });
                    }
                })
            })
        });
    </script>
@endsection