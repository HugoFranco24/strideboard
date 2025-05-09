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
}
