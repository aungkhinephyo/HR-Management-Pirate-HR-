@extends('layouts.master')
@section('title', 'Pirate HR')
@section('content')
    <div class="card mb-3">
        <div class="card-body">
            <div class="row justify-content-center">
                <div class="col-md-6 text-center">
                    <img src="{{ $employee->img_path() }}" class="profile_img" />
                    <div class="ms-3 mt-1">
                        <h6 class="fw-bold mb-1">{{ $employee->name }}</h6>
                        <p class="text-muted mb-1">@ {{ $employee->employee_id }} | <span
                                class="text-theme">{{ $employee->phone }}</span></p>
                        <p class="text-white badge rounded-pill bg-dark mb-1">
                            {{ $employee->department ? $employee->department->name : '-' }}</p>
                        <p class="text-muted mb-1">
                            @foreach ($employee->roles as $role)
                                <span class="badge rounded-pill bg-theme">{{ $role->name }}</span>
                            @endforeach
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection
@section('script')
@endsection
