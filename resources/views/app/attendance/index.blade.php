@extends('layouts.master')
@section('title', 'Attendance')

@section('content')

    <div class="container">
        <div class="d-flex justify-content-between mb-3">
            @can('create attendance')
                <a href="{{ route('attendance.create') }}" class="btn btn-theme"><i class="fas fa-plus-circle me-2"></i>Create
                    Attendance</a>
            @endcan

            @can('create attendance')
                <button class="btn btn-success"
                    onclick="tablesToExcel(['Datatable'], ['Attendance Data'], 'Attendance.xls', 'Excel')">Export to
                    Excel</button>
            @endcan
        </div>
        <div class="card">
            <div class="card-body">
                <table id="Datatable" class="table table-bordered Datatable" style="width:100%">
                    <thead>
                        <th class="text-center no-sort no-search"></th>
                        <th class="text-center">Employee</th>
                        <th class="text-center">Date</th>
                        <th class="text-center">Checkin Time</th>
                        <th class="text-center">Checkout Time</th>
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
                ajax: '/attendance/datatable/ssd',
                columns: [{
                        data: 'plus_icon',
                        name: 'plus_icon',
                        'class': 'text-center'
                    },
                    {
                        data: 'employee_name',
                        name: 'employee_name',
                        'class': 'text-center'
                    },
                    {
                        data: 'date',
                        name: 'date',
                        'class': 'text-center'
                    },
                    {
                        data: 'checkin_time',
                        name: 'checkin_time',
                        'class': 'text-center'
                    },
                    {
                        data: 'checkout_time',
                        name: 'checkout_time',
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
                        text: "Are you sure to delete this attendance?",
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((willDelete) => {
                        if (willDelete) {

                            $.ajax({
                                    method: "DELETE",
                                    url: `/attendance/${id}`,
                                })
                                .done(function(response) {
                                    table.ajax.reload();
                                });

                            swal("This attendance has been deleted!", {
                                icon: "success",
                            });
                        }
                    });
            })
        })
    </script>
@endsection
