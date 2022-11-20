<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use App\Models\CompanySetting;
use App\Models\CheckinCheckout;
use Yajra\Datatables\Datatables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class MyAttendanceController extends Controller
{
    /* datatable */
    public function ssd(Request $request)
    {
        $attendances = CheckinCheckout::with('employee')->where('user_id', Auth::user()->id);

        if ($request->month) {
            $attendances = $attendances->whereMonth('date', $request->month);
        }
        if ($request->year) {
            $attendances = $attendances->whereYear('date', $request->year);
        }

        return Datatables::of($attendances)
            ->addColumn('employee_name', function ($each) {
                return $each->employee ? $each->employee->name : '-';
            })
            ->editColumn('checkin_time', function ($each) {
                return Carbon::parse($each->checkin_time)->format('H:i:s');
            })
            ->editColumn('checkout_time', function ($each) {
                return Carbon::parse($each->checkout_time)->format('H:i:s');
            })
            ->addColumn('plus_icon', function ($each) {
                return null;
            })
            ->make(true);
    }

    public function myOverviewTable(Request $request)
    {
        $month = $request->month;
        $year = $request->year;

        $startOfMonth = $year . '-' . $month . '-01';
        $endOfMonth = Carbon::parse($startOfMonth)->endOfMonth()->format('Y-m-d');

        $employees = User::where('id', Auth::user()->id)->get();
        $company_setting = CompanySetting::findOrFail(1);
        $periods = new CarbonPeriod($startOfMonth, $endOfMonth);
        $attendances = CheckinCheckout::whereYear('date', $year)->whereMonth('date', $month)->get();

        return view('app.components.attendance_overview', compact('employees', 'company_setting', 'periods', 'attendances'))->render();
    }
}
