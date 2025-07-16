<?php

namespace App\Http\Controllers\Admin;

use App\Models\Project;
use App\Models\Task;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminProjectController extends Controller {

    public function index(): View{

        return view('admin.projects.index', [
            'projects' => Project::get()->all(),
        ]);
    }

    public function create(): View{
        
        return view('admin.projects.index');
    }

    public function store(): View{
        
        return view('admin.projects.index');
    }

    public function edit(): View{
        
        return view('admin.projects.index');
    }

    public function update(): View{
        
        return view('admin.projects.index');
    }

    public function destroy(): View{
        
        return view('admin.projects.index');
    }
}