@extends('layouts.master')

@section('title')
    Sales Transactions
@endsection

@push('css')
    <style>
        .responsive_number {
            font-size: 5em;
            text-align: center;
            height: 100px;
        }

        .number_text {
            padding: 10px;
            background: #f0f0f0;
        }

        .table-sales tbody tr:last-child {
            display: none;
        }

        @media(max-width: 768px) {
            .responsive_number {
                font-size: 3em;
                height: 70px;
                padding-top: 5px;
            }
        }
    </style>
@endpush

@section('breadcrumb')
    @parent
    <li class="active">Sales Transactions</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="box">
                <div class="box-body">
                    <form class="form-product">
                        @csrf
                        <div class="form-group row">
                            <label for="code_product" class="col-lg-2">Product Code</label>
                            <div class="col-lg-5">
                                <div class="input-group">
                                    <input type="hidden" name="id_sales" id="id_sales" value="{{ $id_sales }}">
                                    <input type="hidden" name="id_product" id="id_product">
                                    <input type="text" class="form-control" name="code_product" id="code_product">
                                    <span class="input-group-btn">
                                        <button onclick="showProduct()" class="btn btn-success btn-flat" type="button"><i
                                                class="fa fa-search-plus"></i></button>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </form>

                    <table class="table table-stiped table-bordered table-sales">
                        <thead>
                            <th width="5%">#</th>
                            <th>Code</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th width="15%">Quantity</th>
                            <th>Subtotal</th>
                            <th width="15%"><i class="fa fa-cog"></i></th>
                        </thead>
                    </table>

                    <div class="row">
                        <div class="col-lg-8">
                            <div class="responsive_number bg-primary"></div>
                            <div class="number_text"></div>
                        </div>
                        <div class="col-lg-4">
                            <form action="{{ route('transaction.store') }}" class="form-sales" method="post">
                                @csrf
                                <input type="hidden" name="id_sales" value="{{ $id_sales }}">
                                <input type="hidden" name="total" id="total">
                                <input type="hidden" name="total_item" id="total_item">
                                <input type="hidden" name="total_pay" id="total_pay">
                                <input type="hidden" name="id_branch" id="id_branch"
                                    value="{{ $branchSelected->id_branch }}">

                                <div class="form-group row">
                                    <label for="totalrp" class="col-lg-2 control-label">Total</label>
                                    <div class="col-lg-8">
                                        <input type="text" id="totalrp" class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="code_branch" class="col-lg-2 control-label">Branch</label>
                                    <div class="col-lg-8">
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="code_branch"
                                                value="{{ $branchSelected->code_branch }}">
                                            <span class="input-group-btn">
                                                <button onclick="showBranch()" class="btn btn-success btn-flat"
                                                    type="button"><i class="fa fa-search-plus"></i></button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="total_pay" class="col-lg-2 control-label">Pay</label>
                                    <div class="col-lg-8">
                                        <input type="text" id="total_pay_rp" class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="change" class="col-lg-2 control-label">Received</label>
                                    <div class="col-lg-8">
                                        <input type="number" id="change" class="form-control" name="change"
                                            value="{{ $sales->change ?? 0 }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="return" class="col-lg-2 control-label">Return</label>
                                    <div class="col-lg-8">
                                        <input type="text" id="return" name="return" class="form-control"
                                            value="0" readonly>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="box-footer">
                    <button type="submit" class="btn btn-success btn-sm btn-flat pull-right btn-simpan"><i class="fa fa-floppy-o"></i> Save Transaction</button>
                </div>
            </div>
        </div>
    </div>

    @includeIf('sales_detail.product')
    @includeIf('sales_detail.branch')
@endsection

@push('scripts')
    <script>
        let table, table2;

        // data table
        $(function() {
            $('body').addClass('sidebar-collapse');

            table = $('.table-sales').DataTable({
                    responsive: true,
                    processing: true,
                    serverSide: true,
                    autoWidth: false,
                    ajax: {
                        url: '{{ route('transaction.data', $id_sales) }}',
                    },
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
                            data: 'selling_price'
                        },
                        {
                            data: 'stock'
                        },
                        {
                            data: 'subtotal'
                        },
                        {
                            data: 'action',
                            searchable: false,
                            sortable: false
                        },
                    ],
                    dom: 'Brt',
                    bSort: false,
                    paginate: false
                })
                .on('draw.dt', function() {
                    loadForm();
                    setTimeout(() => {
                        $('#change').trigger('input');
                    }, 300);
                });

            table2 = $('.table-product').DataTable();

            $(document).on('input', '.quantity', function() {
                let id = $(this).data('id');
                let newQuantity = parseInt($(this).val());

                if (newQuantity < 1) {
                    $(this).val(1);
                    Swal.fire("Error", "The number cannot be less than 1", 'error');
                    return;
                }
                if (newQuantity > 10000) {
                    $(this).val(10000);
                    Swal.fire("Warning", "The number cannot exceed 10000", 'warning');
                    return;
                }

                // Perform stock check before updating
                $.ajax({
                    url: `{{ url('/transaction') }}/${id}`,
                    method: 'PUT',
                    data: {
                        '_token': $('[name=csrf-token]').attr('content'),
                        '_method': 'put',
                        'jumlah': newQuantity
                    },
                    success: function(response) {
                        table.ajax.reload(() => loadForm());
                    },
                    error: function(jqXHR) {
                        // Handle error
                        if (jqXHR.status === 400) {
                            // Quantity exceeds available stock
                            Swal.fire("Error", "Quantity exceeds available stock", 'error');
                        } else {
                            // Other errors
                            Swal.fire("Error", "Unable to save data", 'error');
                        }
                    }
                });
            });

            $(document).on('input', function() {
                if ($(this).val() == "") {
                    $(this).val(0).select();
                }

                loadForm($(this).val());
            });

            $('#change').on('input', function() {
                if ($(this).val() == "") {
                    $(this).val(0).select();
                }

                loadForm($(this).val());
            }).focus(function() {
                $(this).select();
            });

            $('.btn-simpan').on('click', function() {
                $('.form-sales').submit();
            });
        });

        // show the modal of product selection
        function showProduct() {
            $('#modal-product').modal('show');
        }

        // hide the modal of product selection
        function hideProduct() {
            $('#modal-product').modal('hide');
        }

        // get the product details
        function getProduct(id, code) {
            $('#id_product').val(id);
            $('#code_product').val(code);
            hideProduct();
            addProduct();
        }

        // add the products
        function addProduct() {
            $.post('{{ route('transaction.store') }}', $('.form-product').serialize())
                .done(response => {
                    $('#code_product').focus();
                    table.ajax.reload(() => loadForm());
                    Swal.fire("Success", response.message, 'success');
                })
                .fail(errors => {
                    // Handle error response and show error message
                    let errorMessage = "An error occurred while processing the request.";

                    if (errors.responseJSON && errors.responseJSON.message) {
                        errorMessage = errors.responseJSON.message;
                    }

                    Swal.fire("Error", errorMessage, 'error');
                });
        }

        // member modal show
        function showBranch() {
            $('#modal-member').modal('show');
        }

        // get the member details
        function selectBranch(id, code) {
            $('#id_branch').val(id);
            $('#code_member').val(code);
            loadForm();
            $('#change').val(0).focus().select();
            hideBranch();
        }

        // hide the member modal
        function hideBranch() {
            $('#modal-member').modal('hide');
        }

        // remove the selected product in the data table
        function deleteData(url) {
            Swal.fire({
                    title: "Warning",
                    text: "Are you sure you want to remove this product?",
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
                                table.ajax.reload(() => loadForm());
                            },
                            error: function(errors) {
                                Swal.fire("Error", response.message, "error");
                                return;
                            }
                        });
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        Swal.fire("Canceled", "The product remains secure.", "success");
                        return;
                    }
                });
        }

  // load the sales value in the data table realtime
  function loadForm(change = 0) {
            $('#total').val($('.total').text());
            $('#total_item').val($('.total_item').text());

            $.get(`{{ url('/transaction/loadform') }}/${$('.total').text()}/${change}`)
                .done(response => {
                    $('#totalrp').val('₱' + response.totalrp);
                    $('#bayarrp').val('₱' + response.bayarrp);
                    $('#bayar').val(response.bayar);
                    $('.tampil-bayar').text('Pay: ₱ ' + response.bayarrp);
                    $('.tampil-terbilang').text(response.terbilang);

                    $('#kembali').val('₱' + response.kembalirp);
                    if ($('#diterima').val() != 0) {
                        $('.tampil-bayar').text('Return: ₱ ' + response.kembalirp);
                        $('.tampil-terbilang').text(response.kembali_terbilang);
                    }
                })
                .fail(errors => {
                    Swal.fire("Error", "Error fetching form!", 'error');
                    return;
                })
        }

    </script>
@endpush
