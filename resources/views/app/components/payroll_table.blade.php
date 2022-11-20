<div class="table-responsive">
    <table id="Datatable" class="table table-bordered table-hover">
        <thead>
            <th>Employee</th>
            <th>Role</th>
            <th>Days of Month</th>
            <th>Working Days</th>
            <th>Off Days</th>
            <th>Attendance Days</th>
            <th>Absent</th>
            <th>Fee/day (MMK)</th>
            <th>Total (MMK)</th>
        </thead>
        <tbody>
            @foreach ($employees as $employee)
                @php
                    $attendanceDays = 0;

                    $salary = collect($employee->salaries)
                        ->where('month', $month)
                        ->where('year', $year)
                        ->first();
                    $feePerDay = $salary ? $salary->amount / $workingDays : 0;
                @endphp
                @foreach ($periods as $period)
                    @php
                        $office_start_time = $period->format('Y-m-d') . ' ' . $company_setting->office_start_time;
                        $office_end_time = $period->format('Y-m-d') . ' ' . $company_setting->office_end_time;
                        $break_start_time = $period->format('Y-m-d') . ' ' . $company_setting->break_start_time;
                        $break_end_time = $period->format('Y-m-d') . ' ' . $company_setting->break_end_time;

                        $attendance = collect($attendances)
                            ->where('user_id', $employee->id)
                            ->where('date', $period->format('Y-m-d'))
                            ->first();

                        if ($attendance) {
                            /* checkin */
                            if (!is_null($attendance->checkin_time)) {
                                if ($attendance->checkin_time && $attendance->checkin_time > $break_start_time) {
                                    $attendanceDays += 0;
                                }

                                if ($attendance->checkin_time <= $office_start_time) {
                                    $attendanceDays += 0.5;
                                }

                                if ($attendance->checkin_time > $office_start_time && $attendance->checkin_time < $break_start_time) {
                                    $attendanceDays += 0.5;
                                }
                            } else {
                                $attendanceDays += 0;
                            }

                            /* checkout */
                            if (!is_null($attendance->checkout_time)) {
                                if ($attendance->checkout_time && $attendance->checkout_time < $break_end_time) {
                                    $attendanceDays += 0;
                                }

                                if ($attendance->checkout_time >= $office_end_time) {
                                    $attendanceDays += 0.5;
                                }

                                if ($attendance->checkout_time > $break_end_time && $attendance->checkout_time < $office_end_time) {
                                    $attendanceDays += 0.5;
                                }
                            } else {
                                $attendanceDays += 0;
                            }
                        }
                    @endphp
                @endforeach

                @php
                    $absentDays = $workingDays - $attendanceDays;
                    $total = $feePerDay * $attendanceDays;
                @endphp
                <tr>
                    <td>{{ $employee->employee_id }}</td>
                    <td>{{ implode(',', $employee->roles->pluck('name')->toArray()) }}</td>
                    <td>{{ $daysInMonth }}</td>
                    <td>{{ $workingDays }}</td>
                    <td>{{ $offDays }}</td>
                    <td>{{ $attendanceDays }}</td>
                    <td>{{ $absentDays }}</td>
                    <td>{{ number_format($feePerDay) }}</td>
                    <td>{{ number_format($total) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
