@extends('layouts.master')
@section('title', 'Edit Company Setting')

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
                <form action="{{ route('company_setting.update', $setting->id) }}" method="POST" enctype="multipart/form-data"
                    id="edit-form">
                    @csrf
                    @method('PUT')
                    <div class="form-outline mb-4">
                        <input type="text" name="company_name" class="form-control"
                            value="{{ old('company_name', $setting->company_name) }}" />
                        <label class="form-label">Company Name</label>
                    </div>

                    <div class="form-outline mb-4">
                        <input type="text" name="company_email" class="form-control"
                            value="{{ old('company_email', $setting->company_email) }}" />
                        <label class="form-label">Company Email</label>
                    </div>

                    <div class="form-outline mb-4">
                        <input type="text" name="company_phone" class="form-control"
                            value="{{ old('company_phone', $setting->company_phone) }}" />
                        <label class="form-label">Company Phone</label>
                    </div>

                    <div class="form-outline mb-4">
                        <textarea name="company_address" class="form-control">{{ old('company_address', $setting->company_address) }}</textarea>
                        <label class="form-label">Company Address</label>
                    </div>

                    <div class="form-outline mb-4">
                        <input type="text" name="office_start_time" class="form-control timepicker"
                            value="{{ old('office_start_time', $setting->office_start_time) }}" />
                        <label class="form-label">Office Start Time</label>
                    </div>

                    <div class="form-outline mb-4">
                        <input type="text" name="office_end_time" class="form-control timepicker"
                            value="{{ old('office_end_time', $setting->office_end_time) }}" />
                        <label class="form-label">Office End Time</label>
                    </div>

                    <div class="form-outline mb-4">
                        <input type="text" name="break_start_time" class="form-control timepicker"
                            value="{{ old('break_start_time', $setting->break_start_time) }}" />
                        <label class="form-label">Break Start Time</label>
                    </div>

                    <div class="form-outline mb-4">
                        <input type="text" name="break_end_time" class="form-control timepicker"
                            value="{{ old('break_end_time', $setting->break_end_time) }}" />
                        <label class="form-label">Break End Time</label>
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
    {!! JsValidator::formRequest('App\Http\Requests\UpdateCompanySetting', '#edit-form') !!}
    <script>
        $(document).ready(function() {
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
