<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Http\Requests\StoreDepartment;
use App\Http\Requests\UpdateDepartment;

class DepartmentController extends Controller
{

    /* index */
    public function index()
    {
        $this->checkPermission('view department');
        return view('app.department.index');
    }

    /* datatable */
    public function ssd(Request $request)
    {
        $this->checkPermission('view department');

        $departments = Department::query();
        return Datatables::of($departments)
            ->addColumn('plus_icon', function ($each) {
                return null;
            })
            ->addColumn('action', function ($each) {
                $edit_icon = '';
                $delete_icon = '';

                if (auth()->user()->can('edit department')) {
                    $edit_icon = '<a href="' . route('department.edit', $each->id) . '"><i class="far fa-edit"></i></a>';
                }
                if (auth()->user()->can('delete department')) {
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
        $this->checkPermission('create department');
        return view('app.department.create');
    }


    /* store */
    public function store(StoreDepartment $request)
    {
        $this->checkPermission('create department');

        Department::create(['name' => $request->name]);
        return redirect()->route('department.index')->with('create', 'Department is successfully created.');
    }


    /* edit */
    public function edit($id)
    {
        $this->checkPermission('edit department');

        $department = Department::findOrFail($id);
        return view('app.department.edit', compact('department'));
    }

    /* update */
    public function update(UpdateDepartment $request, $id)
    {
        $this->checkPermission('edit department');

        $department = Department::findOrFail($id);
        $department->update(['name' => $request->name]);
        return redirect()->route('department.index')->with('update', 'Department is successfully updated.');
    }

    /* delete */
    public function destroy($id)
    {
        $this->checkPermission('delete department');

        $department = Department::findOrFail($id);
        $department->delete();
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
