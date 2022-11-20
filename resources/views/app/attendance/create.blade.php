@extends('layouts.master')
@section('title', 'Create Attendance')

@section('extra_css')
    <style>
        .daterangepicker .drp-buttons .cancelBtn {
            background: #000 !important;
            color: #fff !important;
        }
    </style>
@endsection

@section('content')

    <div class="container">
        <div class="card">
            <div class="card-body">

                @include('layouts.error')

                <form action="{{ route('attendance.store') }}" method="POST" enctype="multipart/form-data" id="create-form">
                    @csrf

                    <div class="form-group mb-4">
                        <label class="form-label">Employee</label>
                        <select name="user_id" class="form-select select-multiple" style="width: 100%">
                            <option value="">— Please Choose —</option>
                            @foreach ($employees as $employee)
                                <option value="{{ $employee->id }}" @if (old('user_id') == $employee->id) selected @endif>
                                    {{ $employee->employee_id }} ( {{ $employee->name }} )
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-4">
                        <label class="form-label">Date</label>
                        <input type="text" name="date" class="form-control date" value="{{ old('date') }}" />
                    </div>

                    <div class="form-group mb-4">
                        <label class="form-label">Checkin Time</label>
                        <input type="text" name="checkin_time" class="form-control timepicker"
                            value="{{ old('checkin_time') }}" />
                    </div>

                    <div class="form-group mb-4">
                        <label class="form-label">Checkout Time</label>
                        <input type="text" name="checkout_time" class="form-control timepicker"
                            value="{{ old('checkout_time') }}" />
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
    {!! JsValidator::formRequest('App\Http\Requests\StoreAttendance', '#create-form') !!}
    <script>
        $(document).ready(function() {
            $('.date').daterangepicker({
                "singleDatePicker": true,
                "showDropdowns": true,
                "autoApply": true,
                "locale": {
                    "format": "YYYY-MM-DD"
                },
            });

            $('.timepicker').daterangepicker({
                "singleDatePicker": true,
                "timePicker": true,
                "timePicker24Hour": true,
                "timePickerSeconds": true,
                "autoApply": true,
                "locale": {
                    "format": "HH:mm:ss"
                },
            }).on('show.daterangepicker', function(ev, picker) {
                picker.container.find('.calendar-table').hide();
            });
        })
    </script>
@endsection
