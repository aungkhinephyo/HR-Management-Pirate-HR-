@extends('layouts.master')
@section('title', 'Edit Employee')

@section('content')

    <div class="container">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('employee.update', $employee->id) }}" method="POST" enctype="multipart/form-data"
                    id="edit-form">
                    @csrf
                    @method('PUT')
                    <div class="form-outline mb-4">
                        <input type="text" name="employee_id" class="form-control"
                            value="{{ old('employee_id', $employee->employee_id) }}" />
                        <label class="form-label">Employee ID</label>
                    </div>

                    <div class="form-group mb-4">
                        <label class="form-label">Department</label>
                        <select name="department_id" class="form-select select-multiple" style="width:100%;">
                            @foreach ($departments as $department)
                                <option value="{{ $department->id }}" @if ($employee->department_id == $department->id) selected @endif>
                                    {{ $department->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-outline mb-4">
                        <input type="text" name="name" class="form-control"
                            value="{{ old('name', $employee->name) }}" />
                        <label class="form-label">Name</label>
                    </div>

                    <div class="form-outline mb-4">
                        <input type="email" name="email" class="form-control"
                            value="{{ old('email', $employee->email) }}" />
                        <label class="form-label">Email</label>
                    </div>

                    <div class="form-outline mb-4">
                        <input type="number" name="phone" class="form-control"
                            value="{{ old('phone', $employee->phone) }}" />
                        <label class="form-label">Phone</label>
                    </div>

                    <div class="form-group mb-4">
                        <label class="form-label">Gender</label>
                        <select name="gender" class="form-select select-multiple" style="width:100%;">
                            <option value="male" @if ($employee->gender == 'male') selected @endif>Male</option>
                            <option value="female" @if ($employee->gender == 'female') selected @endif>Female</option>
                        </select>
                    </div>

                    <div class="form-group mb-4">
                        <label class="form-label">Role (or) Designation</label>
                        <select name="roles[]" class="form-select select-multiple" multiple style="width:100%;">
                            @foreach ($roles as $role)
                                <option value="{{ $role->name }}" @if (in_array($role->name, $old_roles)) selected @endif>
                                    {{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-outline mb-4">
                        <input type="text" name="nrc_number" class="form-control"
                            value="{{ old('nrc_number', $employee->nrc_number) }}" />
                        <label class="form-label">NRC Number</label>
                    </div>

                    <div class="form-outline mb-4">
                        <textarea name="address" class="form-control">{{ old('address', $employee->address) }}</textarea>
                        <label class="form-label">Address</label>
                    </div>

                    <div class="form-outline mb-4">
                        <input type="text" name="birthday" class="form-control birthday"
                            value="{{ old('birthday', $employee->birthday) }}" />
                        <label class="form-label">Birthday</label>
                    </div>

                    <div class="form-outline mb-4">
                        <input type="text" name="date_of_join" class="form-control date_of_join"
                            value="{{ old('date_of_join', $employee->date_of_join) }}" />
                        <label class="form-label">Date of Join</label>
                    </div>

                    <div class="form-group mb-4">
                        <label class="form-label">Is Present?</label>
                        <select name="is_present" class="form-select select-multiple" style="width:100%;">
                            <option value="1" @if ($employee->is_present == 1) selected @endif>Yes</option>
                            <option value="0" @if ($employee->is_present == 0) selected @endif>No</option>
                        </select>
                    </div>

                    <div class="form-group mb-4">
                        <label class="form-label">Profile Image</label>
                        <input type="file" name="image" class="form-control mb-2" id="profile_img_input" />
                        <div class="preview_img">
                            @if ($employee->image)
                                <img src="{{ $employee->img_path() }}" />
                            @endif
                        </div>
                    </div>

                    <div class="form-outline mb-4">
                        <input type="number" name="pin_code" class="form-control"
                            value="{{ old('pin_code', $employee->pin_code) }}" />
                        <label class="form-label">Pin Code</label>
                    </div>

                    <div class="form-outline mb-4">
                        <input type="password" name="password" class="form-control" />
                        <label class="form-label">Password</label>
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
    {!! JsValidator::formRequest('App\Http\Requests\UpdateEmployee', '#edit-form') !!}
    <script>
        $(document).ready(function() {
            $('.birthday').daterangepicker({
                "singleDatePicker": true,
                "showDropdowns": true,
                "autoApply": true,
                "drops": "up",
                "maxDate": moment(),
                "locale": {
                    "format": "YYYY-MM-DD"
                },
            });

            $('.date_of_join').daterangepicker({
                "singleDatePicker": true,
                "showDropdowns": true,
                "autoApply": true,
                "drops": "up",
                "locale": {
                    "format": "YYYY-MM-DD"
                },
            });

            $('#profile_img_input').on('change', function(e) {
                var file_length = e.target.files.length;
                $('.preview_img').html('');
                for (let i = 0; i < file_length; i++) {
                    $('.preview_img').append(`<img src="${URL.createObjectURL(e.target.files[i])}"/>`)
                }
            })
        })
    </script>
@endsection
