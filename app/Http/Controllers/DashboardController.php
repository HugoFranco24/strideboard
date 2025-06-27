<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;

class DashboardController extends Controller {

    public function dashboard(Request $request){
        
        $query = auth()->user()->projects;

        if($request->filled('project')){
            $project_id = $request->project;

            $project = $query->find($project_id)->load(['tasks', 'users']);
        }else{
            $project = $query->first()->load(['tasks', 'users']);
        }
        
        return view("pages.dashboard",[
            'projects' => auth()->user()->projects,
            'vProject' => $project,
            'tasks' => Task::where('user_id', auth()->id()),
        ]);
    }

    public function settings(){
        return view("pages.settings");
    }
}
