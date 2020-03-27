@extends('dashboard.layout')

@section('content')
    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-6 col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4>List User</h4>
                    </div>
                    <div class="card-body pt-0 pb-0">
                        <form id="formFilter">
                            <div class="row justify-content-end">
                                <div class="col-sm-12 col-md-6 col-lg-2 col-xl-2">
                                    <div class="form-group">
                                        <select class="form-control" id="fKolom">
                                            <option value="username">Username</option>
                                            <option value="name">Nama</option>
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
                        <div class="thead-dark table-sm table-striped" id="listTable"></div>
                    </div>
                    <div class="card-footer bg-whitesmoke">
                        <div class="row justify-content-end">
                            <div class="col-sm-12 col-lg-3 mt-2 mt-lg-0">
                                <div class="btn-group btn-block mb-3" role="group" aria-label="Basic example">
                                    <button type="button" id="btnDisable" class="btn btn-danger disabled">
                                        <i class="fas fa-times mr-2"></i>Disable
                                    </button>
                                    <button type="button" id="btnActivate" class="btn btn-success disabled">
                                        <i class="fas fa-check mr-2"></i>Activate
                                    </button>
                                </div>
                            </div>
                            <div class="col-sm-12 col-lg-2 mt-2 mt-lg-0">
                                <button type="button" id="btnReset" class="btn btn-block btn-warning" disabled>
                                    <i class="fas fa-undo mr-2"></i>Reset Password
                                </button>
                            </div>
                            <div class="col-sm-12 col-lg-2 mt-2 mt-lg-0">
                                <button type="button" id="btnEdit" class="btn btn-block btn-primary" disabled>
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
        const formFilter = $('#formFilter');
        const btnClearFilter = $('#btnClearFilter');
        const btnEdit = $('#btnEdit');
        const btnReset = $('#btnReset');
        const btnDisable = $('#btnDisable');
        const btnActivate = $('#btnActivate');

        let dataID;

        $(document).ready(function () {
            let listTable = new Tabulator("#listTable", {
                layout: "fitDataStretch",
                selectable: 1,
                placeholder: 'No Data Available',
                pagination: "remote",
                ajaxFiltering: true,
                ajaxURL: "{{ url('dashboard/master/user-management/data') }}",
                ajaxConfig: {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content'),
                    }
                },
                ajaxURLGenerator:function(url, config, params){
                    return url + "?page=" + params.page;
                },
                columns: [
                    {
                        title:"Status",field:"status",width:100,
                        formatter: function (row) {
                            if (row.getData().status === 1) {
                                row.getElement().style.backgroundColor = "rgba(6,255,0,0.51)";
                                return 'aktif';
                            } else {
                                row.getElement().style.backgroundColor = "rgba(255,0,9,0.5)";
                                return 'nonaktif';
                            }
                        }
                    },
                    {title:"Username",field:"username"},
                    {
                        title:"System",field:"system",
                        formatter: function (row) {
                            let data = row.getData().system;
                            if (data === 0) {
                                return 'Web & Android';
                            } else if (data === 1) {
                                return 'Android';
                            } else {
                                return 'Web';
                            }
                        }
                    },
                    {title:"Nama",field:"name"},
                    {title:"No HP",field:"no_hp"},
                    {title:"Email",field:"email"},
                ],
                rowSelectionChanged:function (data,rows) {
                    if (data.length === 1) {
                        btnEdit.removeAttr('disabled');
                        btnReset.removeAttr('disabled');
                        if (data[0].status === 1) {
                            btnActivate.addClass('disabled');
                            btnDisable.removeClass('disabled');
                        } else {
                            btnActivate.removeClass('disabled');
                            btnDisable.addClass('disabled');
                        }
                    } else {
                        btnEdit.attr('disabled',true);
                        btnReset.attr('disabled',true);
                        btnDisable.addClass('disabled');
                        btnActivate.addClass('disabled');
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
                let username = listTable.getSelectedData()[0].username;
                window.location = '{{ url('dashboard/master/user-management/edit') }}/'+username;
            });

            btnReset.click(function (e) {
                e.preventDefault();
                let username = listTable.getSelectedData()[0].username;
                Swal.fire({
                    title: 'Reset password?',
                    text: "Password akan sama seperti username",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Reset Password'
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: '{{ url('dashboard/master/user-management/reset-password') }}',
                            method: 'post',
                            data: {username: username},
                            success: function (response) {
                                if (response === 'success') {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Berhasil',
                                        text: 'Password berhasil direset',
                                        onClose(modalElement) {
                                            listTable.setData();
                                        }
                                    });
                                } else {
                                    console.log(response);
                                    Swal.fire({
                                        icon: 'warning',
                                        title: 'Gagal',
                                        text: 'Gagal reset Password, silahkan coba lagi.',
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
                })
            });

            btnDisable.click(function (e) {
                e.preventDefault();
                let username = listTable.getSelectedData()[0].username;
                Swal.fire({
                    title: 'Nonaktifkan user ini?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Nonaktifkan User'
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: '{{ url('dashboard/master/user-management/disable') }}',
                            method: 'post',
                            data: {username: username},
                            success: function (response) {
                                if (response === 'success') {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Berhasil',
                                        text: 'User nonaktif',
                                        onClose(modalElement) {
                                            listTable.setData();
                                        }
                                    });
                                } else {
                                    console.log(response);
                                    Swal.fire({
                                        icon: 'warning',
                                        title: 'Gagal',
                                        text: 'Gagal reset Password, silahkan coba lagi.',
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
            });
            btnActivate.click(function (e) {
                e.preventDefault();
                let username = listTable.getSelectedData()[0].username;
                Swal.fire({
                    title: 'Aktifkan user ini?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Aktifkan User'
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: '{{ url('dashboard/master/user-management/activate') }}',
                            method: 'post',
                            data: {username: username},
                            success: function (response) {
                                if (response === 'success') {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Berhasil',
                                        text: 'User telah aktif',
                                        onClose(modalElement) {
                                            listTable.setData();
                                        }
                                    });
                                } else {
                                    console.log(response);
                                    Swal.fire({
                                        icon: 'warning',
                                        title: 'Gagal',
                                        text: 'Gagal reset Password, silahkan coba lagi.',
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
            });
        });
    </script>
@endsection
