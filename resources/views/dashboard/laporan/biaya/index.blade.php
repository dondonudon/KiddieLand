@extends('dashboard.layout')

@section('content')
<div class="section-body">
    <div class="row">
        <div class="col-12 col-md-6 col-lg-12">
            <div class="card">
                {{-- <div class="card-header">
                    <h4>{{ ucfirst(str_replace('-',' ',request()->segment(3))) }} Baru</h4>
            </div> --}}
            <form id="formData">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12 col-md-5 col-lg-4">
                            <div class="form-group">
                                <label for="iSiswa">Nama Siswa</label>
                                <select style="width: 100%" id="iSiswa" name="siswa" required></select>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-5 col-lg-4">
                            <div class="form-group">
                                <label for="iPeriode">Periode</label>
                                <select style="width: 100%" id="iPeriode" name="periode" required></select>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-2 col-lg-2">
                            <div class="form-group">
                                <input type="hidden" class="form-control text-right" id="iNama" name="nama" readonly>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-2 col-lg-2">
                            <div class="form-group">
                                <input type="hidden" class="form-control text-right" id="iTahun" name="tahun" readonly>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-whitesmoke">
                    <div class="row justify-content-end">
                        <div class="col-sm-12 col-lg-2 mt-2 mb-lg-0 text-center">
                            <button type="submit" class="btn btn-block btn-success" id="btnSubmit">
                                <i class="fas fa-check mr-2"></i>Export
                            </button>
                            <div class="spinner-border text-danger d-none" role="status" id="spinner">
                                <span class="sr-only">Loading...</span>
                            </div>
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
        const btnSubmit = $('#btnSubmit');
        const spinner = $('#spinner');

        const iNama = $('#iNama');
        const iPeriode = $('#iPeriode');

        const iSiswa = $('#iSiswa');
        const iTahun = $('#iTahun');

        $(document).ready(function () {
            iSiswa.select2({
                ajax: {
                    url: '{{ url('siswa') }}',
                    dataType: 'json',
                    data: function (params) {
                        return {
                            search: params.term,
                        }
                    }
                }
            });
            iPeriode.select2({
                ajax: {
                    url: '{{ url('thnajaran') }}',
                    dataType: 'json',
                    data: function (params) {
                        return {
                            search: params.term,
                        }
                    }
                }
            });


            iSiswa.change(function () {
                let kode_siswa = this.value;
                $.ajax({
                    url: '{{ url('siswa') }}/'+kode_siswa,
                    method: 'get',
                    success: function (response) {
                        iNama.val(response.nama);
                    },
                    error: function (response) {
                        console.log(response);
                    }
                })
            });

            iPeriode.change(function () {
                let id = this.value;
                $.ajax({
                    url: '{{ url('thnajaran') }}/'+id,
                    method: 'get',
                    success: function (response) {
                        iTahun.val(response.periode);
                    },
                    error: function (response) {
                        console.log(response);
                    }
                })
            });

            // formData.submit(function (e) {
            //     e.preventDefault();
            //     // console.log($(this).serialize());
            //     btnSubmit.addClass('d-none');
            //     spinner.removeClass('d-none');
            //     $.ajax({
            //         url: '{{ url('dashboard/laporan/biaya/submit') }}',
            //         method: 'post',
            //         data: $(this).serialize(),
            //         success: function (response) {
            //             if (response === 'success') {
            //                 Swal.fire({
            //                     icon: 'success',
            //                     title: 'Data Tersimpan',
            //                     showConfirmButton: false,
            //                     timer: 1000,
            //                     onClose: function () {
            //                         window.location.reload();
            //                     }
            //                 });
            //             } else {
            //                 console.log(response);
            //                 Swal.fire({
            //                     icon: 'error',
            //                     title: 'Data Gagal Tersimpan',
            //                     text: 'Pastikan nomor tiket belum pernah digunakan',
            //                 });
            //             }
            //         },
            //         error: function (response) {
            //             console.log(response);
            //             Swal.fire({
            //                 icon: 'error',
            //                 title: 'System Error',
            //             });
            //         },
            //         complete: function () {
            //             btnSubmit.removeClass('d-none');
            //             spinner.addClass('d-none');
            //         }
            //     })
            // })


            btnSubmit.click(function (e) {
                e.preventDefault();
                let siswa = 'kode1';
                window.open('{{ url('dashboard/laporan/biaya/export/pdf') }}/'+siswa);
            });
        });
</script>
@endsection
