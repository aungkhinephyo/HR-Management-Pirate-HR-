<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreRole;
use Yajra\Datatables\Datatables;
use App\Http\Requests\UpdateRole;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{

    /* index */
    public function index()
    {
        $this->checkPermission('view role');
        return view('app.role.index');
    }

    /* datatable */
    public function ssd(Request $request)
    {
        $this->checkPermission('view role');

        $roles = Role::query();
        return Datatables::of($roles)
            ->addColumn('plus_icon', function ($each) {
                return null;
            })
            ->addColumn('permissions', function ($each) {
                $list = '';
                foreach ($each->permissions as $permission) {
                    $list .= '<span class="badge rounded-pill bg-theme m-1">' . $permission->name . '</span>';
                }
                return '<div>' . $list . '</div>';
            })
            ->addColumn('action', function ($each) {
                $edit_icon = '';
                $delete_icon = '';

                if (auth()->user()->can('edit role')) {
                    $edit_icon = '<a href="' . route('role.edit', $each->id) . '"><i class="far fa-edit"></i></a>';
                }
                if (auth()->user()->can('delete role')) {
                    $delete_icon = '<a href="javascript:void(0);" class="text-danger delete-btn" data-id="' . $each->id . '"><i class="far fa-trash-alt"></i></a>';
                }
                return '<div class="action-icons">' . $edit_icon . $delete_icon . '</div>';
            })
            ->rawColumns(['permissions', 'action'])
            ->make(true);
    }

    /* create */
    public function create()
    {
        $this->checkPermission('create role');

        $permissions = Permission::all();
        return view('app.role.create', compact('permissions'));
    }


    /* store */
    public function store(StoreRole $request)
    {
        $this->checkPermission('create role');

        $role = Role::create(['name' => $request->name]);

        $role->givePermissionTo($request->permissions);

        return redirect()->route('role.index')->with('create', 'Role is successfully created.');
    }


    /* edit */
    public function edit($id)
    {
        $this->checkPermission('edit role');

        $role = Role::findOrFail($id);
        $old_permissions = $role->permissions->pluck('name')->toArray();
        $permissions = Permission::all();
        return view('app.role.edit', compact('role', 'old_permissions', 'permissions'));
    }

    /* update */
    public function update(UpdateRole $request, $id)
    {
        $this->checkPermission('edit role');

        $role = Role::findOrFail($id);
        $role->update(['name' => $request->name]);

        $role->revokePermissionTo($role->permissions);
        $role->givePermissionTo($request->permissions);

        return redirect()->route('role.index')->with('update', 'Role is successfully updated.');
    }

    /* delete */
    public function destroy($id)
    {
        $this->checkPermission('delete role');

        $role = Role::findOrFail($id);
        $role->delete();
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
