@extends('layouts.master')
@section('title', 'Profile')
@section('content')
    <div class="card mb-3">
        <div class="card-body">
            <div class="row justify-content-center">
                <div class="col-md-6 text-center mb-3 mb-md-0">
                    <div class="profile_container">
                        <img src="{{ $employee->img_path() }}" class="profile_img" />
                        <a href="{{ route('profile.edit', $employee->id) }}" class="edit_profile"><i
                                class="fas fa-edit"></i></a>
                    </div>
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

                <div class="col-md-6 px-3 dash-border">

                    <p class="mb-1"><strong>Email:</strong> <span class="text-muted">{{ $employee->email }}</span></p>
                    <p class="mb-1"><strong>Phone:</strong> <span class="text-muted">{{ $employee->phone }}</span></p>
                    <p class="mb-1"><strong>NRC Number:</strong> <span
                            class="text-muted">{{ $employee->nrc_number }}</span></p>
                    <p class="mb-1"><strong>Gender:</strong> <span
                            class="text-muted">{{ ucfirst($employee->gender) }}</span></p>
                    <p class="mb-1"><strong>Address:</strong> <span class="text-muted">{{ $employee->address }}</span>
                    </p>
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

    <div class="card mb-3">
        <div class="card-body">
            <h5>Biometric Authentication</h5>

            <span id="biometric-data-component"></span>

            <form id="biometric-register-form">
                <button type="submit">
                    <i class="fas fa-fingerprint"></i>
                    <i class="fas fa-plus-circle"></i>
                </button>
            </form>
        </div>
    </div>
@endsection

@section('script')
    <script>
        biometricData();

        function biometricData() {
            $.ajax({
                url: '/biometrics_data',
                type: 'GET',
                success: function(response) {
                    $('#biometric-data-component').html(response);
                }
            })
        }


        const register = event => {
            event.preventDefault()

            const Toast = Swal.mixin({
                toast: true,
                position: 'top',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal
                        .stopTimer)
                    toast.addEventListener('mouseleave', Swal
                        .resumeTimer)
                }
            });

            new WebAuthn().register()
                .then(function(response) {
                    biometricData();
                    Toast.fire({
                        icon: 'success',
                        title: 'Biometric data is successfully created.'
                    })
                })
                .catch(function(error) {
                    Toast.fire({
                        icon: 'error',
                        title: "Something went wrong!!"
                    })
                })
        }
        document.getElementById('biometric-register-form').addEventListener('submit', register);
    </script>
@endsection
