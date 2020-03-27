@extends('dashboard.layout')

@section('content')
    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-6 col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Daftar {{ ucfirst(request()->segment(3)) }}</h4>
                    </div>
                    <div class="card-body pt-0 pb-0">
                        <form id="formFilter">
                            <div class="row justify-content-end">
                                <div class="col-sm-12 col-md-6 col-lg-2 col-xl-2">
                                    <div class="form-group">
                                        <select class="form-control" id="fKolom">
                                            <option value="periode">Periode</option>
                                            <option value="bulan">Bulan</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-3 col-xl-3">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="fas fa-search"></i>
                                                </div>
                                            </div>
                                            <input type="text" class="form-control phone-number" id="fValue">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-2 col-xl-2">
                                    <button type="button" class="btn btn-warning btn-block" id="btnClearFilter">CLEAR Filter</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card-body p-0">
                        <div class="thead-dark table-sm table-striped" id="listTable" style="width: 100%"></div>
                    </div>
                    <div class="card-footer bg-whitesmoke">
                        <div class="row justify-content-end">
                            <!-- <div class="col-sm-12 col-lg-3 mt-2 mt-lg-0">
                                <div class="btn-group btn-block mb-3" role="group" aria-label="Basic example">
                                    <button type="button" id="btnDisable" class="btn btn-danger disabled">
                                        <i class="fas fa-times mr-2"></i>Disable
                                    </button>
                                    <button type="button" id="btnActivate" class="btn btn-success disabled">
                                        <i class="fas fa-check mr-2"></i>Activate
                                    </button>
                                </div>
                            </div> -->
                            <div class="col-sm-12 col-lg-2 mt-2 mt-lg-0">
                                <button type="button" id="btnEdit" class="btn btn-block btn-danger" disabled>
                                    <i class="fas fa-pencil-alt mr-2"></i>Edit
                                </button>
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
        function changeStatus(data,status,question,commit,successResponse,failedResponse,table) {
            Swal.fire({
                title: question,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: commit
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: '{{ url('dashboard/master/tahun-ajaran') }}/'+status,
                        method: 'post',
                        data: data,
                        success: function (response) {
                            if (response === 'success') {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: successResponse,
                                    onClose(modalElement) {
                                        table.setData();
                                    }
                                });
                            } else {
                                console.log(response);
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Gagal',
                                    text: failedResponse,
                                });
                            }
                        },
                        error: function (response) {
                            console.log(response);
                            Swal.fire({
                                icon: 'error',
                                title: 'System Error',
                                text: 'Silahkan hubungi WAVE Solusi Indonesia',
                            });
                        }
                    });
                }
            });
        }

        const formFilter = $('#formFilter');
        const btnClearFilter = $('#btnClearFilter');
        // const btnDisable = $('#btnDisable');
        // const btnActivate = $('#btnActivate');
        let btnEdit = $('#btnEdit');

        let dataID;

        $(document).ready(function () {
            let listTable = new Tabulator("#listTable", {
                resizableColumns: false,
                layout: "fitDataStretch",
                selectable: 1,
                placeholder: 'No Data Available',
                pagination: "remote",
                ajaxFiltering: true,
                ajaxURL: "{{ url('dashboard/master/tahun-ajaran/data') }}",
                ajaxConfig: {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content'),
                    }
                },
                ajaxURLGenerator:function(url, config, params){
                    return url + "?page=" + params.page;
                },
                // groupBy: "koridor",
                columns: [
                    // {
                    //     title:"Status",field:"status",width:100,
                    //     formatter: function (row) {
                    //         if (row.getData().status === 1) {
                    //             row.getElement().style.backgroundColor = "rgba(0,155,0,0.8)";
                    //             row.getElement().style.color = "white";
                    //             row.getElement().style.textAlign = "center";
                    //             return 'Aktif';
                    //         } else {
                    //             row.getElement().style.backgroundColor = "rgb(155,0,10)";
                    //             row.getElement().style.color = "white";
                    //             row.getElement().style.textAlign = "center";
                    //             return 'Nonaktif';
                    //         }
                    //     }
                    // },
                    {title:"Periode",field:"periode"},
                    {title:"Bulan",field:"bulan"},
                    {title:"Ket",field:"ket"},
                ],
                rowSelectionChanged:function (data,rows) {
                    if (data.length === 1) {
                        btnEdit.removeAttr('disabled');
                        if (data[0].status === 1) {
                            // btnActivate.addClass('disabled');
                            // btnDisable.removeClass('disabled');
                        } else {
                            // btnActivate.removeClass('disabled');
                            // btnDisable.addClass('disabled');
                        }
                    } else {
                        btnEdit.attr('disabled',true);
                        // btnDisable.addClass('disabled');
                        // btnActivate.addClass('disabled');
                    }
                }
            });

            formFilter.submit(function (e) {
                e.preventDefault();
                let kolom = $('#fKolom');
                let value = $('#fValue');
                listTable.setFilter(kolom.val(),'like',value.val());
            });

            btnClearFilter.click(function (e) {
                e.preventDefault();
                listTable.clearFilter();
            });

            btnEdit.click(function (e) {
                e.preventDefault();
                let id = listTable.getSelectedData()[0].id;
                window.location = '{{ url('dashboard/master/tahun-ajaran/edit') }}/'+id;
            });

            // btnDisable.click(function (e) {
            //     e.preventDefault();
            //     let id = listTable.getSelectedData()[0].id;
            //     changeStatus(
            //         {id: id},
            //         'disable',
            //         'Nonaktifkan bus ini?',
            //         'Nonaktifkan Bus',
            //         'Bus nonaktif!',
            //         'Gagal menonaktifkan bus, silahkan coba lagi!',
            //         listTable
            //     );
            // });

            // btnActivate.click(function (e) {
            //     e.preventDefault();
            //     let id = listTable.getSelectedData()[0].id;
            //     changeStatus(
            //         {id: id},
            //         'activate',
            //         'Aktifkan bus ini?',
            //         'Aktifkan Bus',
            //         'Bus aktif!',
            //         'Gagal mengaktifkan bus, silahkan coba lagi!',
            //         listTable
            //     );
            // });
        });
    </script>
@endsection
