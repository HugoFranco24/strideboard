<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Project;
use Illuminate\View\View;
use App\Models\ProjectUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class AdminProjectController extends Controller {

    public function index(): View{

        return view('admin.projects.index', [
            'projects' => Project::get()->all(),
        ]);
    }

    public function create(): View{
        
        return view('admin.projects.form', [
            'users' => User::get()->all(),
        ]);
    }

    public function store(Request $request): RedirectResponse{

        $request->validate([
            'owner' => 'required',
            'name' => 'required|string|max:255',
            'business' => 'nullable|max:255',
            'due_date' => 'required|date',
            'color' => 'hex_color|required',
            'archived' => 'required|boolean',
        ]);

        $project = Project::create([
            'name' => $request->name,
            'business' => $request->business,
            'due_date' => $request->due_date,
            'color' => $request->color,
            'archived' => $request->archived,
        ]);

        ProjectUser::create([
            'project_id' => $project->id,
            'user_id' => $request->owner,
            'user_type' => 2,
            'active' => 1,
        ]);
        
        return redirect(route('admin.projects.index'))->with('status', 'Project created with success!');
    }

    public function edit(int $id): View{

        $project = Project::with('users')->findOrFail($id);

        return view('admin.projects.form', [
            'users' => $project->users,
            'project' => Project::findOrFail($id),
        ]);
    }

    public function update($id, Request $request): RedirectResponse{

        $project = Project::findorFail($id);

        $project_owner = ProjectUser::where('project_id', $id)->where('user_type', 2)->firstorFail();

        $request->validate([
            'owner' => 'required',
            'name' => 'required|string|max:255',
            'business' => 'nullable|max:255',
            'due_date' => 'required|date',
            'color' => 'hex_color|required',
            'archived' => 'required|boolean',
        ]);

        if($request->owner != $project_owner->id){
            $project_owner->update([
               'user_type' => 1, 
            ]);

            $new_owner = ProjectUser::where('project_id', $id)
            ->where('user_id',$request->owner)
            ->firstorFail();

            $new_owner->update([
                'user_type' => 2,
            ]);
        }

        $project->update([
            'name' => $request->name,
            'business' => $request->business,
            'due_date' => $request->due_date,
            'color' => $request->color,
            'archived' => $request->archived,
        ]);

        return redirect(route('admin.project.overview', $id))->with('status', 'Project updated with success!');
    }

    public function destroy(): View{
        
        return view('admin.projects.index');
    }

    public function overview(int $id): View{

        return view('admin.projects.overview', [
            'project' => Project::with(['tasks', 'users'])->findOrFail($id),
            'project_users' => ProjectUser::where('project_id', $id)->get(),
        ]);
    }
}