<?php

namespace App\Http\Controllers;

use App\Models\Inbox;
use App\Models\User;
use App\Models\Project;
use App\Models\Task;
use App\Models\ProjectsUser;
use App\Models\ProjectsTask;

class InboxController extends Controller
{
    public function inbox(){

        $inbox = Inbox::where('receiver_id', auth()->id())
                    ->orderByDesc('created_at')
                    ->with('user')
                    ->get();

        return view('pages.inbox', [
            'inbox' => $inbox,
            'opened_noti' => null,
        ]);
    }

    public function open($id){

        $inbox = Inbox::where('receiver_id', auth()->id())
                    ->orderByDesc('created_at')
                    ->with('user')
                    ->get();

        $opened_noti = Inbox::where('id', $id)->with('user')->first();

        return view('pages.inbox', [
            'inbox' => $inbox,
            'opened_noti' => $opened_noti,
        ]);
    }

    public function markRead($id){

        Inbox::where('id', $id)
        ->update(['is_read' => true]);

        return redirect(route('inbox.open', $id));
    }

    public function markUnread($id){

        Inbox::where('id', $id)
        ->update(['is_read' => false]);

        return redirect(route('inbox.open', $id));
    }

    public function delete($id){

        Inbox::where('id', $id)->delete();

        return redirect(route('dashboard.inbox'));
    }

    public function markAllRead(){

        Inbox::where('receiver_id', auth()->id())
        ->update(['is_read' => true]);

        return redirect(route('dashboard.inbox'));
    }
}