@extends('layouts.master')

@section('title')
    Category List
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Category List</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="box">
                <div class="box-header with-border">
                    <button onclick="addForm('{{ route('category.store') }}')" class="btn btn-success btn-flat"><i
                            class="fa fa-plus-circle"></i> Add New Category</button>
                </div>
                <div class="box-body table-responsive">
                    <table class="table table-stiped table-bordered table-hover">
                        <thead>
                            <th width="5%">#</th>
                            <th>Category</th>
                            <th width="15%"><i class="fa fa-cog"></i></th>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

@includeIf('category.form')
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
                    url: '{{ route('category.data') }}',
                },
                columns: [{
                        data: 'DT_RowIndex',
                        searchable: false,
                        sortable: false
                    },
                    {
                        data: 'name_category'
                    },
                    {
                        data: 'action',
                        searchable: false,
                        sortable: false
                    },
                ]
            });

            // modal
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
            $('#modal-form .modal-title').text('Add Category');

            $('#modal-form form')[0].reset();
            $('#modal-form form').attr('action', url);
            $('#modal-form [name=_method]').val('post');
            $('#modal-form [name=name_category]').focus();
        }

        // edit form
        function editForm(url) {
            $('#modal-form').modal('show');
            $('#modal-form .modal-title').text('Edit Category');

            $('#modal-form form')[0].reset();
            $('#modal-form form').attr('action', url);
            $('#modal-form [name=_method]').val('put');
            $('#modal-form [name=name_category]').focus();

            $.get(url)
                .done((response) => {
                    $('#modal-form [name=name_category]').val(response.name_category);
                })
                .fail((errors) => {
                    Swal.fire("Error", response.message, 'error');
                    return;
                });
        }

        // delete category
        function deleteData(url) {
            Swal.fire({
                    title: "Are you sure?",
                    text: "Once deleted, you will not be able to recover this category!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, delete it!",
                    cancelButtonText: "No, cancel!",
                    buttonsStyling: true,
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
                                if (response.status === 'success') {
                                    Swal.fire("Success", response.message, "success");
                                    table.ajax.reload();
                                } else {
                                    Swal.fire("Error", response.message, "error");
                                }
                            },
                            error: function(error) {
                                Swal.fire("Error", "Cannot delete data", "error");
                            }
                        });
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        Swal.fire("Canceled", "The category remains secure.", "success");
                    }
                });
        }
    </script>
@endpush
