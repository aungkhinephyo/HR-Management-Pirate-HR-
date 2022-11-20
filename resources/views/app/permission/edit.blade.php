@extends('layouts.master')
@section('title', 'Edit Permission')

@section('content')

    <div class="container">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('permission.update', $permission->id) }}" method="POST" enctype="multipart/form-data"
                    id="edit-form">
                    @csrf
                    @method('PUT')
                    <div class="form-outline mb-3">
                        <input type="text" name="name" class="form-control"
                            value="{{ old('name', $permission->name) }}" />
                        <label class="form-label">Title</label>
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
    {!! JsValidator::formRequest('App\Http\Requests\UpdatePermission', '#edit-form') !!}
    <script>
        $(document).ready(function() {})
    </script>
@endsection
