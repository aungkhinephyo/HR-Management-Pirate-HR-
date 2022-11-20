@extends('layouts.master')
@section('title', 'Show Employee')
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row justify-content-center">
                <div class="col-md-6 text-center mb-3 mb-md-0">
                    <img src="{{ $employee->img_path() }}" class="profile_img"/>
                    <div class="ms-3 mt-1">
                        <h6 class="fw-bold mb-1">{{ $employee->name }}</h6>
                        <p class="text-muted mb-1">@ {{ $employee->employee_id }} | <span class="text-theme">{{ $employee->phone }}</span></p>
                        <p class="text-white badge rounded-pill bg-dark mb-1">{{ $employee->department ? $employee->department->name : '-' }}</p>
                        <p class="text-muted mb-1">
                            @foreach ($employee->roles as $role)
                                <span class="badge rounded-pill bg-theme">{{ $role->name }}</span>
                            @endforeach
                        </p>
                    </div>
                </div>

                <div class="col-md-6 px-3 dash-border">

                    <p class="mb-1"><strong>Email:</strong> <span class="text-muted">{{ $employee->email }}</span></p>
                    <p class="mb-1"><strong>Phone:</strong> <span class="text-muted">{{ $employee->phone }}</span></p>
                    <p class="mb-1"><strong>NRC Number:</strong> <span
                            class="text-muted">{{ $employee->nrc_number }}</span></p>
                    <p class="mb-1"><strong>Gender:</strong> <span
                            class="text-muted">{{ ucfirst($employee->gender) }}</span></p>
                    <p class="mb-1"><strong>Address:</strong> <span class="text-muted">{{ $employee->address }}</span></p>
                    <p class="mb-1"><strong>Date of Join:</strong> <span
                            class="text-muted">{{ $employee->date_of_join }}</span></p>
                    <p class="mb-1"><strong>Is Present:</strong>
                        @if ($employee->is_present == 1)
                            <span class="badge rounded-pill bg-success">Present</span>
                        @else
                            <span class="badge rounded-pill bg-danger">Leave</span>
                        @endif
                    </p>

                </div>
            </div>
        </div>
    </div>
@endsection
