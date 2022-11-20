@extends('layouts.master')
@section('title', 'Employees')

@section('content')

    <div class="container">
        <div class="d-flex justify-content-between mb-3">
            @can('create employee')
                <a href="{{ route('employee.create') }}" class="btn btn-theme"><i class="fas fa-plus-circle me-2"></i>Create
                    employee</a>
            @endcan

            @can('create employee')
                <button class="btn btn-success"
                    onclick="tablesToExcel(['Datatable'], ['Employee Data'], 'Employee.xls', 'Excel')">Export to
                    Excel</button>
            @endcan
        </div>
        <div class="card">
            <div class="card-body">
                <table id="Datatable" class="table table-bordered table-hover Datatable" style="width:100%">
                    <thead>
                        <th class="text-center no-sort no-search"></th>
                        <th class="text-center no-sort no-search">Name</th>
                        <th class="text-center">Employee ID</th>
                        <th class="text-center">Email</th>
                        <th class="text-center">Phone</th>
                        <th class="text-center">Department</th>
                        <th class="text-center no-sort no-search">Roles (or) Designation</th>
                        <th class="text-center ">Is present?</th>
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
                ajax: '/employee/datatable/ssd',
                columns: [{
                        data: 'plus_icon',
                        name: 'plus_icon',
                        'class': 'text-center'
                    },
                    {
                        data: 'image',
                        name: 'image',
                        'class': 'text-center'
                    },
                    {
                        data: 'employee_id',
                        name: 'employee_id',
                        'class': 'text-center'
                    },
                    {
                        data: 'email',
                        name: 'email',
                        'class': 'text-center'
                    },
                    {
                        data: 'phone',
                        name: 'phone',
                        'class': 'text-center'
                    },
                    {
                        data: 'department_name',
                        name: 'department_name',
                        'class': 'text-center'
                    },
                    {
                        data: 'role',
                        name: 'role',
                        'class': 'text-center'
                    },
                    {
                        data: 'is_present',
                        name: 'is_present',
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
                    [8, 'desc']
                ],
            });

            $(document).on('click', '.delete-btn', function(e) {
                e.preventDefault();

                var id = $(this).data('id');

                swal({
                        text: "Are you sure to delete this account?",
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((willDelete) => {
                        if (willDelete) {

                            $.ajax({
                                    method: "DELETE",
                                    url: `/employee/${id}`,
                                })
                                .done(function(response) {
                                    table.ajax.reload();
                                });

                            swal("This account has been deleted!", {
                                icon: "success",
                            });
                        }
                    });
            })
        })
    </script>
@endsection
