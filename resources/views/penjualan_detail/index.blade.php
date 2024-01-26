@extends('layouts.master')

@section('title')
    Pull Out Transactions
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

        .table-penjualan tbody tr:last-child {
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
    <li class="active">Pull Out Transactions</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="box">
                <div class="box-body">

                    <form class="form-produk">
                        @csrf
                        <div class="form-group row">
                            <label for="kode_produk" class="col-lg-2">Item Code</label>
                            <div class="col-lg-5">
                                <div class="input-group">
                                    <input type="hidden" name="id_penjualan" id="id_penjualan" value="{{ $id_penjualan }}">
                                    <input type="hidden" name="id_produk" id="id_produk">
                                    <input type="text" class="form-control" name="kode_produk" id="kode_produk">
                                    <span class="input-group-btn">
                                        <button onclick="tampilProduk()" class="btn btn-success btn-flat" type="button"><i
                                                class="fa fa-search-plus"></i></button>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </form>

                    <table class="table table-stiped table-bordered table-penjualan">
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
                            <form action="{{ route('transaksi.simpan') }}" class="form-penjualan" method="post">
                                @csrf
                                <input type="hidden" name="id_penjualan" value="{{ $id_penjualan }}">
                                <input type="hidden" name="total" id="total">
                                <input type="hidden" name="total_item" id="total_item">
                                <input type="hidden" name="bayar" id="bayar">
                                <input type="hidden" name="diterima" id="changebayar">
                                <input type="hidden" name="id_member" id="id_member"
                                    value="{{ $memberSelected->id_member }}">

                                <div class="form-group row">
                                    <label for="totalrp" class="col-lg-2 control-label">Total</label>
                                    <div class="col-lg-8">
                                        <input type="text" id="totalrp" class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="kode_member" class="col-lg-2 control-label">Branch</label>
                                    <div class="col-lg-8">
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="kode_member"
                                                value="{{ $memberSelected->kode_member }}">
                                            <span class="input-group-btn">
                                                <button onclick="tampilMember()" class="btn btn-success btn-flat"
                                                    type="button"><i class="fa fa-search-plus"></i></button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="bayarrp" class="col-lg-2 control-label">Pay</label>
                                    <div class="col-lg-8">
                                        <input type="text" id="bayarrp" class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="changerp" class="col-lg-2 control-label">Received</label>
                                    <div class="col-lg-8">
                                        <input type="text" id="changerp" class="form-control"readonly>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="box-footer">
                    <button type="submit" class="btn btn-success btn-sm btn-flat pull-right btn-simpan"><i
                            class="fa fa-floppy-o"></i> Save Transaction</button>
                </div>
            </div>
        </div>
    </div>

    @includeIf('penjualan_detail.produk')
    @includeIf('penjualan_detail.member')
@endsection

@push('scripts')
    <script>
        let table, table2;

        // data table
        $(function() {
            $('body').addClass('sidebar-collapse');

            table = $('.table-penjualan').DataTable({
                    responsive: true,
                    processing: true,
                    serverSide: true,
                    autoWidth: false,
                    ajax: {
                        url: '{{ route('transaksi.data', $id_penjualan) }}',
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
                            data: 'harga_jual'
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
                    loadForm();
                });
            table2 = $('.table-produk').DataTable();

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

                $.post(`{{ url('/transaksi') }}/${id}`, {
                        '_token': $('[name=csrf-token]').attr('content'),
                        '_method': 'put',
                        'jumlah': jumlah
                    })
                    .done(response => {
                        $(this).on('mouseout', function() {
                            table.ajax.reload(() => loadForm());
                        });
                    })
                    .fail(errors => {
                        Swal.fire("Error", "Unable to save data", 'error');
                        return;
                    });
            });

            $(document).on('input', function() {
                if ($(this).val() == "") {
                }

                loadForm($(this).val());
            });


            $('.btn-simpan').on('click', function() {
                $('.form-penjualan').submit();
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

        // get the product details
        function pilihProduk(id, kode) {
            $('#id_produk').val(id);
            $('#kode_produk').val(kode);
            hideProduk();
            tambahProduk();
        }

        // add the products
        function tambahProduk() {
            $.post('{{ route('transaksi.store') }}', $('.form-produk').serialize())
                .done(response => {
                    $('#kode_produk').focus();
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
        function tampilMember() {
            $('#modal-member').modal('show');
        }

        // get the member details
        function pilihMember(id, kode) {
            $('#id_member').val(id);
            $('#kode_member').val(kode);
            loadForm();
            hideMember();
        }

        // hide the member modal
        function hideMember() {
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
        // realtime displaying the details
        function loadForm() {
            $('#total').val($('.total').text());
            $('#total_item').val($('.total_item').text());

            $.get(`{{ url('/transaksi/loadform') }}/${$('.total').text()}`)
                .done(response => {
                    $('#totalrp').val('₱ ' + response.totalrp);
                    $('#bayarrp').val('₱ ' + response.bayarrp);
                    $('#changerp').val('₱ ' + response.changerp);
                    $('#bayar').val(response.bayar);
                    $('#changebayar').val(response.bayar);
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
