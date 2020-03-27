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
                        <h4 class="ml-3">Transaksi per Jenis Tiket</h4>
                        <div class="thead-dark table-sm table-striped table-bordered" id="listTable" style="width: 100%"></div>

                        <h4 class="mt-5 ml-3">Transaksi Tiket Offline</h4>
                        <div class="thead-dark table-sm table-striped table-bordered" id="listTableOffline" style="width: 100%"></div>
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
                columnHeaderVertAlign: 'center',
                headerSort: false,
                placeholder: 'No Data Available',
                layout: "fitData",
                selectable: 0,
                ajaxURL: "{{ url('dashboard/laporan/transaksi-per-jenis/data') }}",
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
                    {
                        title:"JENIS TRANSAKSI",field:"jenis",frozen:"true",
                        bottomCalcFormatter: function () {
                            return "TOTAL";
                        }
                    },
                    {
                        title:"JAM TRANSAKSI",
                        columns:[
                            {title:"00", field:"j_00", align:"right", width:60, bottomCalc:"sum"},
                            {title:"01", field:"j_01", align:"right", width:60, bottomCalc:"sum"},
                            {title:"02", field:"j_02", align:"right", width:60, bottomCalc:"sum"},
                            {title:"03", field:"j_03", align:"right", width:60, bottomCalc:"sum"},
                            {title:"04", field:"j_04", align:"right", width:60, bottomCalc:"sum"},
                            {title:"05", field:"j_05", align:"right", width:60, bottomCalc:"sum"},
                            {title:"06", field:"j_06", align:"right", width:60, bottomCalc:"sum"},
                            {title:"07", field:"j_07", align:"right", width:60, bottomCalc:"sum"},
                            {title:"08", field:"j_08", align:"right", width:60, bottomCalc:"sum"},
                            {title:"09", field:"j_09", align:"right", width:60, bottomCalc:"sum"},
                            {title:"10", field:"j_10", align:"right", width:60, bottomCalc:"sum"},
                            {title:"11", field:"j_11", align:"right", width:60, bottomCalc:"sum"},
                            {title:"12", field:"j_12", align:"right", width:60, bottomCalc:"sum"},
                            {title:"13", field:"j_13", align:"right", width:60, bottomCalc:"sum"},
                            {title:"14", field:"j_14", align:"right", width:60, bottomCalc:"sum"},
                            {title:"15", field:"j_15", align:"right", width:60, bottomCalc:"sum"},
                            {title:"16", field:"j_16", align:"right", width:60, bottomCalc:"sum"},
                            {title:"17", field:"j_17", align:"right", width:60, bottomCalc:"sum"},
                            {title:"18", field:"j_18", align:"right", width:60, bottomCalc:"sum"},
                            {title:"19", field:"j_19", align:"right", width:60, bottomCalc:"sum"},
                            {title:"20", field:"j_20", align:"right", width:60, bottomCalc:"sum"},
                            {title:"21", field:"j_21", align:"right", width:60, bottomCalc:"sum"},
                            {title:"22", field:"j_22", align:"right", width:60, bottomCalc:"sum"},
                            {title:"23", field:"j_23", align:"right", width:60, bottomCalc:"sum"},
                        ],
                    },
                    {title:"TOTAL TRANSAKSI",field:"total_transaksi",align:"right", bottomCalc:"sum"},
                    {
                        title:"TOTAL RUPIAH",field:"total_rupiah", align:"right",
                        formatter: "money", formatterParams: {
                            decimal:",",
                            thousand:".",
                            precision:false,
                        },
                        bottomCalc:"sum", bottomCalcFormatter: "money", bottomCalcFormatterParams: {
                            decimal:",",
                            thousand:".",
                            precision:false,
                        }
                    },
                ]
            });

            let listTableOffline = new Tabulator("#listTableOffline", {
                resizableColumns: false,
                columnHeaderVertAlign: 'center',
                headerSort: false,
                placeholder: 'No Data Available',
                layout: "fitData",
                selectable: 0,
                ajaxURL: "{{ url('dashboard/laporan/transaksi-per-jenis/data-offline') }}",
                ajaxParams: {
                    tanggal: iTanggal.data('daterangepicker').startDate.format('YYYY-MM-DD')
                },
                ajaxConfig: {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content'),
                    }
                },
                // dataLoaded: function(data) {
                //     if (data.length > 0) {
                //         // console.log(data);
                //         btnExport.removeAttr('disabled');
                //     } else {
                //         btnExport.attr('disabled',true);
                //     }
                // },
                columns: [
                    {formatter:"rownum",align:"center"},
                    {
                        title:"PETUGAS TIKET",field:"username",
                        bottomCalcFormatter: function () {
                            return "TOTAL";
                        }
                    },
                    {title:"TOTAL TRANSAKSI",field:"total_transaksi",align:"right", bottomCalc:"sum"},
                    {title:"TOTAL RUPIAH",field:"total_rupiah", align:"right",
                        formatter: "money", formatterParams: {
                            decimal:",",
                            thousand:".",
                            precision:false,
                        },
                        bottomCalc:"sum", bottomCalcFormatter: "money", bottomCalcFormatterParams: {
                            decimal:",",
                            thousand:".",
                            precision:false,
                        }
                    },
                ]
            });

            iTanggal.on('apply.daterangepicker', function(ev, picker) {
                listTable.setData("{{ url('dashboard/laporan/transaksi-per-jenis/data') }}", {tanggal: picker.startDate.format('YYYY-MM-DD')});
                listTableOffline.setData("{{ url('dashboard/laporan/transaksi-per-jenis/data-offline') }}", {tanggal: picker.startDate.format('YYYY-MM-DD')});
            });

            btnPDF.click(function (e) {
                e.preventDefault();
                let tanggal = iTanggal.data('daterangepicker').startDate.format('YYYY-MM-DD');
                window.open('{{ url('dashboard/laporan/transaksi-per-jenis/export/pdf') }}/'+tanggal);
            });
        });
    </script>
@endsection
