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
use Pest\Configuration\Project;

class DashboardController extends Controller {

    public function dashboard(){
        return view("pages.dashboard");
    }

    

    public function tasks(){
        return view("pages.tasks");
    }

    public function calendar(){
        return view("pages.calendar");
    }

    public function messages(){

        return view('pages.messages', [
            'user' => User::all(),
        ]);
    }

    public function profile(Request $request){
        

        return view('pages.profile', [
            'user' => $request->user(),
        ]);
    }

    public function settings(){
        return view("pages.settings");
    }

    //project functions
    public function projects(Request $request){

        $projects = auth()->user()->projects; //gets all projects that the user has

        return view('pages.projects', [
            'user' => auth()->user(),
            'projects' => $projects
        ]);
    }

    public function projectsCreate(){
        return view('pages.projects-create');
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
        $projectsUsers->user_type = 1;
        $projectsUsers->save();

        return redirect()->route('dashboard.projects');
    }
}
