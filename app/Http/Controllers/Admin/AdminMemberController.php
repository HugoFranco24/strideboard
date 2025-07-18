<?php

namespace App\Http\Controllers\Admin;

use App\Models\Task;
use App\Models\User;
use App\Models\Inbox;
use App\Models\Project;
use Illuminate\View\View;
use App\Models\ProjectUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class AdminMemberController extends Controller {

    public function create(int $project_id): View{

        $users = User::whereNotIn('id', DB::table('projects_users')
            ->select('user_id')
            ->where('project_id', $project_id))
            ->select('id', 'pfp', 'name', 'email')
            ->get();

        return view('admin.projects.member-form', [
            'users' => $users,
            'project_id' => $project_id,
        ]);
    }

    public function store(int $id, Request $request): RedirectResponse{

        $project = Project::findOrFail($id);

        $request->validate([
            'user' => 'required',
            'user_type' => 'required',
            'active' => 'required|boolean',
        ]);

        $project_user = ProjectUser::create([
            'project_id' => $project->id,
            'user_id' => $request->user,
            'user_type' => $request->user_type,
            'active' => $request->active,
        ]);

        if($request->active == 0){
            Inbox::create([
                'receiver_id' => $request->user,
                'actor_id' => 0,
                'type' => 'invited',
                'project_name' => $project->name,
                'reference_id' => $project_user->id,
            ]);
        }

        return redirect(route('admin.project.overview', $id))->with('status', 'Collaborator added with success!');
    }

    public function updateType(int $project_id, int $user_id, Request $request): RedirectResponse{
        
        $request->validate([
            'user_type' => 'required',
        ]);

        $project_user = ProjectUser::where('project_id', $project_id)->where('user_id', $user_id)->firstOrFail();

        $project_user->update([
            'user_type' => $request->user_type,
        ]);

        return redirect(route('admin.project.overview', $project_id))->with('status', 'Collaborator type updated with success!');
    }

    public function updateActive(int $project_id, int $user_id, Request $request): RedirectResponse{
        
        $project = Project::findOrFail($project_id);

        $request->validate([
            'active' => 'required',
        ]);

        $project_user = ProjectUser::where('project_id', $project_id)->where('user_id', $user_id)->firstOrFail();

        $project_user->update([
            'active' => $request->active,
        ]);

        if(!$project_user->active){
            Inbox::create([
                'receiver_id' => $user_id,
                'actor_id' => 0,
                'type' => 'invited',
                'project_name' => $project->name,
                'reference_id' => $project_user->id,
            ]);
        }

        return redirect(route('admin.project.overview', $project_id))->with('status', 'Collaborator active attribute updated with success!');
    }

    public function destroy(int $project_id, int $user_id): RedirectResponse{

        $project = Project::findOrFail($project_id);

        $project_user = ProjectUser::where('project_id', $project_id)
            ->where('user_id', $user_id)
            ->firstOrFail();

         $ownedTasks = Task::where('user_id', $project_user->user_id)->get();

        foreach ($ownedTasks as $task) {
            $newOwner = ProjectUser::where('project_id', $task->project_id)
                        ->where('user_id', '!=', $user_id)
                        ->whereIn('user_type', [2, 1, 0])
                        ->orderByDesc('user_type')
                        ->first();

            if ($newOwner) {
                $task->user_id = $newOwner->user_id;
                $task->save();
            } else {
                $task->delete();
            }
        }

        Inbox::create([
            'receiver_id' => $user_id,
            'actor_id' => 0,
            'type' => 'removed',
            'project_name' => $project->name,
        ]);

        $project_user->delete();

        return redirect(route('admin.project.overview', $project_id))->with('status', 'Collaborator removed with success!');
    }
}