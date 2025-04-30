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

class DashboardController extends Controller {

    public function dashboard(){
        return view("pages.dashboard", [
            'user' => auth()->user(),
        ]);
    }

    

    public function tasks(){
        return view("pages.tasks", [
            'user' => auth()->user(),
        ]);
    }

    public function calendar(){
        return view("pages.calendar", [
            'user' => auth()->user(),
        ]);
    }

    public function messages(){
        return view('pages.messages', [
            'user' => auth()->user(),
            'userAll' => User::all(),
        ]);
    }

    public function profile(){
        

        return view('pages.profile', [
            'user' => auth()->user(),
        ]);
    }

    public function settings(){
        return view("pages.settings", [
            'user' => auth()->user(),
        ]);
    }

    //project functions
    public function projects(){

        $projects = auth()->user()->projects; //gets all projects that the user has

        return view('pages.projects', [
            'user' => auth()->user(),
            'projects' => $projects
        ]);
    }

    public function projectsCreate(){
        return view('pages.projects-create', [
            'user' => auth()->user(),
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
        $projectsUsers->user_type = 1;
        $projectsUsers->save();

        return redirect()->route('dashboard.projects');
    }

    public function projectsEdit($id){

        $project = Projects::findorFail($id);

        return view('pages.projects-create', [
            'user' => auth()->user(),
            'project' => $project
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

        return view('pages.projects-create', [
            'user' => auth()->user(),
        ]);
    }
}
