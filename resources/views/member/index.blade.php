@extends('layouts.master')

@section('title')
    List of Members
@endsection

@section('breadcrumb')
    @parent
    <li class="active">List of Members</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="box">
                <div class="box-header with-border">
                    <button onclick="addForm('{{ route('member.store') }}')" class="btn btn-success btn-flat"><i
                            class="fa fa-plus-circle"></i> Add New Member</button>
                    <button onclick="cetakMember('{{ route('member.cetak_member') }}')" class="btn btn-primary btn-flat"><i
                            class="fa fa-id-card"></i> Download Membership Card</button>
                </div>
                <div class="box-body table-responsive">
                    <form action="" method="post" class="form-member">
                        @csrf
                        <table class="table table-stiped table-bordered table-hover">
                            <thead>
                                <th width="5%">
                                    <input type="checkbox" name="select_all" id="select_all">
                                </th>
                                <th width="5%">#</th>
                                <th>Code</th>
                                <th>Name</th>
                                <th>Telephone</th>
                                <th>Address</th>
                                <th width="15%"><i class="fa fa-cog"></i></th>
                            </thead>
                        </table>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- visit "codeastro" for more projects! -->
    @includeIf('member.form')
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
                    url: '{{ route('member.data') }}',
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
                        data: 'kode_member'
                    },
                    {
                        data: 'nama'
                    },
                    {
                        data: 'telepon'
                    },
                    {
                        data: 'alamat'
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
                            Swal.fire("Success", response.message, 'success');
                            $('#modal-form').modal('hide');
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

        // add form
        function addForm(url) {
            $('#modal-form').modal('show');
            $('#modal-form .modal-title').text('Add Member');

            $('#modal-form form')[0].reset();
            $('#modal-form form').attr('action', url);
            $('#modal-form [name=_method]').val('post');
            $('#modal-form [name=nama]').focus();
        }

        // edit form
        function editForm(url) {
            $('#modal-form').modal('show');
            $('#modal-form .modal-title').text('Edit Member');

            $('#modal-form form')[0].reset();
            $('#modal-form form').attr('action', url);
            $('#modal-form [name=_method]').val('put');
            $('#modal-form [name=nama]').focus();

            $.get(url)
                .done((response) => {
                    $('#modal-form [name=nama]').val(response.nama);
                    $('#modal-form [name=telepon]').val(response.telepon);
                    $('#modal-form [name=alamat]').val(response.alamat);
                })
                .fail((errors) => {
                    Swal.fire("Error", response.message, 'error');
                    return;
                });
        }

        // delete the specific branch
        function deleteData(url) {
            Swal.fire({
                    title: "Are you sure?",
                    text: "Once deleted, you will not be able to recover this branch!",
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
                        Swal.fire("Canceled", "The branch remains secure.", "success");
                    }
                });
        }

        // generate card in branch
        function cetakMember(url) {
            const selectedBranchesCount = $('input:checked').length;

            if (selectedBranchesCount < 1) {
                Swal.fire("Information", "Select branch to print!", 'info');
            } else {
                $('.form-member')
                    .attr('target', '_blank')
                    .attr('action', url)
                    .submit();
            }
        }
    </script>
@endpush
