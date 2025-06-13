<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\User;
use App\Models\Project;
use App\Models\Task;
use App\Models\ProjectsUser;
use App\Models\ProjectsTask;

class NotificationsController extends Controller
{
    public function notifications(){

        $notifications = Notification::where('receiver_id', auth()->id())
                    ->orderBy('created_at')
                    ->with('user')
                    ->get();

        return view('pages.notifications', [
            'notifications' => $notifications,
            'opened_noti' => null,
        ]);
    }

    public function open($id){

        $notifications = Notification::where('receiver_id', auth()->id())
                    ->with('user')
                    ->get();

        $opened_noti = Notification::where('id', $id)->with('user')->first();

        return view('pages.notifications', [
            'notifications' => $notifications,
            'opened_noti' => $opened_noti,
        ]);
    }

    public function markRead($id){

        Notification::where('id', $id)
        ->update(['is_read' => true]);

        return redirect(route('notification.open', $id));
    }

    public function markUnread($id){

        Notification::where('id', $id)
        ->update(['is_read' => false]);

        return redirect(route('notification.open', $id));
    }

    public function delete($id){

        Notification::where('id', $id)->delete();

        return redirect(route('dashboard.notifications'));
    }

    public function markAllRead(){

        Notification::where('receiver_id', auth()->id())
        ->update(['is_read' => true]);

        return redirect(route('dashboard.notifications'));
    }
}