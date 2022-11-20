@extends('layouts.master')
@section('title', 'Payroll Overview')

@section('content')

    <div class="card">
        <div class="card-body">

            @can('view payroll')
                <div class="row justify-content-between mb-3">
                    <div class="col-md-3"></div>
                    <div class="col-md-3 text-end">
                        <button class="btn btn-success"
                            onclick="tablesToExcel(['Datatable'], ['Payroll Data'], 'Payroll.xls', 'Excel')">Export to
                            Excel</button>
                    </div>
                </div>
            @endcan

            <div class="row mb-3">

                <div class="col-md-3 col-sm-6 mb-3">
                    <input type="text" name="employee_name" class="form-control employee_name"
                        placeholder="Employee Name" />
                </div>

                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="form-group">
                        @php
                            $months = ['Jan', 'Feb', 'March', 'April', 'May', 'June', 'July', 'Aug', 'Sept', 'Oct', 'Nov', 'Dec'];
                            $values = ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'];
                        @endphp
                        <select name="month" class="form-select select-multiple select-month" style="width: 100%">
                            <option value="">—— Choose Month ——</option>
                            @for ($i = 0; $i < count($months); $i++)
                                <option value="{{ $values[$i] }}" @if (now()->format('m') == $values[$i]) selected @endif>
                                    {{ $months[$i] }}</option>
                            @endfor
                        </select>
                    </div>
                </div>

                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="form-group">
                        <select name="year" class="form-select select-multiple select-year" style="width: 100%">
                            <option value="">—— Choose Year ——</option>
                            @for ($i = 0; $i < 5; $i++)
                                <option value="{{ now()->subYears($i)->format('Y') }}"
                                    @if (now()->format('Y') ===
                                        now()->subYears($i)->format('Y')) selected @endif>
                                    {{ now()->subYears($i)->format('Y') }}</option>
                            @endfor
                        </select>
                    </div>
                </div>

                <div class="col-md-3 col-sm-6 mb-3">
                    <button type="button" class="btn btn-block btn-theme search-btn"><i class="fas fa-search"></i>
                        Search</button>
                </div>

            </div>

            <div class="payroll_table"></div>
        </div>
    </div>

@endsection

@section('script')
    <script src="{{ asset('js/export/export.js') }}"></script>

    <script>
        $(document).ready(function() {

            payrollTable();

            function payrollTable() {
                var employee_name = $('.employee_name').val();
                var month = $('.select-month').val();
                var year = $('.select-year').val();

                $.ajax({
                    url: `/payroll_table?employee_name=${employee_name}&month=${month}&year=${year}`,
                    type: "GET",
                    success: function(response) {
                        $('.payroll_table').html(response);
                    },
                })
            }

            $(document).on('click', '.search-btn', payrollTable);

        })
    </script>
@endsection
