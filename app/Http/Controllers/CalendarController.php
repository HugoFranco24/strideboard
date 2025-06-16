<?php

namespace App\Http\Controllers;

use App\Models\Inbox;
use App\Models\ProjectUser;
use App\Models\Task;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CalendarController extends Controller {

    public function calendar(){
        return view('pages.calendar');
    }

    public function getTasks()
    {
        $tasks = Task::leftJoin('projects', 'tasks.project_id', '=', 'projects.id')
                        ->where('user_id', auth()->id())
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

    public function updateDate($task_id, Request $request){

        $task = Task::findOrFail($task_id);

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

        $receivers = ProjectUser::where('project_id', $task->project_id)
            ->where('user_id', $task->user_id)
            ->whereIn('user_type', [1, 2])
            ->whereNot('user_id', auth()->id())
            ->get();

        foreach($receivers as $reciever){
            Inbox::create([
                'receiver_id' => $reciever->user_id,
                'actor_id' => auth()->id(),
                'type' => 'updated_task',
                'task_name' => $task->name,
                'reference_id' => $task->id,
            ]);
        }

        return response()->json(['message' => 'Task Updated.']);
    }
}
