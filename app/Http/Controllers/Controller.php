<?php

namespace App\Http\Controllers;

use App\Models\Project;

abstract class Controller
{
    //to not duplicate code
    protected function permissionsCheck(int $id, bool $checkArchived = true, array $allowedUserTypes = [1, 2]): Project
    {
        $query = Project::where('id', $id)->with(['users', 'tasks']);

        if ($checkArchived) {
            $query->where('archived', false);
        }

        $project = $query->firstOrFail();

        $user = $project->users()
            ->where('users.id', auth()->id())
            ->wherePivot('active', true)
            ->firstOrFail();

        if (!in_array($user->pivot->user_type, $allowedUserTypes)) {
            abort(403);
        }

        return $project;
    }
}
