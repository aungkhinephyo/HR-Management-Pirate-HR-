<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use App\Models\TaskMember;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function taskData(Request $request)
    {
        $project = Project::with('tasks')->where('id', $request->project_id)->firstOrFail();
        return view('app.components.task', compact('project'))->render();
    }

    public function taskDraggable(Request $request)
    {
        $project = Project::with('tasks')->where('id', $request->project_id)->firstOrFail();

        if ($request->pending_taskboard) {
            $pending_taskboard = explode(',', $request->pending_taskboard);
            foreach ($pending_taskboard as $key => $task_id) {
                $task = collect($project->tasks)->where('id', $task_id)->first();
                $task->update([
                    'serial_number' => $key,
                    'status' => 'pending',
                ]);
            }
        }
        if ($request->in_progress_taskboard) {
            $in_progress_taskboard = explode(',', $request->in_progress_taskboard);
            foreach ($in_progress_taskboard as $key => $task_id) {
                $task = collect($project->tasks)->where('id', $task_id)->first();
                $task->update([
                    'serial_number' => $key,
                    'status' => 'in_progress',
                ]);
            }
        }
        if ($request->complete_taskboard) {
            $complete_taskboard = explode(',', $request->complete_taskboard);
            foreach ($complete_taskboard as $key => $task_id) {
                $task = collect($project->tasks)->where('id', $task_id)->first();
                $task->update([
                    'serial_number' => $key,
                    'status' => 'complete',
                ]);
            }
        }

        return 'success';
    }

    public function store(Request $request)
    {
        $task = Task::create([
            'project_id' => $request->project_id,
            'name' => $request->name,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'deadline' => $request->deadline,
            'priority' => $request->priority,
            'status' => $request->status,
        ]);

        $task->members()->sync($request->members);

        return 'success';
    }

    public function update($id, Request $request)
    {
        $task = Task::findOrFail($id);
        $task->update([
            'name' => $request->name,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'deadline' => $request->deadline,
            'priority' => $request->priority,
        ]);

        $task->members()->sync($request->members);

        return 'success';
    }

    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        $task->members()->detach();
        $task->delete();
    }
}
