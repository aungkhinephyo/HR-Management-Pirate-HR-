<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Salary;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Http\Requests\StoreSalary;
use App\Http\Requests\UpdateSalary;
use App\Http\Controllers\Controller;

class SalaryController extends Controller
{
    /* index */
    public function index()
    {
        $this->checkPermission('view salary');
        return view('app.salary.index');
    }

    /* datatable */
    public function ssd(Request $request)
    {
        $this->checkPermission('view salary');

        $salaries = Salary::with('employee');
        return Datatables::of($salaries)
            ->filterColumn('employee_name', function ($query, $keyword) {
                $query->whereHas('employee', function ($q1) use ($keyword) {
                    $q1->where('name', 'like', "%$keyword%");
                });
            })
            ->addColumn('plus_icon', function ($each) {
                return null;
            })
            ->addColumn('employee_name', function ($each) {
                return $each->employee ? $each->employee->name : '-';
            })
            ->editColumn('month', function ($each) {
                return Carbon::parse('2022-' . $each->month . '-01')->format('M');
            })
            ->editColumn('amount', function ($each) {
                return number_format($each->amount);
            })
            ->addColumn('action', function ($each) {
                $edit_icon = '';
                $delete_icon = '';

                if (auth()->user()->can('edit salary')) {
                    $edit_icon = '<a href="' . route('salary.edit', $each->id) . '"><i class="far fa-edit"></i></a>';
                }
                if (auth()->user()->can('delete salary')) {
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
        $this->checkPermission('create salary');

        $employees = User::orderBy('employee_id')->get();
        return view('app.salary.create', compact('employees'));
    }


    /* store */
    public function store(StoreSalary $request)
    {
        $this->checkPermission('create salary');

        Salary::create([
            'user_id' => $request->user_id,
            'month' => $request->month,
            'year' => $request->year,
            'amount' => $request->amount,
        ]);

        return redirect()->route('salary.index')->with('create', 'Salary is successfully created.');
    }


    /* edit */
    public function edit($id)
    {
        $this->checkPermission('edit salary');

        $employees = User::orderBy('employee_id')->get();
        $salary = Salary::findOrFail($id);
        return view('app.salary.edit', compact('employees', 'salary'));
    }

    /* update */
    public function update(UpdateSalary $request, $id)
    {
        $this->checkPermission('edit salary');

        $salary = Salary::findOrFail($id);
        $salary->update([
            'user_id' => $request->user_id,
            'month' => $request->month,
            'year' => $request->year,
            'amount' => $request->amount,
        ]);
        return redirect()->route('salary.index')->with('update', 'Salary is successfully updated.');
    }

    /* delete */
    public function destroy($id)
    {
        $this->checkPermission('delete salary');

        $salary = Salary::findOrFail($id);
        $salary->delete();
        return response()->json(['status' => 'success'], 200);
    }

    /* check user has permission */
    private function checkPermission($permission)
    {
        if (!auth()->user()->can($permission)) {
            return abort(403, 'Unauthorized action.');
        }
    }
}
