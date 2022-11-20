@extends('layouts.master')
@section('title', 'Create Role')

@section('content')

    <div class="container">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('role.store') }}" method="POST" enctype="multipart/form-data" id="create-form">
                    @csrf
                    <div class="form-outline mb-3">
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" />
                        <label class="form-label">Name</label>
                    </div>

                    <p>Permissions</p>
                    <div class="row">
                        @foreach ($permissions as $permission)
                            <div class="col-md-3 col-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="permissions[]"
                                        value="{{ $permission->name }}" id="checkbox_{{ $permission->id }}" />
                                    <label class="form-check-label"
                                        for="checkbox_{{ $permission->id }}">{{ $permission->name }}</label>
                                </div>
                            </div>
                        @endforeach
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
    {!! JsValidator::formRequest('App\Http\Requests\StoreRole', '#create-form') !!}
    <script>
        $(document).ready(function() {})
    </script>
@endsection
