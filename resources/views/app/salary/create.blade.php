@extends('layouts.master')
@section('title', 'Create Salary')

@section('content')

    <div class="container">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('salary.store') }}" method="POST" enctype="multipart/form-data" id="create-form">
                    @csrf

                    <div class="form-group mb-4">
                        <label class="form-label">Employee</label>
                        <select name="user_id" class="form-select select-multiple" style="width:100%;">
                            <option value="">— Please Choose —</option>
                            @foreach ($employees as $employee)
                                <option value="{{ $employee->id }}" @if (old('user_id') == $employee->id) selected @endif>
                                    {{ $employee->employee_id }} ( {{ $employee->name }} )
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-4">
                        @php
                            $months = ['Jan', 'Feb', 'March', 'April', 'May', 'June', 'July', 'Aug', 'Sept', 'Oct', 'Nov', 'Dec'];
                            $values = ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'];
                        @endphp

                        <label>Month</label>
                        <select name="month" class="form-select select-multiple select-month" style="width:100%;">
                            <option value="">—— Choose Month ——</option>
                            @for ($i = 0; $i < count($months); $i++)
                                <option value="{{ $values[$i] }}">
                                    {{ $months[$i] }}</option>
                            @endfor
                        </select>
                    </div>

                    <div class="form-group mb-4">
                        <label>Year</label>
                        <select name="year" class="form-select select-multiple select-year" style="width:100%;">
                            <option value="">—— Choose Year ——</option>
                            @for ($i = 0; $i < 10; $i++)
                                <option value="{{ now()->subYears($i)->format('Y') }}">
                                    {{ now()->subYears($i)->format('Y') }}</option>
                            @endfor
                        </select>
                    </div>

                    <div class="form-group mb-4">
                        <label for="">Amount (MMK)</label>
                        <input type="number" name="amount" class="form-control" />
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-theme w-50 mx-auto mt-4 mb-3">Confirm</button>
                    </div>

                </form>
            </div>
        </div>
    </div>

@endsection

@section('script')
    {!! JsValidator::formRequest('App\Http\Requests\StoreSalary', '#create-form') !!}
    <script>
        $(document).ready(function() {})
    </script>
@endsection
