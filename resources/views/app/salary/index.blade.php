@extends('layouts.master')
@section('title', 'Salaries')

@section('content')

    <div class="container">
        <div class="d-flex justify-content-between mb-3">
            @can('create salary')
                <a href="{{ route('salary.create') }}" class="btn btn-theme"><i class="fas fa-plus-circle me-2"></i>Create
                    Salary</a>
            @endcan

            @can('create salary')
                <button class="btn btn-success"
                    onclick="tablesToExcel(['Datatable'], ['Salary Data'], 'Salary.xls', 'Excel')">Export to
                    Excel</button>
            @endcan
        </div>
        <div class="card">
            <div class="card-body">
                <table id="Datatable" class="table table-bordered Datatable" style="width:100%">
                    <thead>
                        <th class="text-center no-sort no-search"></th>
                        <th class="text-center">Employee</th>
                        <th class="text-center">Month</th>
                        <th class="text-center">Year</th>
                        <th class="text-center">Salary (MMK)</th>
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
                ajax: '/salary/datatable/ssd',
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
                        data: 'month',
                        name: 'month',
                        'class': 'text-center'
                    },
                    {
                        data: 'year',
                        name: 'year',
                        'class': 'text-center'
                    },
                    {
                        data: 'amount',
                        name: 'amount',
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
                    [1, 'desc']
                ],
            });

            $(document).on('click', '.delete-btn', function(e) {
                e.preventDefault();

                var id = $(this).data('id');

                swal({
                        text: "Are you sure to delete this salary?",
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((willDelete) => {
                        if (willDelete) {

                            $.ajax({
                                    method: "DELETE",
                                    url: `/salary/${id}`,
                                })
                                .done(function(response) {
                                    table.ajax.reload();
                                });

                            swal("This salary has been deleted!", {
                                icon: "success",
                            });
                        }
                    });
            })
        })
    </script>
@endsection
