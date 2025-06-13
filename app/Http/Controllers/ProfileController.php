<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use App\Models\Project;
use App\Models\Task;
use App\Models\ProjectUser;
use App\Models\User;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(): View
    {
        return view('pages.profile.edit');
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'Personal Information Updated!');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $user = $request->user();

        Auth::logout();

        $ownedProjectIds = ProjectUser::where('user_id', $user->id)
                            ->where('user_type', 2)
                            ->pluck('project_id');

        foreach ($ownedProjectIds as $project_id) {
            $newOwner = ProjectUser::where('project_id', $project_id)
                        ->where('user_id', '!=', $user->id)
                        ->whereIn('user_type', [1, 0])
                        ->orderByDesc('user_type')
                        ->first();

            if (!$newOwner) {
                Project::find($project_id)->delete();
            } else {
                ProjectUser::where('project_id', $project_id)
                    ->where('user_id', $newOwner->user_id)
                    ->update(['user_type' => 2]);
            }
        }

        $tasks = Task::where('user_id', $user->id)->get();

        foreach ($tasks as $task) {
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

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function uploadImg(Request $request): RedirectResponse
    {

        $request->validate([
            'pfp' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = $request->user();

        if ($user->pfp && file_exists(public_path($user->pfp))) {
            unlink(public_path($user->pfp));
        }

        $filePath = $request->file('pfp')->store('pfps', 'public');

        $user->pfp = 'storage/' . $filePath;
        $user->save();

        return Redirect::route('profile.edit')->with('status', 'Profile Picture Updated!');
    }

    public function overview($user_id){ //check profile of someone

        $OVuser = User::where('id', $user_id)->firstorFail();

        if($OVuser->id == auth()->id()){
            return redirect(route('profile.edit'));
        }

        $commonProjects = $OVuser->projects()
                                ->whereHas('users', function ($q) {
                                    $q->where('user_id', auth()->id());
                                })->get();

        return view('pages.profile.overview', [
            'OVuser' => $OVuser,
            'commonProjects' => $commonProjects,
        ]);
    }
}