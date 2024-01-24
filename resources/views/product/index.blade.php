@extends('layouts.master')

@section('title')
    Product List
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Product List</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="box">
                <div class="box-header with-border">
                    <div class="btn-group">
                        <button onclick="addForm('{{ route('product.store') }}')" class="btn btn-success  btn-flat"><i
                                class="fa fa-plus-circle"></i> Add New Product</button>
                        <button onclick="deleteSelected('{{ route('product.delete_selected') }}')"
                            class="btn btn-danger  btn-flat"><i class="fa fa-trash"></i> Delete</button>
                        <button onclick="barcode('{{ route('product.barcode') }}')" class="btn btn-warning  btn-flat"><i
                                class="fa fa-barcode"></i> Print Barcode</button>
                    </div>
                </div>
                <div class="box-body table-responsive">
                    <form action="" method="post" class="form-produk">
                        @csrf
                        <table class="table table-stiped table-bordered table-hover">
                            <thead>
                                <th width="5%">
                                    <input type="checkbox" name="select_all" id="select_all">
                                </th>
                                <th width="5%">#</th>
                                <th>Code</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Brand</th>
                                <th>Purchase Price</th>
                                <th>Selling Price</th>
                                <th>Stock</th>
                                <th width="15%"><i class="fa fa-cog"></i></th>
                            </thead>
                        </table>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @includeIf('product.form')
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
                    url: '{{ route('product.data') }}',
                },
                columns: [{
                        data: 'select_all',
                        searchable: false,
                        sortable: false
                    },
                    {
                        data: 'DT_RowIndex',
                        searchable: false,
                        sortable: false
                    },
                    {
                        data: 'code_product'
                    },
                    {
                        data: 'name_product'
                    },
                    {
                        data: 'name_category'
                    },
                    {
                        data: 'brand'
                    },
                    {
                        data: 'price_purchase'
                    },
                    {
                        data: 'price_selling'
                    },
                    {
                        data: 'stock'
                    },
                    {
                        data: 'action',
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

            $('[name=select_all]').on('click', function() {
                $(':checkbox').prop('checked', this.checked);
            });
        });

        // modal add form
        function addForm(url) {
            $('#modal-form').modal('show');
            $('#modal-form .modal-title').text('Add Product');

            $('#modal-form form')[0].reset();
            $('#modal-form form').attr('action', url);
            $('#modal-form [name=_method]').val('post');
            $('#modal-form [name=name_product]').focus();
        }

        // modal edit form
        function editForm(url) {
            $('#modal-form').modal('show');
            $('#modal-form .modal-title').text('Edit Product');

            $('#modal-form form')[0].reset();
            $('#modal-form form').attr('action', url);
            $('#modal-form [name=_method]').val('put');
            $('#modal-form [name=name_product]').focus();

            $.get(url)
                .done((response) => {
                    $('#modal-form [name=name_product]').val(response.name_product);
                    $('#modal-form [name=id_category]').val(response.id_category);
                    $('#modal-form [name=id_unit]').val(response.id_unit);
                    $('#modal-form [name=brand]').val(response.brand);
                    $('#modal-form [name=price_purchase]').val(response.price_purchase);
                    $('#modal-form [name=price_selling]').val(response.price_selling);
                    $('#modal-form [name=stock]').val(response.stock);
                })
                .fail((errors) => {
                    Swal.fire("Error", 'Unable to display data!');
                    return;
                });
        }

        // deleting product
        function deleteData(url) {
            Swal.fire({
                    title: "Are you sure?",
                    text: "Once deleted, you will not be able to recover this product!",
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
                        Swal.fire("Canceled", "The product remains secure.", "success");
                    }
                });
        }

        // deleting selected product
        function deleteSelected(url) {
            const checkedCount = $('input:checked').length;

            if (checkedCount > 1) {
                Swal.fire({
                        title: "Are you sure?",
                        text: "Once deleted, you will not be able to recover this product!",
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
                            $.post(url, $('.form-produk').serialize())
                                .done((response) => {
                                    Swal.fire("Success", response.message, "success");
                                    table.ajax.reload();
                                })
                                .fail(() => {
                                    Swal.fire("Error", response.message, "error");
                                });
                        } else if (result.dismiss === Swal.DismissReason.cancel) {
                            Swal.fire("Canceled", "The product remains secure.", "success");
                        }
                    });
            } else {
                Swal.fire("Information", "Select at least two products to delete", "info");
            }
        }

        // generate product barcode
        function barcode(url) {
            if ($('input:checked').length < 1) {
                Swal.fire("Information", "Select product to print!", 'info');
                return;
            } else if ($('input:checked').length < 3) {
                Swal.fire("Information", "Select atleast 3 product to print!", 'info');
                return;
            } else {
                $('.form-produk')
                    .attr('target', '_blank')
                    .attr('action', url)
                    .submit();
            }
        }
    </script>
@endpush