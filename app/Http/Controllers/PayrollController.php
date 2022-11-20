<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use App\Models\CompanySetting;
use App\Models\CheckinCheckout;
use Yajra\Datatables\Datatables;
use App\Http\Requests\StoreAttendance;
use App\Http\Requests\UpdateAttendance;

class PayrollController extends Controller
{
    public function payroll(Request $request)
    {
        $this->checkPermission('view payroll');
        return view('app.payroll');
    }

    public function payrollTable(Request $request)
    {
        $this->checkPermission('view payroll');

        $month = $request->month;
        $year = $request->year;
        $startOfMonth = $year . '-' . $month . '-01';
        $endOfMonth = Carbon::parse($startOfMonth)->endOfMonth()->format('Y-m-d');

        $daysInMonth = Carbon::parse($startOfMonth)->daysInMonth;

        ## https://stackoverflow.com/questions/41462401/calculate-working-days-between-to-dates

        $workingDays = Carbon::parse($startOfMonth)->diffInDaysFiltered(function (Carbon $date) {
            return $date->isWeekday();
        }, Carbon::parse($endOfMonth)->addDays(1));

        $offDays = $daysInMonth - $workingDays;

        $employees = User::orderBy('employee_id')->where('name', 'like', '%' . $request->employee_name . '%')->get();
        $company_setting = CompanySetting::findOrFail(1);
        $periods = new CarbonPeriod($startOfMonth, $endOfMonth);
        $attendances = CheckinCheckout::whereYear('date', $year)->whereMonth('date', $month)->get();

        return view('app.components.payroll_table', compact('employees', 'company_setting', 'periods', 'attendances', 'daysInMonth', 'workingDays', 'offDays', 'month', 'year'))->render();
    }

    /* check user has permission */
    private function checkPermission($permission)
    {
        if (!auth()->user()->can($permission)) {
            return abort(403, 'Unauthorized action.');
        }
    }
}
