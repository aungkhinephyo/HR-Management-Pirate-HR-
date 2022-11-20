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

class AttendanceController extends Controller
{

    /* index */
    public function index()
    {
        $this->checkPermission('view attendance');
        return view('app.attendance.index');
    }

    /* datatable */
    public function ssd(Request $request)
    {
        $this->checkPermission('view attendance');

        $attendances = CheckinCheckout::with('employee');
        return Datatables::of($attendances)
            ->filterColumn('employee_name', function ($query, $keyword) {
                $query->whereHas('employee', function ($q1) use ($keyword) {
                    $q1->where('name', 'like', "%$keyword%");
                });
            })
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
            ->addColumn('action', function ($each) {
                $edit_icon = '';
                $delete_icon = '';

                if (auth()->user()->can('edit attendance')) {
                    $edit_icon = '<a href="' . route('attendance.edit', $each->id) . '"><i class="far fa-edit"></i></a>';
                }
                if (auth()->user()->can('delete attendance')) {
                    $delete_icon = '<a href="javascript:void(0);" class="text-danger delete-btn" data-id="' . $each->id . '"><i class="far fa-trash-alt"></i></a>';
                }
                return '<div class="action-icons">' . $edit_icon . $delete_icon . '</div>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /* create */
    public function create()
    {
        $this->checkPermission('create attendance');

        $employees = User::orderBy('employee_id')->get();
        return view('app.attendance.create', compact('employees'));
    }


    /* store */
    public function store(StoreAttendance $request)
    {
        $this->checkPermission('create attendance');

        if (CheckinCheckout::where('user_id', $request->user_id)->where('date', $request->date)->exists()) {
            return back()->withErrors(['fail' => 'Already defined.'])->withInput();
        }

        CheckinCheckout::create([
            'user_id' => $request->user_id,
            'date' => $request->date,
            'checkin_time' => $request->date . ' ' . $request->checkin_time,
            'checkout_time' => $request->date . ' ' . $request->checkout_time,
        ]);
        return redirect()->route('attendance.index')->with('create', 'Attendance is successfully created.');
    }


    /* edit */
    public function edit($id)
    {
        $this->checkPermission('edit attendance');

        $attendance = CheckinCheckout::findOrFail($id);

        $employees = User::orderBy('employee_id')->get();
        return view('app.attendance.edit', compact('attendance', 'employees'));
    }

    /* update */
    public function update(UpdateAttendance $request, $id)
    {
        $this->checkPermission('edit attendance');

        $attendance = CheckinCheckout::findOrFail($id);


        if (CheckinCheckout::where('user_id', $request->user_id)
            ->where('date', $request->date)
            ->where('id', '!=', $attendance->id)
            ->exists()
        ) {
            return back()->withErrors(['fail' => 'Already defined.'])->withInput();
        }

        $attendance->update([
            'user_id' => $request->user_id,
            'date' => $request->date,
            'checkin_time' => $request->date . ' ' . $request->checkin_time,
            'checkout_time' => $request->date . ' ' . $request->checkout_time,
        ]);

        return redirect()->route('attendance.index')->with('update', 'Attendance is successfully updated.');
    }

    /* delete */
    public function destroy($id)
    {
        $this->checkPermission('delete attendance');

        $attendance = CheckinCheckout::findOrFail($id);
        $attendance->delete();
        return response()->json(['status' => 'success'], 200);
    }


    public function overview(Request $request)
    {
        $this->checkPermission('view attendance');
        return view('app.attendance.overview');
    }

    public function overviewTable(Request $request)
    {
        $this->checkPermission('view attendance');


        $month = $request->month;
        $year = $request->year;

        $startOfMonth = $year . '-' . $month . '-01';
        $endOfMonth = Carbon::parse($startOfMonth)->endOfMonth()->format('Y-m-d');

        $employees = User::orderBy('employee_id')->where('name', 'like', '%' . $request->employee_name . '%')->get();
        $company_setting = CompanySetting::findOrFail(1);
        $periods = new CarbonPeriod($startOfMonth, $endOfMonth);
        $attendances = CheckinCheckout::whereYear('date', $year)->whereMonth('date', $month)->get();
        // return $attendances;
        return view('app.components.attendance_overview', compact('employees', 'company_setting', 'periods', 'attendances'))->render();
    }

    /* check user has permission */
    private function checkPermission($permission)
    {
        if (!auth()->user()->can($permission)) {
            return abort(403, 'Unauthorized action.');
        }
    }
}
