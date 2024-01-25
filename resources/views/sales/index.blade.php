@extends('layouts.master')

@section('title')
    Sales List
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Sales List</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="box">
                <div class="box-body table-responsive">
                    <table class="table table-stiped table-bordered table-sales table-hover">
                        <thead>
                            <th width="5%">#</th>
                            <th>Date</th>
                            <th>Branch Code</th>
                            <th>Quantity</th>
                            <th>Total Price</th>
                            <th>Total Pay</th>
                            <th>Cashier</th>
                            <th width="15%"><i class="fa fa-cog"></i></th>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @includeIf('sales.detail')
@endsection

@push('scripts')
    <script>
        let table, table1;

        // data tables
        $(function() {
            table = $('.table-sales').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax: {
                    url: '{{ route('sales.data') }}',
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
                        data: 'code_branch'
                    },
                    {
                        data: 'total_item'
                    },
                    {
                        data: 'total_price'
                    },
                    {
                        data: 'total_pay'
                    },
                    {
                        data: 'cashier'
                    },
                    {
                        data: 'action',
                        searchable: false,
                        sortable: false
                    },
                ]
            });

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
                        data: 'price_selling'
                    },
                    {
                        data: 'stock'
                    },
                    {
                        data: 'subtotal'
                    },
                ]
            })
        });

        // show specific sales details
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

        // delete the speific sales
        function deleteData(url) {
            Swal.fire({
                    title: "Are you sure?",
                    text: "Once deleted, you will not be able to recover this sales!",
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
                        Swal.fire("Canceled", "The sales remains secure.", "success");
                    }
                });
        }
    </script>
@endpush
