@extends('layouts.master')
@section('title', 'Role')

@section('content')

    <div class="container">
        <div class="mb-3">
            @can('create role')
                <a href="{{ route('role.create') }}" class="btn btn-theme"><i class="fas fa-plus-circle me-2"></i>Create
                    Role</a>
            @endcan
        </div>
        <div class="card">
            <div class="card-body">
                <table class="table table-bordered Datatable" style="width:100%">
                    <thead>
                        <th class="text-center no-sort no-search"></th>
                        <th class="text-center">Name</th>
                        <th class="text-center">Permissions</th>
                        <th class="text-center no-sort no-search">Action</th>
                        <th class="text-center no-search hidden">Update at</th>
                    </thead>
                </table>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script>
        $(document).ready(function() {

            var table = $('.Datatable').DataTable({
                ajax: '/role/datatable/ssd',
                columns: [{
                        data: 'plus_icon',
                        name: 'plus_icon',
                        'class': 'text-center'
                    },
                    {
                        data: 'name',
                        name: 'name',
                        'class': 'text-center'
                    },
                    {
                        data: 'permissions',
                        name: 'permissions',
                        'class': 'text-center'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        'class': 'text-center'
                    },
                    {
                        data: 'updated_at',
                        name: 'updated_at',
                        'class': 'text-center'
                    },
                ],
                order: [
                    [3, 'desc']
                ],
            });

            $(document).on('click', '.delete-btn', function(e) {
                e.preventDefault();

                var id = $(this).data('id');

                swal({
                        text: "Are you sure to delete this role?",
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((willDelete) => {
                        if (willDelete) {

                            $.ajax({
                                    method: "DELETE",
                                    url: `/role/${id}`,
                                })
                                .done(function(response) {
                                    table.ajax.reload();
                                });

                            swal("This role has been deleted!", {
                                icon: "success",
                            });
                        }
                    });
            })
        })
    </script>
@endsection
