@extends('layouts.master')
@section('title', 'Create Project')

@section('content')

    <div class="container">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('project.store') }}" method="POST" enctype="multipart/form-data" id="create-form">
                    @csrf
                    <div class="form-outline mb-4">
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" />
                        <label class="form-label">Project Name</label>
                    </div>

                    <div class="form-outline mb-4">
                        <textarea name="description" class="form-control">{{ old('description') }}</textarea>
                        <label class="form-label">Description</label>
                    </div>

                    <div class="form-outline mb-4">
                        <input type="text" name="start_date" class="form-control active datepicker"
                            value="{{ old('start_date') }}" />
                        <label class="form-label">Start Date</label>
                    </div>

                    <div class="form-outline mb-4">
                        <input type="text" name="deadline" class="form-control active datepicker"
                            value="{{ old('deadline') }}" />
                        <label class="form-label">Deadline</label>
                    </div>

                    <div class="form-group mb-4">
                        <label>Leader</label>
                        <select name="leaders[]" class="form-select select-multiple" multiple style="width:100%;">
                            @foreach ($employees as $employee)
                                <option value="{{ $employee->id }}">{{ $employee->employee_id }} ( {{ $employee->name }} )
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-4">
                        <label>Team Members</label>
                        <select name="members[]" class="form-select select-multiple" multiple style="width:100%;">
                            @foreach ($employees as $employee)
                                <option value="{{ $employee->id }}">{{ $employee->employee_id }} ( {{ $employee->name }} )
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-4">
                        <label class="form-label">Priority</label>
                        <select name="priority" class="form-select select-multiple" style="width:100%;">
                            <option value="">—— Please Choose ——</option>
                            <option value="high">High</option>
                            <option value="middle">Middle</option>
                            <option value="low">Low</option>
                        </select>
                    </div>

                    <div class="form-group mb-4">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select select-multiple" style="width:100%;">
                            <option value="">—— Please Choose ——</option>
                            <option value="pending">pending</option>
                            <option value="in_progress">in progress</option>
                            <option value="complete">Complete</option>
                        </select>
                    </div>

                    <div class="form-group mb-4">
                        <label class="form-label">Project Image (Only jpg, jpeg, png)</label>
                        <input type="file" name="images[]" class="form-control mb-1" id="project_img_input"
                            accept="image/jpg, image/jpeg, image/png" multiple />
                        <div class="preview_img"></div>
                    </div>

                    <div class="form-group mb-4">
                        <label class="form-label">Project Files</label>
                        <input type="file" name="files[]" class="form-control" accept="application/pdf" multiple />
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
    {!! JsValidator::formRequest('App\Http\Requests\StoreProject', '#create-form') !!}
    <script>
        $(document).ready(function() {
            $('.datepicker').daterangepicker({
                "singleDatePicker": true,
                "showDropdowns": true,
                "autoApply": true,
                "locale": {
                    "format": "YYYY-MM-DD"
                },
            });

            $('#project_img_input').on('change', function(e) {
                var file_length = e.target.files.length;
                $('.preview_img').html('');
                for (let i = 0; i < file_length; i++) {
                    $('.preview_img').append(`<img src="${URL.createObjectURL(e.target.files[i])}"/>`)
                }
            })
        })
    </script>
@endsection
