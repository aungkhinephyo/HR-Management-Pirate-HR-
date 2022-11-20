@extends('layouts.master')
@section('title', 'Company Setting')
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-end mb-3">
                @can('edit company setting')
                    <a href="{{ route('company_setting.edit', $setting->id) }}" class="btn btn-sm btn-theme"><i
                            class="fas fa-edit me-1"></i>
                        Edit
                        Setting</a>
                @endcan
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="col-md-12">
                        <p class="mb-0 fw-bold">Company Name</p>
                        <p class="text-muted">{{ $setting->company_name }}</p>
                    </div>
                    <div class="col-md-12">
                        <p class="mb-0 fw-bold">Company Email</p>
                        <p class="text-muted">{{ $setting->company_email }}</p>
                    </div>
                    <div class="col-md-12">
                        <p class="mb-0 fw-bold">Company Phone</p>
                        <p class="text-muted">{{ $setting->company_phone }}</p>
                    </div>
                    <div class="col-md-12">
                        <p class="mb-0 fw-bold">Company Address</p>
                        <p class="text-muted">{{ $setting->company_address }}</p>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="col-md-12">
                        <p class="mb-0 fw-bold">Office Start Time</p>
                        <p class="text-muted">{{ $setting->office_start_time }}</p>
                    </div>
                    <div class="col-md-12">
                        <p class="mb-0 fw-bold">Office End Time</p>
                        <p class="text-muted">{{ $setting->office_end_time }}</p>
                    </div>
                    <div class="col-md-12">
                        <p class="mb-0 fw-bold">Break Start Time</p>
                        <p class="text-muted">{{ $setting->break_start_time }}</p>
                    </div>
                    <div class="col-md-12">
                        <p class="mb-0 fw-bold">Break End Time</p>
                        <p class="text-muted">{{ $setting->break_end_time }}</p>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
