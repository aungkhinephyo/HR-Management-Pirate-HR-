@extends('layouts.master')
@section('title', 'Update Password')

@section('content')

    <div class="container">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('update_password') }}" method="POST" id="edit-form">
                    @csrf
                    @include('layouts.error')
                    @if (session('current_password_wrong'))
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            <strong class="text-danger"><i class="fas fa-info-circle text-danger me-2"></i>
                                {{ session('current_password_wrong') }}</strong>
                        </div>
                    @endif
                    <div class="form-outline mb-4">
                        <input type="password" name="current_password" class="form-control" />
                        <label class="form-label">Current Password</label>
                    </div>

                    <div class="form-outline mb-4">
                        <input type="password" name="new_password" class="form-control" />
                        <label class="form-label">New Password</label>
                    </div>
                    <div class="form-outline mb-4">
                        <input type="password" name="confirm_password" class="form-control" />
                        <label class="form-label">Confirm Password</label>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-theme w-50 mx-auto mt-4 mb-3">Confirm</button>
                    </div>

                </form>
            </div>
        </div>
    </div>

@endsection
