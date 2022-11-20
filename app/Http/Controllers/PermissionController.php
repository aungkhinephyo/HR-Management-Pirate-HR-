<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePermission;
use Illuminate\Http\Request;
use App\Http\Requests\UpdatePermission;
use Yajra\Datatables\Datatables;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{

    /* index */
    public function index()
    {
        $this->checkPermission('view permission');
        return view('app.permission.index');
    }

    /* datatable */
    public function ssd(Request $request)
    {
        $this->checkPermission('view permission');

        $permissions = Permission::query();
        return Datatables::of($permissions)
            ->addColumn('plus_icon', function ($each) {
                return null;
            })
            ->editColumn('created_at', function ($each) {
                return $each->created_at->toDateTimeString();
            })
            ->editColumn('updated_at', function ($each) {
                return $each->created_at->toDateTimeString();
            })
            ->addColumn('action', function ($each) {
                $edit_icon = '';
                $delete_icon = '';

                if (auth()->user()->can('edit permission')) {
                    $edit_icon = '<a href="' . route('permission.edit', $each->id) . '"><i class="far fa-edit"></i></a>';
                }
                if (auth()->user()->can('delete permission')) {
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
        $this->checkPermission('create permission');
        return view('app.permission.create');
    }


    /* store */
    public function store(StorePermission $request)
    {
        $this->checkPermission('create permission');

        Permission::create(['name' => $request->name]);
        return redirect()->route('permission.index')->with('create', 'Permission is successfully created.');
    }


    /* edit */
    public function edit($id)
    {
        $this->checkPermission('edit permission');

        $permission = Permission::findOrFail($id);
        return view('app.permission.edit', compact('permission'));
    }

    /* update */
    public function update(UpdatePermission $request, $id)
    {
        $this->checkPermission('edit permission');

        $permission = Permission::findOrFail($id);
        $permission->update(['name' => $request->name]);
        return redirect()->route('permission.index')->with('update', 'Permission is successfully updated.');
    }

    /* delete */
    public function destroy($id)
    {
        $this->checkPermission('delete permission');

        $permission = Permission::findOrFail($id);
        $permission->delete();
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
