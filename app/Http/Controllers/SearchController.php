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

class SearchController extends Controller {

    public function searchUsers(Request $request)
    {
        $term = $request->input('term');

        $users = User::where('id', '!=', auth()->id())
                    ->where('name', 'like', '%' . $term . '%')
                    ->select('id', 'name', 'email')
                    ->get();

        return response()->json($users);
    }
}