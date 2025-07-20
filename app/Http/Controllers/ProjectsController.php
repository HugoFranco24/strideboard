<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Task;
use App\Models\Inbox;
use App\Models\Project;
use Illuminate\View\View;
use App\Models\ProjectUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;

class ProjectsController extends Controller 
{
    public function projects(): View
    {
        $projects = auth()->user()->projects()
            ->where('archived', false)
            ->wherePivot('active', true)
            ->get();

        return view('pages.projects.projects', [
            'projects' => $projects,
            'page' => 'projects',
        ]);
    }

    public function projectCreate(): View
    {
        return view('pages.projects.projects-create');
    }

    public function projectAdd(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'business' => 'nullable|max:255',
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

    public function projectEdit(int $id): View
    {   
        $project = $this->permissionsCheck($id);

        return view('pages.projects.projects-create', [
            'project' => $project,
        ]);
    }

    public function projectUpdate(Request $request, int $id): RedirectResponse
    {
        $project = $this->permissionsCheck($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'business' => 'max:255',
            'due_date' => 'required|date',
            'color' => 'hex_color|required',
        ]);

        if($project->due_date != $request->due_date){
            $project->update([
                'late_notified' => false,
            ]);
        }

        $project->update([
            'name' => $request->name,
            'business' => $request->business,
            'due_date' => $request->due_date,
            'color' => $request->color,
        ]);

        $receivers = ProjectUser::where('project_id', $project->id)
        ->where('active', true)
        ->whereNot('user_id', auth()->id())
        ->get();

        foreach($receivers as $reciever){
            Inbox::create([
                'receiver_id' => $reciever->user_id,
                'actor_id' => auth()->id(),
                'type' => 'updated_project',
                'project_name' => $project->name,
                'reference_id' => $project->id,
            ]);
        }

        return redirect(route('projects.overview', $id));
    }

    public function projectDelete(int $id): RedirectResponse
    {
        $project = $this->permissionsCheck($id, false, [2]);
        
        $projects_users = ProjectUser::where('project_id', $id)->get();

        $receivers = ProjectUser::where('project_id', $project->id)
        ->where('active', true)
        ->whereNot('user_id', auth()->id())
        ->get();

        foreach($receivers as $reciever){
            Inbox::create([
                'receiver_id' => $reciever->user_id,
                'actor_id' => auth()->id(),
                'type' => 'deleted_project',
                'project_name' => $project->name,
            ]);
        }

        $project->tasks()->delete();
        foreach($projects_users as $pu){
            $pu->delete();
        }
        $project->delete();

        return redirect(route('dashboard.projects'));
    }

    public function projectOverview(int $id): RedirectResponse|View
    {   
        $project = $this->permissionsCheck($id, true, [0, 1, 2]);


        $my_tasks = $project->tasks
                ->where('user_id', auth()->id());
        $late = $project->tasks
                ->where('end', '<', now()->format('Y-m-d'));
        $urgent = $project->tasks
                ->where('priority', 3);
        $done = $project->tasks
                ->where('state', 3);
        

        $user_all = User::whereNotIn('id', DB::table('projects_users') //ver todos os users que nao estao ligado ao project
        ->select('user_id')
        ->where('project_id', $project->id))
        ->select('id', 'pfp', 'name', 'email')
        ->get();

        return view('pages.projects.projects-overview', [
            'project' => $project,
            'user_all' => $user_all,
            'authUserType' => $project->users->filter(fn($u) => $u->id == auth()->id())->first()->pivot->user_type,
            'owner' => $project->users->filter(fn($u) => $u->pivot->user_type == 2)->first(),
            'my_tasks' => $my_tasks,
            'late' => $late,
            'urgent' => $urgent,
            'done' => $done,
        ]);
    }

    public function addMember(int $project_id, int $user_id): RedirectResponse
    {
        $project = $this->permissionsCheck($project_id);

        $project_user = ProjectUser::create([
            'project_id' => $project_id,
            'user_id' => $user_id,
            'user_type' => 0,
            'active' => false,
        ]);

        Inbox::create([
            'receiver_id' => $user_id,
            'actor_id' => auth()->id(),
            'type' => 'invited',
            'project_name' => $project->name,
            'reference_id' => $project_user->id,
        ]);

        return redirect(route('projects.overview', $project_id));
    }

    public function updateMember(Request $request, int $project_id, int $user_id): RedirectResponse{

        $project = $this->permissionsCheck($project_id);

        $project_user = ProjectUser::where('project_id', $project_id)
                                    ->where('user_id', $user_id)
                                    ->firstOrFail();

        // Additional custom permission check, ensure current user can’t update same role type
        $user = $project->users()
            ->where('users.id', auth()->id())
            ->wherePivot('active', true)
            ->firstOrFail();

        if ($user->pivot->user_type == 0 || $user->pivot->user_type == $project_user->user_type) {
            abort(403);
        }


        $request->validate([
            'user_type' => 'required',
        ]);

        $project_user->update([
            'user_type'=> $request->user_type
        ]);

        Inbox::create([
            'receiver_id' => $user_id,
            'actor_id' => auth()->id(),
            'type' => 'changed_role',
            'project_name' => $project->name,
            'reference_id' => $project->id,
        ]);

        return redirect(route('projects.overview', $project_id));    
    }

    public function deleteMember(int $project_id, int $user_id): RedirectResponse
    {
        //this permitions can't be in the helper
        $project = Project::where('id', $project_id)
            ->with(['users', 'tasks'])
            ->firstOrFail();
    
        $project_user = ProjectUser::where('project_id', $project_id)
            ->where('user_id', $user_id)
            ->where('user_type', '!=', 2)
            ->firstOrFail();
        
        
        $user = $project->users()
            ->where('users.id', auth()->id())
            ->wherePivot('active', true)
            ->firstOrFail();

        if($user->pivot->user_type == 0 && $user->id != $project_user->user_id){
            abort(403);
        }
        if($user->pivot->user_type == 1 && $project_user->user_type == 1 && $user->id != $project_user->user_id){
            abort(403);
        }
        if($project->archived && $user->id != $project_user->user_id){
            abort(404);
        }


        $ownedTasks = Task::where('user_id', $project_user->user_id)->get();

        foreach ($ownedTasks as $task) {
            $newOwner = ProjectUser::where('project_id', $task->project_id)
                        ->where('user_id', '!=', $project_user->user_id)
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

        if($user->id != $project_user->user_id){
            Inbox::create([
                'receiver_id' => $user_id,
                'actor_id' => auth()->id(),
                'type' => 'removed',
                'project_name' => $project->name,
            ]);
        }else{
            $noti_users = ProjectUser::where('project_id', $project_user->project_id)
                            ->where('active', true)
                            ->whereIn('user_type', [2, 1])
                            ->whereNot('user_id', auth()->id())
                            ->get();

            foreach($noti_users as $nu){
                Inbox::create([
                    'receiver_id' => $nu->user_id,
                    'actor_id' => $project_user->user_id,
                    'type' => 'left',
                    'project_name' => $project->name,
                ]);
            }
        }

        $project_user->delete();

        if($user->id != $project_user->user_id){
            return redirect(route('projects.overview', $project_id));
        }else{
            return redirect(route('dashboard.projects'));
        }
    }

    //region Archiving
    public function seeArchived(): View{
        $projects = auth()->user()->projects()
            ->where('archived', true)
            ->wherePivot('active', true)
            ->get();

        return view('pages.projects.projects', [
            'projects' => $projects,
            'page' => 'completed',
        ]);
    }
    public function archiveToggle(int $id): RedirectResponse{
        $project = $this->permissionsCheck($id, false, [2]);

        $project->update([
            'archived' => !$project->archived,
        ]);

        foreach($project->users->where('id', '!=', auth()->id())->where('active', true) as $pu){
            Inbox::create([
                'receiver_id' => $pu->id,
                'actor_id' => auth()->id(),
                'type' => $project->archived ? 'marked_complete' : 'restored',
                'project_name' => $project->name,
            ]);
        }

        return redirect($project->archived ? route('projects.archived') : route('dashboard.projects'));
    }

    //region Invites

    public function acceptInvite(int $id): RedirectResponse{

        $pu = ProjectUser::where('id', $id)->firstOrFail();
        
        $noti_users = ProjectUser::where('project_id', $pu->project_id)
                            ->whereIn('user_type', [2, 1])->get();

        $project = Project::where('id', $pu->project_id)->firstOrFail();

        if($pu->user_id != auth()->id()){
            abort(403);
        }

        foreach($noti_users as $nu){
            Inbox::create([
                'receiver_id' => $nu->user_id,
                'actor_id' => $pu->user_id,
                'type' => 'accepted',
                'project_name' => $project->name,
                'reference_id' => $project->id,
            ]);
        }

        $pu->update(['active' => true]);

        return redirect(route('projects.overview', $project->id));
    }

    public function rejectInvite(int $id): RedirectResponse{

        $pu = ProjectUser::where('id', $id)->firstOrFail();
        
        $noti_users = ProjectUser::where('project_id', $pu->project_id)
                            ->whereIn('user_type', [2, 1])
                            ->get();

        $project = Project::where('id', $pu->project_id)->firstOrFail();

        if($pu->user_id != auth()->id()){
            abort(403);
        }

        foreach($noti_users as $nu){
            Inbox::create([
                'receiver_id' => $nu->user_id,
                'actor_id' => $pu->user_id,
                'type' => 'rejected',
                'project_name' => $project->name,
            ]);
        }

        $pu->delete();

        return redirect()->back();
    }
}
