<?php

namespace App\Http\Controllers\Admin;

use App\Models\Task;
use App\Models\User;
use App\Models\Project;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Services\TaskNotifier;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class AdminTaskController extends Controller {

    public function index(): View{

        return view('admin.tasks.index', [
            'tasks' => Task::all()->load(['project', 'user']),
        ]);
    }

    public function create(int $id): View{

        $project = Project::with('users')->findOrFail($id);

        return view('admin.tasks.form', [
            'project' => $project,
        ]);
    }

    public function store(int $id, Request $request): RedirectResponse{

        $project = Project::findOrFail($id); 

        $request->validate([
            'name' => 'required|string|max:255',
            'start' => 'required|date|before:end',
            'end' => 'required|date',
            'state' => 'required|between:0,3',
            'priority' => 'required|between:0,3',
            'user' => 'required|integer',
        ]);

        $task = Task::create([
            'project_id' => $id,
            'name' => $request->name,
            'description' => $request->description,
            'start' => $request->start,
            'end' => $request->end,
            'state' => $request->state,
            'priority' => $request->priority,
            'user_id' => $request->user,
        ]);
        
        $notifier = new TaskNotifier($project, $task);
        $notifier->notify('created_task', $task, $project);

        return redirect(route('admin.project.overview', $project->id))->with('status', 'Task created with success!');
    }

    public function edit(int $id): View{

        $task = Task::findOrFail($id);
        $project = Project::findOrFail($task->project_id);

        return view('admin.tasks.form', [
            'task' => $task,
            'project' => $project,
        ]);
    }

    public function update(int $id, Request $request): RedirectResponse{

        $task = Task::findOrFail($id);

        $project = Project::findOrFail($task->project_id); 

        $request->validate([
            'name' => 'required|string|max:255',
            'start' => 'required|date|before:end',
            'end' => 'required|date',
            'state' => 'required|between:0,3',
            'priority' => 'required|between:0,3',
            'user' => 'required|integer',
        ]);

        $task->update([
            'name' => $request->name,
            'description' => $request->description,
            'start' => $request->start,
            'end' => $request->end,
            'state' => $request->state,
            'priority' => $request->priority,
            'user_id' => $request->user,
        ]);
        
        $notifier = new TaskNotifier($project, $task);
        $notifier->notify('updated_task', $task, $project);
        
        return redirect(route('admin.project.overview', $task->project_id))->with('status', 'Task updated with success!');
    }

    public function destroy(int $id): View{

        $task = Task::findOrFail($id);

        $project = Project::findOrFail($task->project_id);
        
        $notifier = new TaskNotifier($project, $task);
        $notifier->notify('deleted_task', $task, $project);

        $task->delete();

        return view('admin.tasks.index');
    }
}