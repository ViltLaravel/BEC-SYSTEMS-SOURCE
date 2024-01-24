@extends('layouts.master')

@section('title')
    Purchase List
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Purchase List</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="box">
                <div class="box-header with-border">
                    <button onclick="addForm()" class="btn btn-success btn-flat"><i class="fa fa-plus-circle"></i> Add New
                        Purchase</button>
                    @empty(!session('id_purchase'))
                        <a href="{{ route('purchase_detail.index') }}" class="btn btn-info btn-xs btn-flat"><i
                                class="fa fa-pencil"></i> Active Transaction</a>
                    @endempty
                </div>
                <div class="box-body table-responsive">
                    <table class="table table-stiped table-bordered table-purchase table-hover">
                        <thead>
                            <th width="5%">#</th>
                            <th>Date</th>
                            <th>Supplier</th>
                            <th>Quantity</th>
                            <th>Total Price</th>
                            <th>Discount</th>
                            <th>Total Pay</th>
                            <th width="15%"><i class="fa fa-cog"></i></th>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @includeIf('purchase.supplier')
    @includeIf('purchase.detail')
@endsection

@push('scripts')
    <script>
        let table, table1;

        // data tables
        $(function() {
            table = $('.table-purchase').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax: {
                    url: '{{ route('purchase.data') }}',
                },
                columns: [{
                        data: 'DT_RowIndex',
                        searchable: false,
                        sortable: false
                    },
                    {
                        data: 'date'
                    },
                    {
                        data: 'supplier'
                    },
                    {
                        data: 'total_item'
                    },
                    {
                        data: 'total_price'
                    },
                    {
                        data: 'discount'
                    },
                    {
                        data: 'total_pay'
                    },
                    {
                        data: 'action',
                        searchable: false,
                        sortable: false
                    },
                ]
            });

            $('.table-supplier').DataTable();
            table1 = $('.table-detail').DataTable({
                processing: true,
                bSort: false,
                dom: 'Brt',
                columns: [{
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
                        data: 'purchase_price'
                    },
                    {
                        data: 'stock'
                    },
                    {
                        data: 'subtotal'
                    },
                ]
            });
        });

        function addForm() {
            $('#modal-supplier').modal('show');
        }

        // show specific purchases
        function showDetail(url) {
            $('#modal-detail').modal('show');
            $.ajax({
                url: url,
                type: 'GET',
                data: {
                    '_token': $('[name=csrf-token]').attr('content'),
                },
                success: function(response) {
                    table1.ajax.url(url);
                    table1.ajax.reload();
                },
                error: function(errors) {
                    Swal.fire("Error", response.message, "error");
                }
            });
        }

        // delete the specific purchase
        function deleteData(url) {
            Swal.fire({
                    title: "Are you sure?",
                    text: "Once deleted, you will not be able to recover this purchase!",
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
                        Swal.fire("Canceled", "The purchase remains secure.", "success");
                    }
                });
        }
    </script>
@endpush
