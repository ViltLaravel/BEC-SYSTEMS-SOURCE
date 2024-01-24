@extends('layouts.master')

@section('title')
    Purchase
@endsection

@push('css')
    <style>
        .tampil-bayar {
            font-size: 5em;
            text-align: center;
            height: 100px;
        }

        .tampil-terbilang {
            padding: 10px;
            background: #f0f0f0;
        }

        .table-purchase tbody tr:last-child {
            display: none;
        }

        @media(max-width: 768px) {
            .tampil-bayar {
                font-size: 3em;
                height: 70px;
                padding-top: 5px;
            }
        }
    </style>
@endpush

@section('breadcrumb')
    @parent
    <li class="active">Purchase Transaction</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="box">
                <div class="box-header with-border">
                    <table>
                        <tr>
                            <td>Supplier</td>
                            <td>: {{ $supplier->nama }}</td>
                        </tr>
                        <tr>
                            <td>Phone</td>
                            <td>: {{ $supplier->telepon }}</td>
                        </tr>
                        <tr>
                            <td>Address</td>
                            <td>: {{ $supplier->alamat }}</td>
                        </tr>
                    </table>
                </div>
                <div class="box-body">

                    <form class="form-product">
                        @csrf
                        <div class="form-group row">
                            <label for="code_product" class="col-lg-2">Product Code</label>
                            <div class="col-lg-5">
                                <div class="input-group">
                                    <input type="hidden" name="id_purchase" id="id_purchase" value="{{ $id_purchase }}">
                                    <input type="hidden" name="id_product" id="id_product">
                                    <input type="text" class="form-control" name="code_product" id="code_product">
                                    <span class="input-group-btn">
                                        <button onclick="selectProduct()" class="btn btn-info btn-flat" type="button"><i
                                                class="fa fa-arrow-right"></i></button>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </form>

                    <table class="table table-stiped table-bordered table-purchase table-hover">
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
                            <div class="tampil-bayar bg-primary"></div>
                            <div class="tampil-terbilang"></div>
                        </div>
                        <div class="col-lg-4">
                            <form action="{{ route('purchase.store') }}" class="form-pembelian" method="post">
                                @csrf
                                <input type="hidden" name="id_purchase" value="{{ $id_purchase }}">
                                <input type="hidden" name="total" id="total">
                                <input type="hidden" name="total_item" id="total_item">
                                <input type="hidden" name="total_pay" id="total_pay">

                                <div class="form-group row">
                                    <label for="totalrp" class="col-lg-2 control-label">Total</label>
                                    <div class="col-lg-8">
                                        <input type="text" id="totalrp" class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="discount" class="col-lg-2 control-label">Discount</label>
                                    <div class="col-lg-8">
                                        <input type="number" name="discount" id="discount" class="form-control"
                                            value="{{ $discount }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="total_pay" class="col-lg-2 control-label">Pay</label>
                                    <div class="col-lg-8">
                                        <input type="text" id="total_pay_rp" class="form-control">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="box-footer">
                    <button type="submit" class="btn btn-primary btn-sm btn-flat pull-right btn-simpan"><i
                            class="fa fa-floppy-o"></i> Save Transaction</button>
                </div>
            </div>
        </div>
    </div>

    @includeIf('purchase_detail.product')
@endsection

@push('scripts')
    <script>
        let table, table2;

        // data table
        $(function() {
            $('body').addClass('sidebar-collapse');

            table = $('.table-purchase').DataTable({
                    responsive: true,
                    processing: true,
                    serverSide: true,
                    autoWidth: false,
                    ajax: {
                        url: '{{ route('purchase_detail.data', $id_purchase) }}',
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
                            data: 'purchase_price'
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
                    loadForm($('#discount').val());
                });
            table2 = $('.table-product').DataTable();

            // showing the sweet alert in quantity and discount
            $(document).on('input', '.quantity', function() {
                let id = $(this).data('id');
                let stock = parseInt($(this).val());

                if (stock < 1) {
                    $(this).val(1);
                    Swal.fire("Error", "The number cannot be less than 1", 'error');
                    return;
                }
                if (stock > 10000) {
                    $(this).val(10000);
                    Swal.fire("Warning", "The number cannot exceed 10000", 'warning');
                    return;
                }

                $.post(`{{ url('/purchase_detail') }}/${id}`, {
                        '_token': $('[name=csrf-token]').attr('content'),
                        '_method': 'put',
                        'stock': stock
                    })
                    .done(response => {
                        $(this).on('mouseout', function() {
                            table.ajax.reload(() => loadForm($('#discount').val()));
                        });
                    })
                    .fail(errors => {
                        Swal.fire("Information", "Unable to save purchase", 'info');
                        return;
                    });
            });

            $(document).on('input', '#discount', function() {
                if ($(this).val() == "") {
                    $(this).val(0).select();
                }

                loadForm($(this).val());
            });

            $('.btn-simpan').on('click', function() {
                $('.form-pembelian').submit();
            });
        });

        // Show the modal for product selection
        function selectProduct() {
            $('#modal-product').modal('show');
        }

        // Hide the modal for product selection
        function hideProduct() {
            $('#modal-product').modal('hide');
        }

        // Function for handling product selection
        function pilihProduk(id, code) {
            $('#id_product').val(id);
            $('#code_product').val(code);
            hideProduct();
            addProduct();
        }

        // Add the selected product into the data table
        function addProduct() {
            $.post('{{ route('purchase_detail.store') }}', $('.form-product').serialize())
                .done(response => {
                    $('#code_product').focus();
                    table.ajax.reload(() => loadForm($('#discount').val()));
                    Swal.fire("Success", response.message, 'success');
                })
                .fail(errors => {
                    // Display error message from the response
                    Swal.fire("Error", errors.responseJSON.message || 'An error occurred', 'error');
                });
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
                                table.ajax.reload(() => loadForm($('#discount').val()));
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

        // realtime displaying the details
        function loadForm(discount = 0) {
            $('#total').val($('.total').text());
            $('#total_item').val($('.total_item').text());

            $.get(`{{ url('/purchase_detail/loadform') }}/${discount}/${$('.total').text()}`)
                .done(response => {
                    $('#totalrp').val('₱ ' + response.totalrp);
                    $('#total_pay_rp').val('₱ ' + response.total_pay_rp);
                    $('#total_pay').val(response.total_pay);
                    $('.tampil-bayar').text('₱ ' + response.total_pay_rp);
                    $('.tampil-terbilang').text(response.number_generate);
                })
                .fail(errors => {
                    Swal.fire("Error", "Unable to display data!", 'error');
                    return;
                })
        }
    </script>
@endpush
