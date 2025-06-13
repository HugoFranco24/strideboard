<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Project;
use App\Models\ProjectUser;
use App\Models\Task;
use Illuminate\Support\Facades\DB;

class ProjectsController extends Controller {

    public function projects()
    {
        return view('pages.projects.projects', [
            'projects' => auth()->user()->projects->filter(fn($p) => $p->pivot->active == true),
        ]);
    }

    public function projectsCreate()
    {
        return view('pages.projects.projects-create');
    }

    public function projectsCreateAdd(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'business' => 'max:255',
            'due_date' => 'required|date',
            'color' => 'hex_color|required',
        ]);

        $project = Project::create([
            'name' => $request->name,
            'business' => $request->business,
            'due_date' => $request->due_date,
            'color' => $request->color,
        ]);
        
        ProjectUser::create([
            'project_id' => $project->id,
            'user_id' => auth()->id(),
            'user_type' => 2,
            'active' => 1,
        ]);

        return redirect(route('projects.overview', $project->id));
    }

    public function projectsEdit($id)
    {   
        $project = Project::findorFail($id)
        ->load('users','tasks');

        //permitions check
        $user = $project->users
            ->filter(fn($user) => $user->id === auth()->id() && $user->pivot->active == true)
            ->firstOrFail();

        if (!$project->users->firstWhere('id', auth()->id())?->pivot->active) {
            abort(code: 403);
        }  
        if($user->pivot->user_type == 0){
            abort(403);
        }
        //permitions check end

        return view('pages.projects.projects-create', [
            'project' => $project,
        ]);
    }

    public function projectsUpdate(Request $request, $id)
    {   
        $project = Project::findorFail($id)
        ->load('users','tasks');

        //permitions check
        $user = $project->users
            ->filter(fn($user) => $user->id === auth()->id() && $user->pivot->active == true)
            ->firstOrFail();

        if(!$project->users->firstWhere('id', auth()->id())?->pivot->active) {
            abort(code: 403);
        }  
        if($user->pivot->user_type == 0){
            abort(403);
        }
        //permitions check end
        
        $request->validate([
            'name' => 'required|string|max:255',
            'business' => 'max:255',
            'due_date' => 'required|date',
            'color' => 'hex_color|required',
        ]);

        Project::findOrFail($id)->update([
            'name' => $request->name,
            'business' => $request->business,
            'due_date' => $request->due_date,
            'color' => $request->color,
        ]);

        return redirect(route('projects.overview', $id));
    }

    public function projectsDelete($id)
    {
        $project = Project::findorFail($id)
        ->load('users','tasks');

        $projects_users = ProjectUser::where('project_id', $id);

        $user = $project->users
            ->where('id', auth()->id())
            ->where('active', true)
            ->firstOrFail();

        if(!$project->users->firstWhere('id', auth()->id())?->pivot->active) {
            abort(code: 403);
        }  
        if($user->pivot->user_type != 2){
            abort(403);
        }

        foreach($project->tasks as $task){
            $task->delete();
        }
        foreach($projects_users as $p_user){
            $p_user->delete();
        }
        $project->delete();

        return redirect(route('dashboard.projects'));
    }

    public function projectOverview($id)
    {   
        $project = Project::with('users', 'tasks')->findOrFail($id);

        if (!$project) {
            return redirect()->route('dashboard.projects');
        }

        //permitions check
        if (!$project->users->firstWhere('id', auth()->id())?->pivot->active) {
            abort(code: 403); //ver se o utilizador está no projeto
        }     
        //permitions check end

        //for kanban
        $my_tasks = $project->tasks
                ->where('user_id', auth()->id());
        $late = $project->tasks
                ->where('end', '<', now()->format('Y-m-d'));
        $urgent = $project->tasks
                ->where('priority', 3);
        $done = $project->tasks
                ->where('state', 3);
        //end for kanban
        
        $user_all = User::whereNotIn('id', DB::table('projects_users') //ver todos os users que nao estao ligado ao project
        ->select('user_id')
        ->where('project_id', $project->id))
        ->select('id', 'pfp', 'name', 'email')
        ->get();

        return view('pages.projects.projects-overview', [
            'project' => $project,
            'user_all' => $user_all,
            'authUserType' => $project->users->filter(fn(User $u) => $u->id == auth()->id())->first()->pivot->user_type,
            'owner' => $project->users->filter(fn(User $u) => $u->pivot->user_type == 2)->first(),
            'my_tasks' => $my_tasks,
            'late' => $late,
            'urgent' => $urgent,
            'done' => $done,
        ]);
    }

    public function addMember($project_id, $user_id)
    {
        $project = Project::findorFail($project_id)
        ->load('users','tasks');

        //permitions check
        $user = $project->users
            ->filter(fn($user) => $user->id === auth()->id() && $user->pivot->active == true)
            ->firstOrFail();

        if (!$project->users->firstWhere('id', auth()->id())?->pivot->active) {
            abort(code: 403);
        }  
        if($user->pivot->user_type == 0){
            abort(403);
        }
        //permitions check end

        $project_user = ProjectUser::create([
            'project_id' => $project_id,
            'user_id' => $user_id,
            'user_type' => 0,
            'active' => false,
        ]);

        Notification::create([
            'receiver_id' => $user_id,
            'actor_id' => auth()->id(),
            'type' => 'invited',
            'table' => 'project',
            'message' => $project->name,
            'reference_id' => $project_user->id,
        ]);

        return redirect(route('projects.overview', $project_id));
    }

    public function updateMember(Request $request, $project_id, $user_id){

        $project = Project::findorFail($project_id)
        ->load('users','tasks');

        $project_user = ProjectUser::where('project_id', $project_id)
                                    ->where('user_id', $user_id)
                                    ->firstOrFail();

        $user = $project->users
            ->filter(fn($user) => $user->id === auth()->id() && $user->pivot->active == true)
            ->firstOrFail();

        if (!$project->users->firstWhere('id', auth()->id())?->pivot->active) {
            abort(code: 403); //ver se o utilizador está no projeto
        }
        if($user->pivot->user_type == 0 && $user->id != $project_user->user_id){
            abort(403);
        }
        if($user->pivot->user_type == $project_user->user_type && $user->id != $project_user->user_id){
            abort(403);
        }

        $request->validate([
            'user_type' => 'required',
        ]);

        $project_user->update([
            'user_type'=> $request->user_type
        ]);

        Notification::create([
            'receiver_id' => $user_id,
            'actor_id' => auth()->id(),
            'type' => 'changed_role',
            'table' => 'project',
            'message' => $project->name,
        ]);

        return redirect(route('projects.overview', $project_id));    
    }

    public function deleteMember($project_id, $user_id)
    {
        $project = Project::findorFail($project_id)
        ->load('users','tasks');

        $project_user = ProjectUser::where('project_id', $project_id)
                                    ->where('user_id', $user_id)
                                    ->firstOrFail();
        $user = $project->users
            ->filter(fn($user) => $user->id === auth()->id() && $user->pivot->active == true)
            ->firstOrFail();

        //permitions check
        if (!$project->users->firstWhere('id', auth()->id())?->pivot->active) {
            abort(code: 403); //ver se o utilizador está no projeto
        }
        if($user->pivot->user_type == 0 && $user->id != $project_user->user_id){
           
            abort(403);
        }
        if($user->pivot->user_type == 1 && $project_user->user_type == 1 && $user->id != $project_user->user_id){
            abort(403);
        }
        //permitions check end

        //deleting tasks where the user was
        $ownedTasks = Task::where('user_id', $project_user->user_id)->get();

        foreach ($ownedTasks as $task) {
            $newOwner = ProjectUser::where('project_id', $task->project_id)
                        ->where('user_id', '!=', $user->id)
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
        //end deleting tasks where the user was

        Notification::create([
            'receiver_id' => $user_id,
            'actor_id' => auth()->id(),
            'type' => 'removed',
            'table' => 'project',
            'message' => $project->name,
        ]);
        
        $project_user->delete();

        if($user->id != $project_user->user_id){
            return redirect(route('projects.overview', $project_id));
        }else{
            return redirect(route('dashboard.projects'));
        }
    }

    //region Invites

    public function acceptInvite($id){

        $pu = ProjectUser::where('id', $id)->firstOrFail();
        
        $noti_users = ProjectUser::where('project_id', $pu->project_id)
                            ->where('user_type', [2, 1])->get();

        $project = Project::where('id', $pu->project_id)->firstOrFail();

        foreach($noti_users as $nu){
            Notification::create([
                'receiver_id' => $nu->id,
                'actor_id' => $pu->user_id,
                'type' => 'accepted',
                'table' => 'project',
                'message' => $project->name,
            ]);
        }

        $pu->update(['active' => true]);

        return redirect(route('projects.overview', $project->id));
    }

    public function rejectInvite($id){

        $pu = ProjectUser::where('id', $id)->firstOrFail();
        
        $noti_users = ProjectUser::where('project_id', $pu->project_id)
                            ->where('user_type', [2, 1]);

        $project = Project::where('id', $pu->project_id)->firstOrFail();

        foreach($noti_users as $nu){
            Notification::create([
                'receiver_id' => $nu->id,
                'actor_id' => $pu->user_id,
                'type' => 'accepted',
                'table' => 'project',
                'message' => $project->name,
            ]);
        }

        $pu->delete();

        return redirect()->back();
    }
}
