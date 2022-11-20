@extends('layouts.master')
@section('title', 'Create Department')

@section('content')

    <div class="container">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('department.store') }}" method="POST" enctype="multipart/form-data" id="create-form">
                    @csrf
                    <div class="form-outline mb-3">
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" />
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
    {!! JsValidator::formRequest('App\Http\Requests\StoreDepartment', '#create-form') !!}
    <script>
        $(document).ready(function() {})
    </script>
@endsection
