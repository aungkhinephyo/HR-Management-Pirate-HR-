@extends('layouts.master')
@section('title', 'Attendance Scan')

@section('content')

    <div class="card mb-3">
        <div class="card-body">
            <div class="text-center">
                <img src="{{ asset('images/scan.png') }}" alt="Scan Image" width="250px" />
                <p class="text-muted mb-3">Please scan QR code.</p>

                <!-- Button trigger modal -->
                <button type="button" class="btn btn-sm btn-theme" data-mdb-toggle="modal" data-mdb-target="#scanModal">
                    SCAN
                </button>

            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">

            <div class="row mb-4">

                <div class="col-md-4 col-sm-6 mb-3">
                    <div class="form-group">

                        @php
                            $months = ['Jan', 'Feb', 'March', 'April', 'May', 'June', 'July', 'Aug', 'Sept', 'Oct', 'Nov', 'Dec'];
                            $values = ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'];
                        @endphp

                        <select name="month" class="form-select select-multiple select-month" style="width:100%;">
                            <option value="">—— Choose Month ——</option>
                            @for ($i = 0; $i < count($months); $i++)
                                <option value="{{ $values[$i] }}" @if (now()->format('m') == $values[$i]) selected @endif>
                                    {{ $months[$i] }}</option>
                            @endfor
                        </select>

                    </div>
                </div>

                <div class="col-md-4 col-sm-6 mb-3">
                    <div class="form-group">
                        <select name="year" class="form-select select-multiple select-year" style="width:100%;">
                            <option value="">—— Choose Year ——</option>
                            @for ($i = 0; $i < 5; $i++)
                                <option value="{{ now()->subYears($i)->format('Y') }}"
                                    @if (now()->format('Y') ===
                                        now()->subYears($i)->format('Y')) selected @endif>
                                    {{ now()->subYears($i)->format('Y') }}</option>
                            @endfor
                        </select>
                    </div>
                </div>

                <div class="col-md-4 col-sm-6 mb-3">
                    <button type="button" class="btn btn-block btn-theme search-btn"><i class="fas fa-search"></i>
                        Search</button>
                </div>

            </div>

            <h5 class="mb-3">Attendance Overview</h5>
            <div class="attendance_overview_table mb-3"></div>

            <h5 class="mb-3">Payroll</h5>
            <div class="payroll_table"></div>

            <h5 class="mb-3">Attendance Records</h5>
            <table class="table table-bordered Datatable" style="width:100%">
                <thead>
                    <th class="text-center no-sort no-search"></th>
                    <th class="text-center">Date</th>
                    <th class="text-center">Checkin Time</th>
                    <th class="text-center">Checkout Time</th>
                </thead>
            </table>
        </div>
    </div>

    <!--Scan Modal -->
    <div class="modal fade" id="scanModal" tabindex="-1" aria-labelledby="scanModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="scanModalLabel">Scan Attendance QR</h5>
                    <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <video id="video" width="100%" height="300px"></video>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-dark" data-mdb-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('js/scanner/qr-scanner.umd.min.js') }}"></script>
    <script>
        $(document).ready(function() {

            /* datatable */
            var table = $('.Datatable').DataTable({
                ajax: '/my_attendance/datatable/ssd',
                columns: [{
                        data: 'plus_icon',
                        name: 'plus_icon',
                        'class': 'text-center'
                    },
                    {
                        data: 'date',
                        name: 'date',
                        'class': 'text-center'
                    },
                    {
                        data: 'checkin_time',
                        name: 'checkin_time',
                        'class': 'text-center'
                    },
                    {
                        data: 'checkout_time',
                        name: 'checkout_time',
                        'class': 'text-center'
                    }
                ],
                order: [
                    [1, 'desc']
                ],
            });

            /* scan QR code */
            var videoElem = document.getElementById('video');
            const qrScanner = new QrScanner(videoElem, function(result) {
                console.log(result);
                if (result) {
                    // $('#scanModal').hide();
                    // $('.modal-backdrop').remove();
                    $('#scanModal').modal('hide');
                    qrScanner.stop();

                    $.ajax({
                        url: "/attendance_scan/store",
                        type: "POST",
                        data: {
                            "hash_value": result
                        },
                        dataType: 'json',
                        success: function(response) {

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

                            if (response.status === 'success') {
                                Toast.fire({
                                    icon: 'success',
                                    title: response.message
                                })
                            } else {
                                Toast.fire({
                                    icon: 'error',
                                    title: response.message
                                })
                            }

                        }
                    })
                }
            });

            $('#scanModal').on('show.bs.modal', event => {
                qrScanner.start();
            })
            $('#scanModal').on('hidden.bs.modal', event => {
                qrScanner.stop();
            })

            /* attendance overview */
            attendanceOverviewTable();

            function attendanceOverviewTable() {
                var month = $('.select-month').val();
                var year = $('.select-year').val();

                $.ajax({
                    url: `/my_attendance_overview_table?month=${month}&year=${year}`,
                    type: "GET",
                    success: function(response) {
                        $('.attendance_overview_table').html(response);
                    },
                });

                table.ajax.url(`/my_attendance/datatable/ssd?month=${month}&year=${year}`).load();
            }

            /* payroll */
            payrollTable();

            function payrollTable() {
                var month = $('.select-month').val();
                var year = $('.select-year').val();

                $.ajax({
                    url: `/my_payroll_table?month=${month}&year=${year}`,
                    type: "GET",
                    success: function(response) {
                        $('.payroll_table').html(response);
                    },
                })
            }


            $(document).on('click', '.search-btn', function(e) {
                e.preventDefault();
                attendanceOverviewTable();
                payrollTable();
            });



        })
    </script>
@endsection
