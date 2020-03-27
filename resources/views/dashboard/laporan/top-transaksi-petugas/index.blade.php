@extends('dashboard.layout2')

@section('content')
    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-6 col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Data {{ ucfirst(request()->segment(3)) }}</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-sm-12 col-md-6 col-lg-2">
                                <div class="form-group">
                                    <label for="iTanggal">Filter Tanggal</label>
                                    <input type="text" class="form-control text-right" id="iTanggal" name="tanggal">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="thead-dark table-sm table-striped table-bordered" id="listTable" style="width: 100%"></div>
                    </div>
                    <div class="card-footer">
                        <div class="row justify-content-end">
                            <div class="col-sm-12 col-lg-2 mt-2 mb-lg-0">
                                <div class="dropdown d-inline">
                                    <button class="btn btn-block btn-danger dropdown-toggle" type="button" id="btnExport" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" disabled>
                                        <i class="fas fa-file-export mr-2"></i> EXPORT
                                    </button>
                                    <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; transform: translate3d(0px, 28px, 0px);">
                                        <a class="dropdown-item has-icon" href="#" id="btnPDF"><i class="far fa-file-pdf"></i> PDF</a>
{{--                                        <a class="dropdown-item has-icon" href="#" id="btnExcel"><i class="far fa-file-excel"></i> EXCEL</a>--}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        const btnExport = $('#btnExport');
        const btnPDF = $('#btnPDF');
        const btnExcel = $('#btnExcel');
        const iTanggal = $('#iTanggal');

        $(document).ready(function () {
            iTanggal.daterangepicker({
                singleDatePicker: true,
                locale: {
                    format: 'DD MMMM YYYY'
                }
            });

            let listTable = new Tabulator("#listTable", {
                resizableColumns: false,
                placeholder: 'No Data Available',
                layout: "fitData",
                selectable: 0,
                ajaxURL: "{{ url('dashboard/laporan/top-transaksi-petugas/data') }}",
                ajaxParams: {
                    tanggal: iTanggal.data('daterangepicker').startDate.format('YYYY-MM-DD')
                },
                ajaxConfig: {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content'),
                    }
                },
                dataLoaded: function(data) {
                    if (data.length > 0) {
                        // console.log(data);
                        btnExport.removeAttr('disabled');
                    } else {
                        btnExport.attr('disabled',true);
                    }
                },
                columns: [
                    {formatter:"rownum",align:"center"},
                    {title:"PETUGAS TIKET",field:"petugas"},
                    {title:"KOTA",field:"kota"},
                    {title:"BUS",field:"bus"},
                    {title:"TOTAL TRANSAKSI",field:"total",
                        formatter: function (row) {
                            let numb = numeral(row.getData().total).format('0,0');
                            return '<div class="text-right">'+numb+'</div>';
                        },
                    },
                ]
            });

            iTanggal.on('apply.daterangepicker', function(ev, picker) {
                listTable.setData("{{ url('dashboard/laporan/top-transaksi-petugas/data') }}", {tanggal: picker.startDate.format('YYYY-MM-DD')});
            });

            btnPDF.click(function (e) {
                e.preventDefault();
                let tanggal = iTanggal.data('daterangepicker').startDate.format('YYYY-MM-DD');
                window.open('{{ url('dashboard/laporan/top-transaksi-petugas/export/pdf') }}/'+tanggal);
            });
        });
    </script>
@endsection
