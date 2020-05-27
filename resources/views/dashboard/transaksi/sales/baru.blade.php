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
                    <h4>Tambah {{ ucfirst(request()->segment(3)) }} Baru</h4>
                </div>
                <form id="formData">
                    <input type="hidden" name="type" value="baru">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12 col-md-5 col-lg-4">
                                <div class="form-group">
                                    <label for="iSiswa">Nama Siswa</label>
                                    <select style="width: 100%" id="iSiswa" name="siswa" required></select>
                                </div>
                            </div>
                            <div class="col-sm-1 col-md-1 col-lg-1">
                                <input type="hidden" class="form-control" id="siswa">
                                <input type="hidden" class="form-control" id="kode_siswa">
                                <input class="btn btn-block btn-info" type="button" onclick="doTheInsert1()"
                                    value="Insert">
                            </div>
                            <div class="col-sm-12 col-md-5 col-lg-4">
                                <div class="form-group">
                                    <label for="iSeragam">Seragam</label>
                                    <select style="width: 100%" id="iSeragam" required></select>
                                </div>
                            </div>
                            <div class="col-sm-1 col-md-1 col-lg-1">
                                <input type="hidden" class="form-control" id="seragam">
                                <input type="hidden" class="form-control" id="kode">
                                <input type="hidden" class="form-control" id="stock">
                                <input type="hidden" class="form-control" id="harga_jual">
                                <input class="btn btn-block btn-info" type="button" onclick="doTheInsert()"
                                    value="Insert">
                            </div>
                        </div>

                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6 col-md-5 col-lg-4">
                                <div class="form-group">

                                    <table class="table table-striped table-bordered" id="myTable1">
                                        <thead>
                                            <th>Nama Siswa</th>
                                            <th>Aksi</th>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-sm-2 col-md-1 col-lg-1">
                                <div class="form-group">

                                    <table class="table table-striped table-bordered" id="myTable">
                                        <thead>
                                            <th>Seragam</th>
                                            <th>Stok</th>
                                            <th>Harga</th>
                                            <th>Qty</th>
                                            <th>Aksi</th>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>
            </div>
            <script>

            </script>
            <div class="card-footer bg-whitesmoke">
                <div class="row justify-content-end">
                    <div class="col-sm-12 col-lg-2 mt-2 mb-lg-0">
                        <button type="submit" class="btn btn-block btn-success"><i
                                class="fas fa-check mr-2"></i>Simpan</button>
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
    let iSiswa = $('#iSiswa');
    let iSeragam = $('#iSeragam');
    var i = 1;
    // onclick='remove("+i+")';'
    function doTheInsert1() {
        $('#myTable1').append("<tr id='tr_"+i+"'><td><input type='hidden' id=kode_siswa_"+i+" value="+kode_siswa+" ><input id=seragam_"+i+" value="+siswa+" readonly></td><td><input class='btn btn-block btn-info' type='button' value='Hapus' onclick='remove("+i+")';'></td></tr>");
        i++
    }

    function doTheInsert() {
        $('#myTable').append("<tr id='tr_"+i+"'><td><input type='hidden' id=kode_"+i+" value="+kode+" ><input id=seragam_"+i+" value="+seragam+" readonly></td><td width=\"20\"><input id=stock_"+i+" value="+stock+" readonly></td><td width=\"20\"><input id=harga_"+i+" value="+harga_jual+" readonly></td><td><input type='text' id=qty_"+i+" onchange='gabung("+i+")''><input type='hidden' name='kode_seragam[]' id='gabung_"+i+"'></td><td><input class='btn btn-block btn-info' type='button' value='Hapus' onclick='remove("+i+")';'></td></tr>");
        i++
    }

    function remove(i){
        $("#tr_"+i).remove();
    }

    function gabung(i){
        // let kode_siswa = document.getElementById("kode_siswa_"+i).value;
        let stock             = document.getElementById("stock_"+i).value;
        let kode = document.getElementById("kode_"+i).value;
        let qty = document.getElementById("qty_"+i).value;
        let harga = document.getElementById("harga_"+i).value;
        if (qty>stock){
            Swal.fire({
                icon: 'error',
                title: 'Cek data anda',
                text: 'Supply melebihi demmand',
            });
            document.getElementById("qty_"+i).value = '';
        }else{
            document.getElementById("gabung_"+i).value = kode_siswa+"#"+kode+"#"+qty+"#"+harga;
        let gabung = document.getElementById("gabung_"+i).value;
        }

        // console.log(gabung);
    }


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

        iSeragam.select2({
            ajax: {
                url: '{{ url('seragam') }}',
                dataType: 'json',
                data: function (params) {
                    return {
                        search: params.term,
                    }
                }
            }
        });

        $('#iSeragam').on('select2:select', function (e) {
            var data = e.params.data;
            // console.log(data);
            document.getElementById("seragam").value = data['text'];
            document.getElementById("kode").value = data['id'];
            document.getElementById("stock").value = data['stock'];
            document.getElementById("harga_jual").value = data['harga_jual'];
            seragam = document.getElementById("seragam").value;
            kode = document.getElementById("kode").value;
            stock = document.getElementById("stock").value;
            harga_jual = document.getElementById("harga_jual").value;
        });

        $('#iSiswa').on('select2:select', function (e) {
            var data = e.params.data;
            // console.log(data);
            document.getElementById("siswa").value = data['text'];
            document.getElementById("kode_siswa").value = data['id'];
            siswa = document.getElementById("siswa").value;
            kode_siswa = document.getElementById("kode_siswa").value;
        });


        formData.submit(function (e) {
            e.preventDefault();
            $.ajax({
                url: '{{ url('dashboard/transaksi/salesinvoice/submit') }}',
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
                                // window.location.reload();
                                window.location = '{{ url('dashboard/transaksi/salesinvoice') }}';
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
                },
                error: function (response) {
                    console.log(response);
                }
            })
        })
    });
</script>
@endsection
