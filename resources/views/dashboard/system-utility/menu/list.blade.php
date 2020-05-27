@extends('dashboard.layout')

@section('content')
    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-6 col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Daftar Menu</h4>
                    </div>
                    <div class="card-body p-0">
                        <div class="thead-dark table-sm table-striped" id="listTable" style="width: 100%"></div>
                    </div>
                    <div class="card-footer bg-whitesmoke">
                        <div class="row justify-content-end">
                            <div class="col-sm-12 col-lg-2 mt-2 mt-lg-0">
                                <button type="button" id="btnEdit" class="btn btn-block btn-outline-info" disabled>
                                    <i class="fas fa-pencil-alt mr-2"></i>Edit
                                </button>
                            </div>
                            <div class="col-sm-12 col-lg-2 mt-2 mt-lg-0">
                                <a href="{{ url('dashboard/system/menu/baru') }}" class="btn btn-block btn-primary">
                                    <i class="fas fa-plus mr-2"></i>Tambah
                                </a>
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
        function updateOrder(table,cell) {
            let data = cell.getData();
            $.ajax({
                url: '{{ url('dashboard/system/menu/reorder') }}',
                method: 'post',
                data: {id:data.id, ord:data.ord},
                success: function(response) {
                    if (response === 'success') {
                        table.setData();
                    } else {
                        console.log(response);
                    }
                },
                error: function(response) {
                    console.log(response);
                }
            })
        }

        let btnEdit = $('#btnEdit');

        $(document).ready(function () {
            let listTable = new Tabulator("#listTable", {
                resizableColumns: false,
                layout: "fitDataStretch",
                selectable: 1,
                placeholder: 'No Data Available',
                ajaxURL: "{{ url('dashboard/system/menu/list/data') }}",
                ajaxConfig: {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content'),
                    }
                },
                groupBy: "group",
                initialSort:[
                    {column:"ord", dir:"asc"},
                ],
                columns: [
                    {title:"ORDER",field:"ord",editor:"input"},
                    {title:"NAMA MENU",field:"name"},
                    // {title:"NAMA GROUP",field:"group"},
                    {title:"NAMA SEGMENT",field:"segment_name"},
                    {title:"URL",field:"url"},
                ],
                rowSelectionChanged:function (data,rows) {
                    if (data.length === 1) {
                        btnEdit.removeAttr('disabled');
                    } else {
                        btnEdit.attr('disabled',true);
                    }
                },
                cellEdited: function(cell) {
                    updateOrder(listTable,cell);
                }
            });
            {{--let listTable = $('#listTable').DataTable({--}}
            {{--    scrollX: true,--}}
            {{--    order: [--}}
            {{--        [0,'asc'],--}}
            {{--        [4,'asc'],--}}
            {{--    ],--}}
            {{--    ajax: {--}}
            {{--        url: '{{ url('dashboard/system/menu/list/data') }}'--}}
            {{--    },--}}
            {{--    columns: [--}}
            {{--        {data: 'id_group'},--}}
            {{--        {data: 'name'},--}}
            {{--        {data: 'segment_name'},--}}
            {{--        {data: 'url'},--}}
            {{--        {data: 'ord'},--}}
            {{--    ],--}}
            {{--});--}}
            {{--$('#listTable tbody').on('click','tr',function () {--}}
            {{--    if ($(this).hasClass('selected')) {--}}
            {{--        $(this).removeClass('selected');--}}
            {{--        btnEdit.attr('disabled',true);--}}

            {{--        dataID = null;--}}
            {{--    } else {--}}
            {{--        listTable.$('tr.selected').removeClass('selected');--}}
            {{--        $(this).addClass('selected');--}}
            {{--        btnEdit.removeAttr('disabled');--}}

            {{--        let data = listTable.row('.selected').data();--}}
            {{--        dataID = data.id;--}}
            {{--    }--}}
            {{--});--}}

            btnEdit.click(function (e) {
                e.preventDefault();
                let id = listTable.getSelectedData()[0].id;
                window.location = '{{ url('dashboard/system/menu/edit') }}/'+id;
            })
        });
    </script>
@endsection
