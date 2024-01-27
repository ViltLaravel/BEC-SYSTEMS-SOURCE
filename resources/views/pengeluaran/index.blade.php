@extends('layouts.master')

@section('title')
    Expense List
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Expense List</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="box">
                <div class="box-header with-border">
                    <button onclick="addForm('{{ route('expense.store') }}')" class="btn btn-success btn-flat"><i
                            class="fa fa-plus-circle"></i> New Expenses Entry</button>
                </div>
                <div class="box-body table-responsive">
                    <table class="table table-stiped table-bordered table-hover">
                        <thead>
                            <th width="5%">#</th>
                            <th>Date</th>
                            <th>Description</th>
                            <th>Amount</th>
                            <th width="15%"><i class="fa fa-cog"></i></th>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @includeIf('pengeluaran.form')
@endsection

@push('scripts')
    <script>
        let table;

        // data tables
        $(function() {
            table = $('.table').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax: {
                    url: '{{ route('expense.data') }}',
                },
                columns: [{
                        data: 'DT_RowIndex',
                        searchable: false,
                        sortable: false
                    },
                    {
                        data: 'created_at'
                    },
                    {
                        data: 'deskripsi'
                    },
                    {
                        data: 'nominal'
                    },
                    {
                        data: 'aksi',
                        searchable: false,
                        sortable: false
                    },
                ]
            });

            $('#modal-form').validator().on('submit', function(e) {
                if (!e.preventDefault()) {
                    $.post($('#modal-form form').attr('action'), $('#modal-form form').serialize())
                        .done((response) => {
                            $('#modal-form').modal('hide');
                            Swal.fire("Success", response.message, 'success');
                            table.ajax.reload();
                        })
                        .fail((errors) => {
                            Swal.fire("Error", response.message, 'error');
                            return;
                        });
                }
            });
        });

        // add form
        function addForm(url) {
            $('#modal-form').modal('show');
            $('#modal-form .modal-title').text('Add Expenses');

            $('#modal-form form')[0].reset();
            $('#modal-form form').attr('action', url);
            $('#modal-form [name=_method]').val('post');
            $('#modal-form [name=deskripsi]').focus();
        }

        // edit form
        function editForm(url) {
            $('#modal-form').modal('show');
            $('#modal-form .modal-title').text('Edit Expenses');

            $('#modal-form form')[0].reset();
            $('#modal-form form').attr('action', url);
            $('#modal-form [name=_method]').val('put');
            $('#modal-form [name=deskripsi]').focus();

            $.get(url)
                .done((response) => {
                    $('#modal-form [name=deskripsi]').val(response.deskripsi);
                    $('#modal-form [name=nominal]').val(response.nominal);
                })
                .fail((errors) => {
                    Swal.fire("Error", response.message, 'error');
                    return;
                });
        }

        // delete form
        function deleteData(url) {
            Swal.fire({
                    title: "Are you sure?",
                    text: "Once deleted, you will not be able to recover this expenses!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, delete it!",
                })
                .then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: url,
                            type: 'DELETE',
                            data: {
                                '_token': $('[name=csrf-token]').attr('content'),
                            },
                            success: function(response) {
                                Swal.fire("Success", response.message, "success");
                                table.ajax.reload();
                            },
                            error: function(errors) {
                                Swal.fire("Error", response.message, "error");
                            }
                        });
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        Swal.fire("Canceled", "The financial outlay remains secure.", "success");
                    }
                });
        }
    </script>
@endpush
