<?php

namespace App\Http\Controllers\Admin;

use App\Models\Task;
use App\Models\User;
use App\Models\Project;
use Illuminate\View\View;
use App\Models\ProjectUser;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\ProfileController;

class AdminUserController extends Controller {

    public function index(): View{
        
        return view('admin.users.index', [
            'users' => User::get()->all(),
        ]);
    }

    public function create(): View{
        return view('admin.users.form');
    }

    public function store(Request $request): RedirectResponse{
        
        $request->validate([
            'pfp' => 'nullable|image|mimes:jpeg,png,jpg|dimensions:ratio=1/1|max:2048',
            'name' => 'required|string|max:50',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
            'email_verified_at' => 'required|boolean',
            'is_admin' => 'required|boolean',
        ]);

        
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'email_verified_at' => $request->email_verified_at == 1 ? now() : null,
            'is_admin' => $request->is_admin == 1 ? true : false,
        ]);
        
        if($request->pfp == null){
            $user->pfp = 'Images/Pfp/pfp_default.png';
            $user->save();
        }else{
            $filePath = $request->file('pfp')->store('pfps', 'public');

            $user->pfp = 'storage/' . $filePath;
            $user->save();
        }
        
        return redirect()->route('admin.users.index')->with('status', 'User added with success!');
    }

    public function edit(int $id): View{
        return view('admin.users.form', [
            'user' => User::findOrFail($id),
        ]);
    }

    public function update(int $id, Request $request): RedirectResponse{

        $user = User::findOrFail($id);

        $request->validate([
            'pfp' => 'nullable|image|mimes:jpeg,png,jpg,gif|dimensions:ratio=1/1|max:2048',
            'name' => 'required|string|max:50',
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($user->id),
            ],
            'password' => 'nullable|string|min:8',
            'email_verified_at' => 'required|boolean',
            'is_admin' => 'required|boolean',
        ]);
        
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'email_verified_at' => $request->email_verified_at == 1 ? now() : null,
            'is_admin' => $request->is_admin == 1 ? true : false,
        ]);

        if($request->password){
            $user->update(['password' => $request->password]);
        }

        if ($request->hasFile('pfp')) {
            if ($user->pfp && $user->pfp !== 'Images/Pfp/pfp_default.png' && file_exists(public_path($user->pfp))) {
                unlink(public_path($user->pfp));
            }
            $filePath = $request->file('pfp')->store('pfps', 'public');
            $user->pfp = 'storage/' . $filePath;
        }

        $user->save();

        return redirect()->route('admin.users.index')->with('status', 'User updated with success!');
    }

    public function destroy($id): RedirectResponse{
        
        $user = User::findOrFail($id);

        if(auth()->id() == $user->id){
            $profileController = new ProfileController();
            return $profileController->destroy(request());
        }

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

        return redirect()->route('admin.users.index')->with('status', 'User deleted with success!');
    }
}