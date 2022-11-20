@extends('layouts.master')
@section('title', 'Edit Department')

@section('content')

    <div class="container">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('department.update', $department->id) }}" method="POST" enctype="multipart/form-data"
                    id="edit-form">
                    @csrf
                    @method('PUT')
                    <div class="form-outline mb-3">
                        <input type="text" name="name" class="form-control"
                            value="{{ old('name', $department->name) }}" />
                        <label class="form-label">Name</label>
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
    {!! JsValidator::formRequest('App\Http\Requests\UpdateDepartment', '#edit-form') !!}
    <script>
        $(document).ready(function() {})
    </script>
@endsection
