<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;

class DashboardController extends Controller {

    public function dashboard(Request $request){
        
        $query = auth()->user()->projects->where('archived', false);

        if($request->filled('project')){
            $project_id = $request->project;

            $project = $query->find($project_id)->load(['tasks', 'users']);
        }else{
            $project = $query->first()->load(['tasks', 'users']);
        }

        return view("pages.dashboard",[
            'projects' => auth()->user()->projects->where('archived', false),
            'vProject' => $project,
            'tasks' => Task::whereHas('project', function ($query) {
                    $query->where('archived', false);
                })->where('user_id', auth()->id())->get(),
        ]);
    }

    public function settings(){
        return view("pages.settings");
    }
}
