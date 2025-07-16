<?php

namespace App\Http\Controllers\Admin;

use App\Models\Task;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminTaskController extends Controller {

    public function index(): View{

        return view('admin.tasks.index', [
            'tasks' => Task::all()->load(['project', 'user']),
        ]);
    }

    public function create(): View{
        
        return view('admin.tasks.index');
    }

    public function store(): View{
        
        return view('admin.tasks.index');
    }

    public function edit(): View{
        
        return view('admin.tasks.index');
    }

    public function update(): View{
        
        return view('admin.tasks.index');
    }

    public function destroy(): View{
        
        return view('admin.tasks.index');
    }
}