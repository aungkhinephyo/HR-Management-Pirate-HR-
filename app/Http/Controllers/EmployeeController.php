<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Department;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Spatie\Permission\Models\Role;
use App\Http\Requests\StoreEmployee;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UpdateEmployee;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{

    /* index */
    public function index()
    {
        $this->checkPermission('view employee');
        return view('app.employee.index');
    }

    /* show */
    public function show($id)
    {
        $this->checkPermission('view employee');

        $employee = User::findOrFail($id);
        return view('app.employee.show', compact('employee'));
    }

    /* datatable */
    public function ssd(Request $request)
    {
        $this->checkPermission('view employee');

        $employees = User::with('department');
        return Datatables::of($employees)
            ->filterColumn('department_name', function ($query, $keyword) {
                $query->whereHas('department', function ($q1) use ($keyword) {
                    $q1->where('name', 'like', "%$keyword%");
                });
            })
            ->editColumn('image', function ($each) {
                return '<img src="' . $each->img_path() . '" class="profile_thumbnail"/><p class="mt-1 mb-0">' . $each->name . '</p>';
            })
            ->addColumn('department_name', function ($each) {
                return $each->department ? $each->department->name : '-';
            })
            ->addColumn('role', function ($each) {
                $list = '';
                foreach ($each->roles as $role) {
                    $list .= '<span class="badge rounded-pill bg-theme m-1">' . $role->name . '</span>';
                }
                return $list;
            })
            ->editColumn('is_present', function ($each) {
                if ($each->is_present == 1) {
                    return '<span class="badge rounded-pill bg-success">Present</span>';
                } else {
                    return '<span class="badge rounded-pill text-bg-danger">Left</span>';
                }
            })
            ->editColumn('updated_at', function ($each) {
                return $each->updated_at->format('Y-m-d H:i:s');
            })
            ->addColumn('plus_icon', function ($each) {
                return null;
            })
            ->addColumn('action', function ($each) {
                $edit_icon = '';
                $info_icon = '';
                $delete_icon = '';

                if (auth()->user()->can('edit employee')) {
                    $edit_icon = '<a href="' . route('employee.edit', $each->id) . '"><i class="far fa-edit"></i></a>';
                }
                if (auth()->user()->can('view employee')) {
                    $info_icon = '<a href="' . route('employee.show', $each->id) . '" class="text-primary"><i class="fas fa-info-circle"></i></a>';
                }
                if (auth()->user()->can('delete employee')) {
                    $delete_icon = '<a href="javascript:void(0);" class="text-danger delete-btn" data-id="' . $each->id . '"><i class="far fa-trash-alt"></i></a>';
                }
                return '<div class="action-icons">' . $edit_icon . $info_icon . $delete_icon . '</div>';
            })
            ->rawColumns(['image', 'role', 'is_present', 'action'])
            ->make(true);
    }

    /* create */
    public function create()
    {
        $this->checkPermission('create employee');

        $departments = Department::orderBy('name')->get();
        $roles = Role::all();
        return view('app.employee.create', compact('departments', 'roles'));
    }


    /* store */
    public function store(StoreEmployee $request)
    {
        $this->checkPermission('create employee');

        $img_name = null;
        if ($request->hasFile('image')) {
            $img_file = $request->file('image');
            $img_name = uniqid() . '_' . $img_file->getClientOriginalName();
            Storage::disk('public')->put('employee/' . $img_name, file_get_contents($img_file));
        }

        $data = [
            'employee_id' => $request->employee_id,
            'department_id' => $request->department_id,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'gender' => $request->gender,
            'nrc_number' => $request->nrc_number,
            'address' => $request->address,
            'birthday' => $request->birthday,
            'date_of_join' => $request->date_of_join,
            'is_present' => $request->is_present,
            'image' => $img_name,
            'pin_code' => $request->pin_code,
            'password' => Hash::make($request->password),
        ];

        $employee = User::create($data);

        $employee->syncRoles($request->roles);

        return redirect()->route('employee.index')->with('create', 'Employee is successfully created.');
    }


    /* edit */
    public function edit($id)
    {
        $this->checkPermission('edit employee');

        $employee = User::findOrFail($id);
        $departments = Department::get();
        $old_roles = $employee->roles->pluck('name')->toArray();
        $roles = Role::all();
        return view('app.employee.edit', compact('employee', 'departments', 'old_roles', 'roles'));
    }

    /* update */
    public function update(UpdateEmployee $request, $id)
    {
        $this->checkPermission('edit employee');

        $employee = User::findOrFail($id);

        // dd($employee, $request->pin_code, Hash::make($request->pin_code));

        $img_name = $employee->image;
        if ($request->hasFile('image')) {
            Storage::disk('public')->delete('employee/' . $employee->image);

            $img_file = $request->file('image');
            $img_name = uniqid() . '_' . $img_file->getClientOriginalName();
            Storage::disk('public')->put('employee/' . $img_name, file_get_contents($img_file));
        }

        $data = [
            'employee_id' => $request->employee_id,
            'department_id' => $request->department_id,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'gender' => $request->gender,
            'nrc_number' => $request->nrc_number,
            'address' => $request->address,
            'birthday' => $request->birthday,
            'date_of_join' => $request->date_of_join,
            'is_present' => $request->is_present,
            'image' => $img_name,
            'pin_code' => $request->pin_code,
            'password' => $request->password ? Hash::make($request->password) : $employee->password,
        ];
        $employee->update($data);

        $employee->syncRoles($request->roles);

        return redirect()->route('employee.index')->with('update', 'Employee is successfully updated.');
    }

    /* delete */
    public function destroy($id)
    {
        $this->checkPermission('delete employee');

        $employee = User::findOrFail($id);
        Storage::disk('public')->delete('employee/' . $employee->image);
        
        $employee->delete();
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
