<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Project;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class MyProjectController extends Controller
{
    /* index */
    public function index()
    {
        return view('app.my_project');
    }

    /* show */
    public function show($id)
    {
        $project = Project::with('leaders', 'members', 'tasks')
            ->where('id', $id)
            ->where(function ($query) {
                $query
                    ->whereHas('leaders', function ($q1) {
                        $q1->where('user_id', Auth::user()->id);
                    })
                    ->orWhereHas('members', function ($q1) {
                        $q1->where('user_id', Auth::user()->id);
                    });
            })
            ->firstOrFail();
        return view('app.my_project_show', compact('project'));
    }

    /* datatable */
    public function ssd(Request $request)
    {

        $projects = Project::with('leaders', 'members')
            ->whereHas('leaders', function ($query) {
                $query->where('user_id', Auth::user()->id);
            })
            ->orWhereHas('members', function ($query) {
                $query->where('user_id', Auth::user()->id);
            });

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

                if (auth()->user()->can('view my project')) {
                    $info_icon = '<a href="' . route('my_project.show', $each->id) . '" class="text-primary"><i class="fas fa-info-circle"></i></a>';
                }
                return '<div class="action-icons">' . $info_icon . '</div>';
            })
            ->rawColumns(['description', 'leaders', 'members', 'priority', 'status', 'action'])
            ->make(true);
    }
}
