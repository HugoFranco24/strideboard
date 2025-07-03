<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller {

    public function dashboard(Request $request): View{
        
        $projects = auth()->user()
        ->projects()
        ->where('archived', false)
        ->with(['tasks', 'users'])
        ->get();

        if($request->filled('project')){
            $project = $projects->firstWhere('id', $request->project);
        } else {
            $project = $projects->first();
        }

        return view("pages.dashboard",[
            'projects' => auth()->user()->projects->where('archived', false),
            'vProject' => $project,
            'tasks' => Task::whereHas('project', function ($query) {
                    $query->where('archived', false);
                })->where('user_id', auth()->id())->get(),
        ]);
    }

    public function settings(): View{
        return view("pages.settings");
    }
}
