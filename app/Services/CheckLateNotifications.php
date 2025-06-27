<?php

namespace App\Services;

use App\Models\Task;
use App\Models\Project;
use App\Models\ProjectUser;
use App\Models\Inbox;
use Carbon\Carbon;
use App\Services\TaskNotifier;

class CheckLateNotifications
{
    public function handle()
    {
        $today = Carbon::today();

        //region Check Late Tasks
        $lateTasks = Task::where('end', '<', $today)
            ->where('late_notified', false)
            ->get();
        
        foreach ($lateTasks as $task) {
            $project = Project::findOrFail($task->project_id);
            $notifier = new TaskNotifier($project, $task);
            $notifier->notify('late_task', $task, $project);

            $task->late_notified = true;
            $task->save();
        }
        //end Check Late Tasks

        //region Check Late Projects
        $lateProjects = Project::where('due_date', '<', $today)
            ->where('late_notified', false)
            ->get();

        foreach ($lateProjects as $project) {
            $receivers = ProjectUser::where('project_id', $project->id)
                ->where('active', true)
                ->get();

            foreach($receivers as $reciever){
                Inbox::create([
                    'receiver_id' => $reciever->user_id,
                    'actor_id' => 0,
                    'type' => 'late_project',
                    'project_name' => $project->name,
                    'reference_id' => $project->id,
                ]);
            }            

            $project->late_notified = true;
            $project->save();
        }
        //end Check Late Projects
    }
}