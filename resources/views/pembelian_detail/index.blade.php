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

        .table-pembelian tbody tr:last-child {
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
                            <td>Telephone</td>
                            <td>: {{ $supplier->telepon }}</td>
                        </tr>
                        <tr>
                            <td>Address</td>
                            <td>: {{ $supplier->alamat }}</td>
                        </tr>
                    </table>
                </div>
                <div class="box-body">

                    <form class="form-produk">
                        @csrf
                        <div class="form-group row">
                            <label for="kode_produk" class="col-lg-2">Product Code</label>
                            <div class="col-lg-5">
                                <div class="input-group">
                                    <input type="hidden" name="id_pembelian" id="id_pembelian" value="{{ $id_pembelian }}">
                                    <input type="hidden" name="id_produk" id="id_produk">
                                    <input type="text" class="form-control" name="kode_produk" id="kode_produk">
                                    <span class="input-group-btn">
                                        <button onclick="tampilProduk()" class="btn btn-info btn-flat" type="button"><i
                                                class="fa fa-arrow-right"></i></button>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </form>

                    <table class="table table-stiped table-bordered table-pembelian table-hover">
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
                                <input type="hidden" name="id_pembelian" value="{{ $id_pembelian }}">
                                <input type="hidden" name="total" id="total">
                                <input type="hidden" name="total_item" id="total_item">
                                <input type="hidden" name="bayar" id="bayar">

                                <div class="form-group row">
                                    <label for="totalrp" class="col-lg-2 control-label">Total</label>
                                    <div class="col-lg-8">
                                        <input type="text" id="totalrp" class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="diskon" class="col-lg-2 control-label">Discount</label>
                                    <div class="col-lg-8">
                                        <input type="number" name="diskon" id="diskon" class="form-control"
                                            value="{{ $diskon }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="bayar" class="col-lg-2 control-label">Pay</label>
                                    <div class="col-lg-8">
                                        <input type="text" id="bayarrp" class="form-control">
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

    @includeIf('pembelian_detail.produk')
@endsection

@push('scripts')
    <script>
        let table, table2;

        // data table
        $(function() {
            $('body').addClass('sidebar-collapse');

            table = $('.table-pembelian').DataTable({
                    responsive: true,
                    processing: true,
                    serverSide: true,
                    autoWidth: false,
                    ajax: {
                        url: '{{ route('purchase_detail.data', $id_pembelian) }}',
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                            searchable: false,
                            sortable: false
                        },
                        {
                            data: 'kode_produk'
                        },
                        {
                            data: 'nama_produk'
                        },
                        {
                            data: 'harga_beli'
                        },
                        {
                            data: 'jumlah'
                        },
                        {
                            data: 'subtotal'
                        },
                        {
                            data: 'aksi',
                            searchable: false,
                            sortable: false
                        },
                    ],
                    dom: 'Brt',
                    bSort: false,
                    paginate: false
                })
                .on('draw.dt', function() {
                    loadForm($('#diskon').val());
                });
            table2 = $('.table-produk').DataTable();

            // showing the sweet alert in quantity and discount
            $(document).on('input', '.quantity', function() {
                let id = $(this).data('id');
                let jumlah = parseInt($(this).val());

                if (jumlah < 1) {
                    $(this).val(1);
                    Swal.fire("Error", "The number cannot be less than 1", 'error');
                    return;
                }
                if (jumlah > 10000) {
                    $(this).val(10000);
                    Swal.fire("Warning", "The number cannot exceed 10000", 'warning');
                    return;
                }

                $.post(`{{ url('/purchase_detail') }}/${id}`, {
                        '_token': $('[name=csrf-token]').attr('content'),
                        '_method': 'put',
                        'jumlah': jumlah
                    })
                    .done(response => {
                        $(this).on('mouseout', function() {
                            table.ajax.reload(() => loadForm($('#diskon').val()));
                        });
                    })
                    .fail(errors => {
                        Swal.fire("Information", "Unable to save purchase", 'info');
                        return;
                    });
            });

            $(document).on('input', '#diskon', function() {
                if ($(this).val() == "") {
                    $(this).val(0).select();
                }

                loadForm($(this).val());
            });

            $('.btn-simpan').on('click', function() {
                $('.form-pembelian').submit();
            });
        });

        // show the modal of product selection
        function tampilProduk() {
            $('#modal-produk').modal('show');
        }

        // hide the modal of product selection
        function hideProduk() {
            $('#modal-produk').modal('hide');
        }

        // function in product selection
        function pilihProduk(id, kode) {
            $('#id_produk').val(id);
            $('#kode_produk').val(kode);
            hideProduk();
            tambahProduk();
        }

        // adding the selected product into data table
        function tambahProduk() {
            $.post('{{ route('purchase_detail.store') }}', $('.form-produk').serialize())
                .done(response => {
                    $('#kode_produk').focus();
                    table.ajax.reload(() => loadForm($('#diskon').val()));
                    Swal.fire("Success", response.message, 'success');
                })
                .fail(errors => {
                    Swal.fire("Error", response.message, 'error');
                    return;
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
                                table.ajax.reload(() => loadForm($('#diskon').val()));
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
        function loadForm(diskon = 0) {
            $('#total').val($('.total').text());
            $('#total_item').val($('.total_item').text());

            $.get(`{{ url('/purchase_detail/loadform') }}/${diskon}/${$('.total').text()}`)
                .done(response => {
                    $('#totalrp').val('₱ ' + response.totalrp);
                    $('#bayarrp').val('₱ ' + response.bayarrp);
                    $('#bayar').val(response.bayar);
                    $('.tampil-bayar').text('₱ ' + response.bayarrp);
                    $('.tampil-terbilang').text(response.terbilang);
                })
                .fail(errors => {
                    Swal.fire("Error", "Unable to display data!", 'error');
                    return;
                })
        }
    </script>
@endpush
