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
                </div>
                <form id="formData">
                    <input type="hidden" name="type" value="baru">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12 col-md-5 col-lg-4">
                                <div class="form-group">

                                    <table class="table table-striped table-bordered" id="myTable">
                                        <thead>
                                            <th>Nama</th>
                                            <th>Jumlah</th>
                                            <th>Supply</th>
                                            <th>Harga</th>
                                        </thead>
                                        <input type="text" name="no_po" value="{{$mst->no_po}}" readonly>
                                        
                                        <tbody>
                                            <?php $i = 1; ?>
                                            @foreach ($trn as $item)
                                            <tr>
                                                {{-- <input type="hidden" name="kode_seragam" value="{{$item->no_po}}">
                                                --}}
                                                <td><input type="hidden" id="kode_seragam_<?php echo $i; ?>"
                                                        value="{{$item->kode_seragam}}">{{$item->nama_seragam}}
                                                    <input type="hidden" id="a_<?php echo $i; ?>" name="kode_seragam[]">
                                                </td>
                                                <td><input type="hidden" id="qty_<?php echo  $i; ?>"
                                                        value="{{$item->qty}}">{{$item->qty}}</td>
                                                <td><input type="text" id="qty_supply_<?php echo  $i; ?>"
                                                        onchange="hitung(<?php echo $i; ?>)" required>
                                                </td>
                                                <td><input type="text" id="harga_<?php echo  $i; ?>" required
                                                        onchange="gabung(<?php echo $i; ?>)">
                                                </td>

                                            </tr>
                                            <?php $i ++; ?>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
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

    function hitung(i){
        let qty             = document.getElementById("qty_"+i).value;
        let qty_supply      = document.getElementById("qty_supply_"+i).value;
        if (qty_supply>qty){
            Swal.fire({
                icon: 'error',
                title: 'Cek data anda',
                text: 'Supply melebihi demmand',
            });
            document.getElementById("qty_supply_"+i).value = '';
        }
    }

    function gabung(i){
        let kode_seragam    = document.getElementById("kode_seragam_"+i).value;
        let qty             = document.getElementById("qty_"+i).value;
        let qty_supply      = document.getElementById("qty_supply_"+i).value;
        let harga           = document.getElementById("harga_"+i).value;
        document.getElementById("a_"+i).value = kode_seragam+"#"+qty+"#"+qty_supply+"#"+harga;
        // let gabung          = kode_seragam+"#"+qty+"#"+qty_supply+"#"+harga;
        // console.log(gabung);
    }

    $(document).ready(function () {
        formData.submit(function (e) {
            e.preventDefault();
            $.ajax({
                url: '{{ url('dashboard/transaksi/receiving/submit') }}',
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
                                window.location = '{{ url('dashboard/transaksi/receiving') }}';
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
