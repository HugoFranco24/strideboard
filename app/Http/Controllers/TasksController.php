<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Project;
use App\Models\Task;
use App\Models\ProjectUser;

class TasksController extends Controller {

    public function TasksCreate($project_id)
    {
        $project = Project::findorFail($project_id)
        ->load('users','tasks');

        //permitions check
        $user = $project->users->where('id', auth()->id())->first();

        if (!$project->users->contains('id', auth()->id())) {
            abort(code: 403);
        }  
        if($user->pivot->user_type < 1){
            abort(403);
        }
        //permitions check end

        return view('pages.tasks.tasks-create', [
            'user' => auth()->user(),
            'project' => $project,
        ]);
    }

    public function TasksCreateAdd(Request $request, $project_id)
    {
        $project = Project::findorFail($project_id)
        ->load('users','tasks');

        //permitions check
        $user = $project->users->where('id', auth()->id())->first();

        if (!$project->users->contains('id', auth()->id())) {
            abort(code: 403);
        }  
        if($user->pivot->user_type < 1){
            abort(403);
        }
        //permitions check end
        
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'string',
            'start' => 'required|date|before:end',
            'end' => 'required|date',
            'state' => 'between:0,3',
            'priority' => 'between:0,3',
            'user_id' => 'required|integer',
        ]);

        new Task()->create([
            'project_id' => $project_id,
            'name' => $request->name,
            'description' => $request->description,
            'start' => $request->start,
            'end' => $request->end,
            'state' => $request->state != null ? $request->state : 0,
            'priority' => $request->priority != null ? $request->priority : 1,
            'user_id' => $request->user_id,
        ]);

        return redirect(route('projects.overview', $project_id));
    }

    public function TasksEdit($project_id, $task_id)
    {
        $project = Project::findorFail($project_id)
        ->load('users','tasks');

        //permitions check
        $user = $project->users->where('id', auth()->id())->first();

        if (!$project->users->contains('id', auth()->id())) {
            abort(code: 403);
        }  
        if($user->pivot->user_type == 0 && $project->task->user_id != auth()->id()){
            abort(403);
        }
        //permitions check end

        $task = Task::where('id', $task_id)->first();

        return view('pages.tasks.tasks-create', [
            'project' => $project,
            'user'=> auth()->user(),
            'task' => $task,
        ]);
    }

    public function TasksUpdate(Request $request, $project_id, $task_id){

        $project = Project::findorFail($project_id)
        ->load('users','tasks');

        //permitions check
        $user = $project->users->where('id', auth()->id())->first();

        if (!$project->users->contains('id', auth()->id())) {
            abort(code: 403);
        }  
        if($user->pivot->user_type == 0 && $project->task->user_id != auth()->id()){
            abort(403);
        }
        //permitions check end

        $request->validate([
            'name' => 'required|string|max:255',
            'start' => 'required|date|before:end',
            'end' => 'required|date',
            'user_id' => 'required'
        ]);

        Task::findOrFail($task_id)->update([
            'project_id' => $project_id,
            'user_id' => $request->user_id,
            'name' => $request->name,
            'description' => $request->description,
            'start' => $request->start,
            'end' => $request->end,
        ]);

        return redirect(route('projects.overview', $project_id));
    }

    public function TasksDelete($task){
        $project = Project::findorFail($task->id_project)
        ->load('users','tasks');

        //permitions check
        $user = $project->users->where('id', auth()->id())->first();

        if (!$project->users->contains('id', auth()->id())) {
            abort(code: 403);
        }  
        if($user->pivot->user_type == 0 && $project->task->user_id != auth()->id()){
            abort(403);
        }
        //permitions check end

        $task = Task::where('id', $task->id);

        $task->delete();

        return redirect(route('projects.overview', $task->id_project));
    }

    public function TaskOverview($task_id){

        $task = Task::where('id', $task_id)->first();

        $project = Project::findorFail($task->project_id)
        ->load('users','tasks');

        //permitions check
        if (!$project->users->contains('id', auth()->id())) {
            abort(code: 403);
        }
        //permitions check end

        return view('pages.tasks.overview', [
            'task' => $task,
            'project' => $project,
            'user' => auth()->user(),
        ]);
    }
}
