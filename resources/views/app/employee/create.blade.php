@extends('layouts.master')
@section('title', 'Create Employee')

@section('content')

    <div class="container">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('employee.store') }}" method="POST" enctype="multipart/form-data" id="create-form">
                    @csrf

                    <div class="form-outline mb-4">
                        <input type="text" name="employee_id" class="form-control" value="{{ old('employee_id') }}" />
                        <label class="form-label">Employee ID</label>
                    </div>

                    <div class="form-group mb-4">
                        <label class="form-label">Department</label>
                        <select name="department_id" class="form-select select-multiple" style="width:100%;">
                            <option value="">—— Please Choose ——</option>
                            @foreach ($departments as $department)
                                <option value="{{ $department->id }}">{{ $department->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-outline mb-4">
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" />
                        <label class="form-label">Name</label>
                    </div>

                    <div class="form-outline mb-4">
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}" />
                        <label class="form-label">Email</label>
                    </div>

                    <div class="form-outline mb-4">
                        <input type="number" name="phone" class="form-control" value="{{ old('phone') }}" />
                        <label class="form-label">Phone</label>
                    </div>

                    <div class="form-group mb-4">
                        <label class="form-label">Gender</label>
                        <select name="gender" class="form-select select-multiple" style="width:100%;">
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>
                    </div>

                    <div class="form-group mb-4">
                        <label class="form-label">Role (or) Designation</label>
                        <select name="roles[]" class="form-select select-multiple" multiple style="width:100%;">
                            @foreach ($roles as $role)
                                <option value="{{ $role->name }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-outline mb-4">
                        <input type="text" name="nrc_number" class="form-control" value="{{ old('nrc_number') }}" />
                        <label class="form-label">NRC Number</label>
                    </div>

                    <div class="form-outline mb-4">
                        <textarea name="address" class="form-control">{{ old('address') }}</textarea>
                        <label class="form-label">Address</label>
                    </div>

                    <div class="form-outline mb-4">
                        <input type="text" name="birthday" class="form-control active birthday"
                            value="{{ old('birthday') }}" />
                        <label class="form-label">Birthday</label>
                    </div>

                    <div class="form-outline mb-4">
                        <input type="text" name="date_of_join" class="form-control active date_of_join"
                            value="{{ old('date_of_join') }}" />
                        <label class="form-label">Date of Join</label>
                    </div>

                    <div class="form-group mb-4">
                        <label class="form-label">Is Present?</label>
                        <select name="is_present" class="form-select select-multiple" style="width:100%;">
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                    </div>

                    <div class="form-group mb-4">
                        <label class="form-label">Profile Image</label>
                        <input type="file" name="image" class="form-control mb-1" id="profile_img_input" />
                        <div class="preview_img"></div>
                    </div>

                    <div class="form-outline mb-4">
                        <input type="number" name="pin_code" class="form-control" />
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
    {!! JsValidator::formRequest('App\Http\Requests\StoreEmployee', '#create-form') !!}
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
