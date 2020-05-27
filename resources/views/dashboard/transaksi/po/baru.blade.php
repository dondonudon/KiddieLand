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
                                    <label for="iSupplier">Nama Supplier</label>
                                    <select style="width: 100%" id="iSupplier" name="supplier" required></select>
                                </div>
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
                                <input class="btn btn-block btn-info" type="button" onclick="doTheInsert()"
                                    value="Insert">
                            </div>
                        </div>

                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12 col-md-5 col-lg-4">
                                <div class="form-group">

                                    <table class="table table-striped table-bordered" id="myTable">
                                        <thead>
                                            <th>Nama</th>
                                            <th>Jumlah</th>
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
    let iSupplier = $('#iSupplier');
    let iSeragam = $('#iSeragam');
    var i = 1;
    // onclick='remove("+i+")';'
    function doTheInsert() {
        $('#myTable').append("<tr id='tr_"+i+"'><td><input type='hidden' id=kode_"+i+" value="+kode+" ><input id=seragam_"+i+" value="+seragam+" ></td><td><input type='text' id=qty_"+i+" onchange='gabung("+i+")''><input type='hidden' name='kode_seragam[]' id='gabung_"+i+"'></td><td><input class='btn btn-block btn-info' type='button' value='Hapus' onclick='remove("+i+")';'></td></tr>");
        i++
    }

    function remove(i){
        $("#tr_"+i).remove();
    }

    function gabung(i){
        let kode = document.getElementById("kode_"+i).value;
        let qty = document.getElementById("qty_"+i).value;
        document.getElementById("gabung_"+i).value = kode+"#"+qty;
        let gabung = document.getElementById("qty_"+i).value;
        // console.log(qty1);
    }


    $(document).ready(function () {

        iSupplier.select2({
            ajax: {
                url: '{{ url('supplier') }}',
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
            seragam = document.getElementById("seragam").value;
            kode = document.getElementById("kode").value;
        });


        formData.submit(function (e) {
            e.preventDefault();
            $.ajax({
                url: '{{ url('dashboard/transaksi/purchaseorder/submit') }}',
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
                                window.location = '{{ url('dashboard/transaksi/purchaseorder') }}';
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
