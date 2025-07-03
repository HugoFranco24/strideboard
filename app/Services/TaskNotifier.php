<?php

namespace App\Services;

use App\Models\Project;
use App\Models\Task;
use App\Models\ProjectUser;
use App\Models\Inbox;

class TaskNotifier
{
    protected $notifiableUsers = [];

    public function __construct(Project $project, Task $task)
    {
        $this->notifiableUsers = ProjectUser::where('project_id', $project->id)
            ->where('active', true)
            ->where(function ($query) use ($task) {
                $query->whereIn('user_type', [1, 2])
                    ->orWhere('user_id', $task->user_id);
            })
            ->where('user_id', '!=', auth()->id())
            ->get();
    }

    public function notify($type, Task $task, Project $project): void
    {   
        foreach ($this->notifiableUsers as $receiver) {
            Inbox::create([
                'receiver_id'   => $receiver->user_id,
                'actor_id'      => $type == 'late_task' ? 0 : auth()->id(),
                'type'          => $type,
                'task_name'     => $task->name,
                'project_name'  => $project->name,
                'reference_id'  => $task->id,
            ]);
        }
    }
}