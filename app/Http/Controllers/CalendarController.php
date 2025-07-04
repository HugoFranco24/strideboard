<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Task;
use App\Models\Project;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Services\TaskNotifier;
use Illuminate\Http\JsonResponse;

class CalendarController extends Controller {

    public function calendar(): View{
        return view('pages.calendar');
    }

    public function getTasks(): JsonResponse
    {
        $tasks = Task::leftJoin('projects', 'tasks.project_id', '=', 'projects.id')
                        ->where('user_id', auth()->id())
                        ->where('projects.archived', false)
                        ->select(
                            'tasks.id as id',
                            'tasks.name as title',
                            'tasks.start as start', 
                            'tasks.end as end', 
                            'projects.color as backgroundColor',
                        )
                        ->get();

        return response()->json($tasks);
    }

    public function updateDate(int $task_id, Request $request): JsonResponse{

        $task = Task::findOrFail($task_id);

        $project = Project::where('id', $task->project_id)->firstOrFail()->load('users', 'tasks');

        if(!$project->archived){
            if($request->input('start_date')){
                $task->update([
                    'start' => Carbon::parse($request->input('start_date'))->setTimezone('UTC'),
                    'end' => Carbon::parse($request->input('end_date'))->setTimezone('UTC'),
                ]);
            }else{
                $task->update([
                    'end' => Carbon::parse($request->input('end_date'))->setTimezone('UTC'),
                ]);
            }

            //notify users with service
            $notifier = new TaskNotifier($project, $task);
            $notifier->notify( 'updated_task', $task, $project);
        }

        return response()->json(['message' => 'Task Updated.']);
    }
}
