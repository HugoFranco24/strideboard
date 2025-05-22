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

        return view('pages.projects.tasks-create', [
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
            'start' => 'required|date|before:end',
            'end' => 'required|date',
            'user_id' => 'required'
        ]);

        new Task()->create([
            'project_id' => $project_id,
            'user_id' => $request->user_id,
            'name' => $request->name,
            'description' => $request->description,
            'start' => $request->start,
            'end' => $request->end,
            'state' => 0,
        ]);

        return redirect(route('projects.overview', $project_id));
    }

    public function TasksEdit($project_id)
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

        return view('pages.projects.projects-create', [
            'project' => $project,
            'user'=> auth()->user()
        ]);
    }

    public function TasksUpdate(Request $request, $project_id){

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

        Project::findOrFail($project_id)->update([
            'project_id' => $project_id,
            'user_id' => $request->user_id,
            'name' => $request->name,
            'description' => $request->description,
            'start' => $request->start,
            'end' => $request->end,
        ]);

        return redirect(route('projects.overview', $project_id));
    }
}
