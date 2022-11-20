@extends('layouts.master')
@section('title', 'Departments')

@section('content')

    <div class="container">
        <div class="d-flex justify-content-between mb-3">
            @can('create department')
                <a href="{{ route('department.create') }}" class="btn btn-theme"><i class="fas fa-plus-circle me-2"></i>Create
                    Department</a>
            @endcan

            @can('create department')
                <button class="btn btn-success"
                    onclick="tablesToExcel(['Datatable'], ['Department Data'], 'Departments.xls', 'Excel')">Export to
                    Excel</button>
            @endcan
        </div>
        <div class="card">
            <div class="card-body">
                <table id="Datatable" class="table table-bordered Datatable" style="width:100%">
                    <thead>
                        <th class="text-center no-sort no-search"></th>
                        <th class="text-center">Name</th>
                        <th class="text-center no-sort no-search">Action</th>
                        <th class="text-center no-search hidden">Update at</th>
                    </thead>
                </table>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script src="{{ asset('js/export/export.js') }}"></script>

    <script>
        $(document).ready(function() {

            var table = $('.Datatable').DataTable({
                ajax: '/department/datatable/ssd',
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
                        text: "Are you sure to delete this department?",
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((willDelete) => {
                        if (willDelete) {

                            $.ajax({
                                    method: "DELETE",
                                    url: `/department/${id}`,
                                })
                                .done(function(response) {
                                    table.ajax.reload();
                                });

                            swal("This department has been deleted!", {
                                icon: "success",
                            });
                        }
                    });
            })
        })
    </script>
@endsection
