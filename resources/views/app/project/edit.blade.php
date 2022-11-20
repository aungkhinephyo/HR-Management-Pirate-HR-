@extends('layouts.master')
@section('title', 'Edit Project')

@section('content')

    <div class="container">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('project.update', $project->id) }}" method="POST" enctype="multipart/form-data"
                    id="edit-form">
                    @csrf
                    @method('PUT')
                    <div class="form-group mb-4">
                        <label class="form-label">Project Name</label>
                        <input type="text" name="name" class="form-control"
                            value="{{ old('name', $project->name) }}" />
                    </div>

                    <div class="form-group mb-4">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control">{{ old('description', $project->description) }}</textarea>
                    </div>

                    <div class="form-group mb-4">
                        <label class="form-label">Start Date</label>
                        <input type="text" name="start_date" class="form-control datepicker"
                            value="{{ old('start_date', $project->start_date) }}" />
                    </div>

                    <div class="form-group mb-4">
                        <label class="form-label">Deadline</label>
                        <input type="text" name="deadline" class="form-control datepicker"
                            value="{{ old('deadline', $project->deadline) }}" />
                    </div>

                    <div class="form-group mb-4">
                        <label>Leader</label>
                        <select name="leaders[]" class="form-select select-multiple" multiple style="width:100%;">
                            @foreach ($employees as $employee)
                                <option value="{{ $employee->id }}" @if (in_array(
                                    $employee->id,
                                    collect($project->leaders)->pluck('id')->toArray())) selected @endif>
                                    {{ $employee->employee_id }} ( {{ $employee->name }} )
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-4">
                        <label>Team Members</label>
                        <select name="members[]" class="form-select select-multiple" multiple style="width:100%;">
                            @foreach ($employees as $employee)
                                <option value="{{ $employee->id }}" @if (in_array(
                                    $employee->id,
                                    collect($project->members)->pluck('id')->toArray())) selected @endif>
                                    {{ $employee->employee_id }} ( {{ $employee->name }} )
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-4">
                        <label class="form-label">Priority</label>
                        <select name="priority" class="form-control select-multiple" style="width:100%;">
                            <option value="">—— Please Choose ——</option>
                            <option value="high" @if ($project->priority === 'high') selected @endif>High</option>
                            <option value="middle" @if ($project->priority === 'middle') selected @endif>Middle</option>
                            <option value="low" @if ($project->priority === 'low') selected @endif>Low</option>
                        </select>
                    </div>

                    <div class="form-group mb-4">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-control select-multiple" style="width:100%;">
                            <option value="">—— Please Choose ——</option>
                            <option value="pending" @if ($project->status === 'pending') selected @endif>pending</option>
                            <option value="in_progress" @if ($project->status === 'in_progress') selected @endif>in progress
                            </option>
                            <option value="complete" @if ($project->status === 'complete') selected @endif>Complete</option>
                        </select>
                    </div>

                    <div class="form-group mb-4">
                        <label class="form-label">Project Image (Only jpg, jpeg, png)</label>
                        <input type="file" name="images[]" class="form-control mb-2" id="project_img_input"
                            accept="image/jpg, image/jpeg, image/png" multiple />
                        <div class="preview_img">
                            @if ($project->images)
                                @foreach ($project->images as $image)
                                    <img src="{{ $project->img_path() . '/' . $image }}" />
                                @endforeach
                            @endif

                        </div>
                    </div>

                    <div class="form-group mb-4">
                        <label class="form-label">Project Files</label>
                        <input type="file" name="files[]" class="form-control mb-2" accept="application/pdf" multiple />
                        @if ($project->files)
                            <div class="d-flex flex-wrap">
                                @foreach ($project->files as $file)
                                    <a href="{{ $project->file_path() . '/' . $file }}" target="_blank"
                                        class="pdf_thumbnail" data-mdb-toggle="tooltip" title="{{ $file }}">
                                        <i class="fas fa-file-pdf fa-2x"></i>
                                        <span> File {{ $loop->iteration }}</span>
                                    </a>
                                @endforeach
                            </div>
                        @endif
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
    {!! JsValidator::formRequest('App\Http\Requests\UpdateProject', '#edit-form') !!}
    <script>
        $(document).ready(function() {
            $('.datepicker').daterangepicker({
                "singleDatePicker": true,
                "showDropdowns": true,
                "autoApply": true,
                "drops": "up",
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
