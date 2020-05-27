@extends('dashboard.layout')

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
                            <div class="col-12 col-sm-12 col-md-6 col-lg-3">
                                <div class="form-group">
                                    <label for="iTanggal">Filter Tanggal</label>
                                    <input type="text" class="form-control text-right" id="iTanggal" name="tanggal" onfocus="blur()">
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
                                    <button type="button" class="btn btn-primary btn-block" id="btnView" disabled>
                                        <i class="fas fa-eye mr-2"></i> VIEW
                                    </button>
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
        const iTanggal = $('#iTanggal');
        const btnView = $('#btnView');

        $(document).ready(function () {
            iTanggal.daterangepicker({
                // singleDatePicker: true,
                startDate: moment().startOf('week'),
                endDate: moment().endOf('week'),
                locale: {
                    format: 'DD MMM YYYY'
                }
            });

            let listTable = new Tabulator("#listTable", {
                resizableColumns: false,
                placeholder: 'No Data Available',
                layout: "fitData",
                pagination: "remote",
                selectable: 1,
                ajaxURL: "{{ url('dashboard/problem/bus-report/data') }}",
                ajaxParams: {
                    start: iTanggal.data('daterangepicker').startDate.format('YYYY-MM-DD'),
                    end: iTanggal.data('daterangepicker').endDate.format('YYYY-MM-DD')
                },
                ajaxConfig: {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content'),
                    }
                },
                ajaxURLGenerator:function(url, config, params){
                    return url + "?page=" + params.page;
                },
                // ajaxResponse:function(url, params, response){
                //     console.log(response);
                //
                //     return response.tableData;
                // },
                dataLoaded: function(data) {
                    // console.log(data);
                },
                columns: [
                    {formatter:"rownum",align:"center"},
                    {
                        title:"STATUS",field:"status",
                        formatter: function (row) {
                            console.log(row.getData());
                            if (row.getData().status === 1) {
                                row.getElement().style.backgroundColor = "rgba(0,155,0,0.8)";
                                row.getElement().style.color = "white";
                                row.getElement().style.textAlign = "center";
                                return 'Sudah Dilihat';
                            } else {
                                row.getElement().style.backgroundColor = "rgba(0,0,0,0.5)";
                                row.getElement().style.color = "white";
                                row.getElement().style.textAlign = "center";
                                return 'Belum Dilihat';
                            }
                        }
                    },
                    {
                        title:"TANGGAL",field:"created_at", formatter:"datetime",
                        formatterParams:{
                            inputFormat:"YYYY-MM-DD HH:mm:ss",
                            outputFormat:"DD MMM YY - HH:mm:ss",
                            invalidPlaceholder:"(invalid date)",
                        }
                    },
                    {title:"BUS",field:"bus"},
                    {title:"KORIDOR",field:"koridor"},
                    {title:"TRIP",field:"trip"},
                    {title:"PETUGAS",field:"name"},
                    {title:"SHIFT",field:"shift"},
                    {title:"KETERANGAN",field:"keterangan"},
                ],
                rowSelectionChanged:function (data,rows) {
                    if (data.length === 1) {
                        btnView.removeAttr('disabled');
                    } else {
                        btnView.attr('disabled',true);
                    }
                }
            });

            iTanggal.on('apply.daterangepicker', function(ev, picker) {
                let start = picker.startDate.format('YYYY-MM-DD');
                let end = picker.endDate.format('YYYY-MM-DD');
                listTable.setData(
                    "{{ url('dashboard/problem/bus-report/data') }}",
                    {
                        start: start,
                        end: end,
                    }
                );
            });

            btnView.click(function (e) {
                e.preventDefault();
                let id = listTable.getSelectedData()[0].id;
                window.location = '{{ url('dashboard/problem/bus-report/view') }}/'+id;
            })
        });
    </script>
@endsection
