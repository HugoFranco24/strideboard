<?php

namespace App\Http\Controllers;

class DashboardController extends Controller {

    public function dashboard(){
        return view("pages.dashboard");
    }

    public function settings(){
        return view("pages.settings");
    }
}
