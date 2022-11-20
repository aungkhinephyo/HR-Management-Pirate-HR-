<div class="table-responsive">
    <table class="table table-bordered table-hover">
        <thead>
            <th>Employee</th>
            @foreach ($periods as $period)
                <th @if ($period->format('D') == 'Sat' || $period->format('D') == 'Sun') class="bg-secondary text-light" @endif>
                    {{ $period->format('d') }} <br> {{ $period->format('D') }}
                </th>
            @endforeach
        </thead>
        <tbody>
            @foreach ($employees as $employee)
                <tr>
                    <td>{{ $employee->employee_id }}</td>
                    @foreach ($periods as $period)
                        @php
                            $office_start_time = $period->format('Y-m-d') . ' ' . $company_setting->office_start_time;
                            $office_end_time = $period->format('Y-m-d') . ' ' . $company_setting->office_end_time;
                            $break_start_time = $period->format('Y-m-d') . ' ' . $company_setting->break_start_time;
                            $break_end_time = $period->format('Y-m-d') . ' ' . $company_setting->break_end_time;

                            $checkin_icon = '';
                            $checkout_icon = '';

                            $attendance = collect($attendances)
                                ->where('user_id', $employee->id)
                                ->where('date', $period->format('Y-m-d'))
                                ->first();

                            if ($attendance) {
                                /* checkin */
                                if (!is_null($attendance->checkin_time)) {
                                    if ($attendance->checkin_time && $attendance->checkin_time > $break_start_time) {
                                        $checkin_icon = '<i class="fas fa-times-circle text-danger"></i>';
                                    }

                                    if ($attendance->checkin_time <= $office_start_time) {
                                        $checkin_icon = '<i class="fas fa-check-circle text-success"></i>';
                                    }

                                    if ($attendance->checkin_time > $office_start_time && $attendance->checkin_time < $break_start_time) {
                                        $checkin_icon = '<i class="fas fa-check-circle text-warning"></i>';
                                    }
                                } else {
                                    $checkin_icon = '<i class="fas fa-times-circle text-danger"></i>';
                                }

                                /* checkout */
                                if (!is_null($attendance->checkout_time)) {
                                    if ($attendance->checkout_time && $attendance->checkout_time < $break_end_time) {
                                        $checkout_icon = '<i class="fas fa-times-circle text-danger"></i>';
                                    }

                                    if ($attendance->checkout_time >= $office_end_time) {
                                        $checkout_icon = '<i class="fas fa-check-circle text-success"></i>';
                                    }

                                    if ($attendance->checkout_time > $break_end_time && $attendance->checkout_time < $office_end_time) {
                                        $checkout_icon = '<i class="fas fa-check-circle text-warning"></i>';
                                    }
                                } else {
                                    $checkout_icon = '<i class="fas fa-times-circle text-danger"></i>';
                                }
                            } else {
                                $checkin_icon = '<i class="fas fa-minus-circle text-danger"></i>';
                                $checkout_icon = '<i class="fas fa-minus-circle text-danger"></i>';
                            }
                        @endphp
                        <td @if ($period->format('D') == 'Sat' || $period->format('D') == 'Sun') class="bg-secondary" @endif>
                            <div @if ($period->format('D') == 'Sat' || $period->format('D') == 'Sun') class="d-none" @endif>{!! $checkin_icon !!}</div>
                            <div @if ($period->format('D') == 'Sat' || $period->format('D') == 'Sun') class="d-none" @endif>{!! $checkout_icon !!}</div>
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
