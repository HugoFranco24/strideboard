<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Task;
use App\Models\Project;
use Illuminate\View\View;
use App\Models\ProjectUser;
use Illuminate\Http\Request;
use App\Services\TaskNotifier;
use OwenIt\Auditing\Models\Audit;
use Illuminate\Http\RedirectResponse;

class TasksController extends Controller 
{   
    public function tasks(Request $request): View
    {
        $query = Task::leftJoin('projects', 'tasks.project_id', '=', 'projects.id')
            ->where('user_id', auth()->id())
            ->where('projects.archived', false)
            ->select(
                'tasks.*',
                'projects.name as project_name'
            );

        if ($request->filled('filter') && $request->filter !== 'no_filter') {
            if ($request->filter === 'late') {
                $query->where('tasks.end', '<', now())
                    ->where('tasks.state', '!=', 3);     

            } elseif ($request->filter === 'urgent') {
                $query->where('tasks.priority', '=', 3);

            }elseif ($request->filter === 'done') {
                $query->where('tasks.state', '=', 3);
            }
        }

        if ($request->filled('project') && $request->project !== 'all') {
            $query->where('tasks.project_id', $request->project);
        }

        $my_tasks = $query->get();

        return view("pages.tasks.tasks", [
            'my_tasks' => $my_tasks,
            'projects' => auth()->user()->projects,
        ]);
    }

    public function TaskCreate(int $project_id): View
    {
        $project = $this->permissionsCheck($project_id, true, [1, 2]);

        //permitions check
        $project->users()
            ->where('users.id', auth()->id())
            ->wherePivot('active', true)
            ->wherePivot('user_type', '>=', 1)
            ->firstOrFail();
        //permitions check end

        return view('pages.tasks.tasks-create', [
            'project' => $project,
        ]);
    }

    public function TaskAdd(Request $request, int $project_id): RedirectResponse
    {
        $project = $this->permissionsCheck($project_id, true, [1, 2]);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'start' => 'required|date|before:end',
            'end' => 'required|date',
            'state' => 'between:0,3',
            'priority' => 'between:0,3',
            'user_id' => 'required|integer',
        ]);

        $task = Task::create([
            'project_id' => $project_id,
            'name' => $request->name,
            'description' => $request->description,
            'start' => $request->start,
            'end' => $request->end,
            'state' => $request->state != null ? $request->state : 0,
            'priority' => $request->priority != null ? $request->priority : 1,
            'user_id' => $request->user_id,
        ]);

        //notify users with service
        $notifier = new TaskNotifier($project, $task);
        $notifier->notify('created_task', $task, $project);

        return redirect(route('projects.overview', $project_id));
    }

    public function TaskUpdate(Request $request, int $project_id, int $task_id): View{

        $task = Task::findOrFail($task_id);

        $project = $this->permissionsCheck($project_id, true, [0, 1, 2]);

        $user = $project->users()
            ->where('users.id', auth()->id())
            ->wherePivot('active', true)
            ->firstOrFail();

        if ($user->pivot->user_type == 0 && $task->user_id != auth()->id()) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'start' => 'required|date|before:end',
            'end' => 'required|date',
            'state' => 'between:0,3',
            'priority' => 'between:0,3',
            'user_id' => 'required|integer',
        ]);

        if($task->end != $request->end){
            $task->update([
                'late_notified' => false,
            ]);
        }

        $task->update([
            'name' => $request->name,
            'description' => $request->description,
            'start' => $request->start,
            'end' => $request->end,
            'state' => $request->state != null ? $request->state : 0,
            'priority' => $request->priority != null ? $request->priority : 1,
            'user_id' => $request->user_id,
        ]);


        //notify users with service
        $notifier = new TaskNotifier($project, $task);
        $notifier->notify('updated_task', $task, $project);

        $audits = Audit::where('auditable_id', $task->id)
            ->orderBy('created_at')
            ->get();

        $project_user = ProjectUser::where('project_id', $project->id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        return view('pages.tasks.overview', [
            'task' => $task,
            'project' => $project,
            'audits' => $audits,
            'users' => User::all(),
            'project_user' => $project_user,
            'url_previous' => $request->url_previous,
            'status' => 'Task Updated!',
        ]);
    }

    public function TaskDelete(int $task_id): RedirectResponse{

        $task = Task::findOrFail($task_id);

        $project = $this->permissionsCheck($task->project_id, true, [0, 1, 2]);

        $user = $project->users()
            ->where('users.id', auth()->id())
            ->wherePivot('active', true)
            ->firstOrFail();

        if($user->pivot->user_type == 0 && $task->user_id != auth()->id()){
            abort(403);
        }

        //notify users with service
        $notifier = new TaskNotifier($project, $task);
        $notifier->notify( 'deleted_task', $task, $project);

        $task->delete();

        return redirect(route('projects.overview', $task->project_id . '#allTasks'));
    }

    public function TaskOverview(int $task_id): RedirectResponse|View{

        $task = Task::find($task_id);

        if(!$task){
            return redirect(route('dashboard.projects'));
        }

        $project = $this->permissionsCheck($task->project_id, true, [0, 1, 2]);

        $audits = Audit::where('auditable_id', $task->id)
                ->orderBy('created_at')
                ->get();

        $project_user = ProjectUser::where('project_id', $project->id)
                                    ->where('user_id', auth()->id())
                                    ->firstOrFail();

        $url_previous = null;

        if(url()->previous() != '/dashboard/tasks/overview/' . $task->id){
            $url_previous = url()->previous();
        }
    
        return view('pages.tasks.overview', [
            'task' => $task,
            'project' => $project,
            'audits' => $audits,
            'users' => User::all(),
            'project_user' => $project_user,
            'url_previous' => $url_previous,
            'status' => null,
        ]);
    }
}
