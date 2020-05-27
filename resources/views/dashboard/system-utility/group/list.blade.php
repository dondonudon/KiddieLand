@extends('dashboard.layout')

@section('page_menu')
    <li class="nav-item">
        <a href="{{ url(request()->segment(1).'/'.request()->segment(2).'/'.request()->segment(3)) }}" class="nav-link">Group Baru</a>
    </li>
    <li class="nav-item active">
        <a href="{{ url(request()->segment(1).'/'.request()->segment(2).'/'.request()->segment(3)) }}/list" class="nav-link">List Group</a>
    </li>
@endsection

@section('title','System Utility - Group Menu')

@section('content')
    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-6 col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Daftar Group Menu</h4>
                    </div>
                    <div class="card-body">
                        <table id="listTable" class="table table-striped table-bordered display nowrap" style="width: 100%">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Segment Name</th>
                                <th>Icon</th>
                                <th>Order</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                    <div class="card-footer bg-whitesmoke">
                        <div class="row justify-content-end">
                            <div class="col-sm-12 col-lg-2 mt-2 mt-lg-0">
                                <button type="button" id="btnEdit" class="btn btn-block btn-outline-info" disabled>
                                    <i class="fas fa-pencil-alt mr-2"></i>Edit
                                </button>
                            </div>
                            <div class="col-sm-12 col-lg-2 mt-2 mt-lg-0">
                                <a href="{{ url('system-utility/menu-group/baru') }}" class="btn btn-block btn-primary">
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
        let btnEdit = $('#btnEdit');

        let dataID;

        $(document).ready(function () {
            let listTable = $('#listTable').DataTable({
                scrollX: true,
                order: [
                    [ 3, 'asc' ],
                ],
                ajax: {
                    url: '{{ url('dashboard/system/menu-group/list/data') }}'
                },
                columns: [
                    {data: 'name'},
                    {data: 'segment_name'},
                    {
                        data: 'icon',
                        render: function (data,type,row,meta) {
                            return '<i class="'+data+'"></i>';
                        }
                    },
                    {data: 'ord'},
                ],
            });
            $('#listTable tbody').on('click','tr',function () {
                if ($(this).hasClass('selected')) {
                    $(this).removeClass('selected');
                    btnEdit.attr('disabled',true);

                    dataID = null;
                } else {
                    listTable.$('tr.selected').removeClass('selected');
                    $(this).addClass('selected');
                    btnEdit.removeAttr('disabled');

                    let data = listTable.row('.selected').data();
                    dataID = data.id;
                }
            });

            btnEdit.click(function (e) {
                e.preventDefault();
                window.location = '{{ url('dashboard/system/menu-group/edit') }}/'+dataID;
            })
        });
    </script>
@endsection
