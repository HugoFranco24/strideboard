<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Project;
use App\Models\Task;
use App\Models\ProjectUser;
use App\Models\ProjectTask;
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

        if($request->input('start_date')){
            Task::findOrFail($task_id)->update([
                'start' => Carbon::parse($request->input('start_date'))->setTimezone('UTC'),
                'end' => Carbon::parse($request->input('end_date'))->setTimezone('UTC'),
            ]);
        }else{
            Task::findOrFail($task_id)->update([
                'end' => Carbon::parse($request->input('end_date'))->setTimezone('UTC'),
            ]);
        }

        return response()->json(['message' => 'Task Updated.']);
    }
}
