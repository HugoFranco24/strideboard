<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use phpDocumentor\Reflection\Types\Boolean;
use App\Models\User;
use App\Models\Projects;
use App\Models\Tasks;
use App\Models\ProjectsUsers;
use App\Models\ProjectsTasks;
use Illuminate\Support\Facades\DB;

class ProjectsController extends Controller {

    public function projects(){

        $projects = auth()->user()->projects; //gets all projects that the user has

        return view('pages.projects.projects', [
            'user' => auth()->user(),
            'projects' => $projects
        ]);
    }

    public function projectsCreate(){
        return view('pages.projects.projects-create', [
            'user' => auth()->user(),
            'user_all' => User::all()->where('id', '!=', auth()->id())
        ]);
    }

    public function projectsCreateAdd(Request $request){
        
        $request->validate([
            'name' => 'required|string|max:255',
            'business' => 'required|string|max:255',
            'due_date' => 'required',
        ]);

        $project = new Projects();
        $project->name = $request->name;
        $project->business = $request->business;
        $project->due_date = $request->due_date;
        $project->save();


        $projectsUsers = new ProjectsUsers();
        $projectsUsers->id_project = $project->id_project;
        $projectsUsers->id_user = auth()->id();
        $projectsUsers->user_type = 2;
        $projectsUsers->save();

        return redirect(route('projects.overview', $project->id_project));
    }

    public function projectsEdit($id){

        $project = Projects::findorFail($id);

        return view('pages.projects.projects-create', [
            'project' => $project,
            'user'=> auth()->user()
        ]);
    }

    public function projectsUpdate(Request $request, $id){

        $request->validate([
            'name' => 'required|string|max:255',
            'business' => 'required|string|max:255',
            'due_date' => 'required',
        ]);

        $project = Projects::findorFail($id);
        $project->name = $request->name;
        $project->business = $request->business;
        $project->due_date = $request->due_date;
        $project->save();

        return redirect(route('projects.overview', $id));
    }

    public function projectOverview($id){
        $project = Projects::findorFail($id);

        $project_users = ProjectsUsers::leftJoin('users', 'projects_users.id_user', '=', 'users.id') //ver todos os utilizadores ligados ao projeto
        ->where('projects_users.id_project', $id)
        ->select('users.id','users.pfp', 'users.name', 'users.email', 'projects_users.user_type')
        ->get();

        $owner = ProjectsUsers::leftJoin('users', 'projects_users.id_user', '=', 'users.id') // ver o owner do projeto
        ->where('projects_users.id_project', $id)
        ->where('projects_users.user_type', 2)
        ->select('users.name')
        ->first();
        
        $user_all = User::whereNotIn('id', DB::table('projects_users') //ver todos os users que nao estao ligado ao project
        ->select('id_user')
        ->where('id_project', $project->id_project))
        ->select('id', 'pfp', 'name', 'email')
        ->get();

        $authUserType = ProjectsUsers::where('id_project', $id) //ver qual é o tipo de user
        ->where('id_user', auth()->id())
        ->value('user_type');


        return view('pages.projects.projects-overview', [
            'user' => auth()->user(),
            'authUserType' => $authUserType,
            'project' => $project,
            'project_users' => $project_users,
            'user_all' => $user_all,
            'owner' => $owner
        ]);
    }

    public function addMember($id_project, $id_user){

        $projectsUsers = new ProjectsUsers();
        $projectsUsers->id_project = $id_project;
        $projectsUsers->id_user = $id_user;
        $projectsUsers->user_type = 0;
        $projectsUsers->save();

        return redirect(route('projects.overview', $id_project));
    }

    public function deleteMember($id_project, $id_user){

        $projectsUsers = ProjectsUsers::where('id_project', $id_project)
                                        ->where('id_user', $id_user)
                                        ->first();


        if($id_user != auth()->id()){
            return redirect(route('projects.overview', $id_project));
        }else{
            return redirect(route('dashboard.projects'));
        }
    }

    public function updateMember($id_project, $id_user){

        $projectsUsers = ProjectsUsers::where('id_project', $id_project)
                                        ->where('id_user', $id_user)
                                        ->first();

        $projectsUsers->user_type = request('user_type');
        $projectsUsers->save();

        return redirect(route('projects.overview', $id_project));    
    }
}
