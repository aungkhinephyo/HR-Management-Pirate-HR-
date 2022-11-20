<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Project;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Http\Requests\StoreProject;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProject;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    /* index */
    public function index()
    {
        $this->checkPermission('view project');
        return view('app.project.index');
    }

    /* show */
    public function show($id)
    {
        $this->checkPermission('view project');
        $project = Project::findOrFail($id);
        return view('app.project.show', compact('project'));
    }

    /* datatable */
    public function ssd(Request $request)
    {
        $this->checkPermission('view project');

        $projects = Project::with('leaders', 'members');

        return Datatables::of($projects)
            ->addColumn('plus_icon', function ($each) {
                return null;
            })
            // ->editColumn('description', function ($each) {
            //     return '<div style="width: 200px">' . Str::limit($each->description, 100, '...') . '</div>';
            // })
            ->addColumn('leaders', function ($each) {
                $list = '<div style="width: 100px">';
                foreach ($each->leaders as $leader) {
                    $list .= '<img src="' . $leader->img_path() . '" class="profile_thumbnail2"/>';
                }
                return $list . '</div>';
            })
            ->addColumn('members', function ($each) {
                $list = '<div style="width: 160px">';
                foreach ($each->members as $member) {
                    $list .= '<img src="' . $member->img_path() . '" class="profile_thumbnail2"/>';
                }
                return $list . '</div>';
            })
            ->editColumn('priority', function ($each) {
                if ($each->priority === 'high') {
                    return '<span class="badge rounded-pill bg-danger">High</span>';
                } else if ($each->priority === 'middle') {
                    return '<span class="badge rounded-pill bg-info">Middle</span>';
                } else {
                    return '<span class="badge rounded-pill bg-dark">Low</span>';
                }
            })
            ->editColumn('status', function ($each) {
                if ($each->status === 'pending') {
                    return '<span class="badge rounded-pill bg-warning">Pending</span>';
                } else if ($each->status === 'in_progress') {
                    return '<span class="badge rounded-pill bg-info">In Progress</span>';
                } else {
                    return '<span class="badge rounded-pill bg-success">Complete</span>';
                }
            })
            ->addColumn('action', function ($each) {
                $info_icon = '';
                $edit_icon = '';
                $delete_icon = '';

                if (auth()->user()->can('edit project')) {
                    $edit_icon = '<a href="' . route('project.edit', $each->id) . '"><i class="far fa-edit"></i></a>';
                }
                if (auth()->user()->can('view project')) {
                    $info_icon = '<a href="' . route('project.show', $each->id) . '" class="text-primary"><i class="fas fa-info-circle"></i></a>';
                }
                if (auth()->user()->can('delete project')) {
                    $delete_icon = '<a href="javascript:void(0);" class="text-danger delete-btn" data-id="' . $each->id . '"><i class="far fa-trash-alt"></i></a>';
                }
                return '<div class="action-icons">' . $edit_icon . $info_icon . $delete_icon . '</div>';
            })
            ->rawColumns(['description', 'leaders', 'members', 'priority', 'status', 'action'])
            ->make(true);
    }

    /* create */
    public function create()
    {
        $this->checkPermission('create project');
        $employees = User::orderBy('employee_id')->get();
        return view('app.project.create', compact('employees'));
    }


    /* store */
    public function store(StoreProject $request)
    {
        $this->checkPermission('create project');

        $img_names = null;
        if ($request->hasFile('images')) {
            $img_names = [];
            $img_files = $request->file('images');
            foreach ($img_files as $img_file) {
                $img_name = uniqid() . '_' . $img_file->getClientOriginalName();
                Storage::disk('public')->put('project/images/' . $img_name, file_get_contents($img_file));
                $img_names[] = $img_name;
            }
        }

        $pdf_names = null;
        if ($request->hasFile('files')) {
            $pdf_names = [];
            $pdf_files = $request->file('files');
            foreach ($pdf_files as $pdf_file) {
                $pdf_name = uniqid() . '_' . $pdf_file->getClientOriginalName();
                Storage::disk('public')->put('project/files/' . $pdf_name, file_get_contents($pdf_file));
                $pdf_names[] = $pdf_name;
            }
        }
        $data = [
            'name' => $request->name,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'deadline' => $request->deadline,
            'priority' => $request->priority,
            'status' => $request->status,
            'images' => $img_names,
            'files' => $pdf_names,
        ];
        $project = Project::create($data);

        $project->leaders()->sync($request->leaders);
        $project->members()->sync($request->members);

        return redirect()->route('project.index')->with('create', 'Project is successfully created.');
    }


    /* edit */
    public function edit($id)
    {
        $this->checkPermission('edit project');

        $project = Project::findOrFail($id);
        $employees = User::orderBy('employee_id')->get();

        return view('app.project.edit', compact('project', 'employees'));
    }

    /* update */
    public function update(UpdateProject $request, $id)
    {
        $this->checkPermission('edit project');

        $project = Project::findOrFail($id);

        $img_names = $project->images;
        if ($request->hasFile('images')) {
            foreach ($project->images as $image) {
                Storage::disk('public')->delete('project/images/' . $image);
            }

            $img_names = [];
            $img_files = $request->file('images');
            foreach ($img_files as $img_file) {
                $img_name = uniqid() . '_' . $img_file->getClientOriginalName();
                Storage::disk('public')->put('project/images/' . $img_name, file_get_contents($img_file));
                $img_names[] = $img_name;
            }
        }

        $pdf_names = $project->files;
        if ($request->hasFile('files')) {
            // foreach ($project->files as $file) {
            //     Storage::disk('public')->delete('project/files/' . $file);
            // }
            // $pdf_names = [];

            $pdf_files = $request->file('files');
            foreach ($pdf_files as $pdf_file) {
                $pdf_name = uniqid() . '_' . $pdf_file->getClientOriginalName();
                Storage::disk('public')->put('project/files/' . $pdf_name, file_get_contents($pdf_file));
                $pdf_names[] = $pdf_name;
            }
        }
        $data = [
            'name' => $request->name,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'deadline' => $request->deadline,
            'priority' => $request->priority,
            'status' => $request->status,
            'images' => $img_names,
            'files' => $pdf_names,
        ];
        $project->update($data);

        $project->leaders()->sync($request->leaders);
        $project->members()->sync($request->members);

        return redirect()->route('project.index')->with('update', 'Project is successfully updated.');
    }

    /* delete */
    public function destroy($id)

    {
        $this->checkPermission('delete project');

        $project = Project::with('leaders', 'members', 'tasks')->findOrFail($id);

        $project->leaders()->detach();
        $project->members()->detach();

        foreach (($project->tasks ?? []) as $task) {
            $task->members()->detach();
            $task->delete();
        }

        foreach (($project->images ?? []) as $image) {
            Storage::disk('public')->delete('project/images/' . $image);
        }

        foreach (($project->files ?? []) as $file) {
            Storage::disk('public')->delete('project/files/' . $file);
        }

        $project->delete();

        return 'success';
    }

    /* check user has permission */
    private function checkPermission($permission)
    {
        if (!auth()->user()->can($permission)) {
            return abort(403, 'Unauthorized action.');
        }
    }
}
