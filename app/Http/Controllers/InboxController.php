<?php

namespace App\Http\Controllers;

use App\Models\Inbox;
use App\Models\User;
use App\Models\Project;
use App\Models\Task;
use App\Models\ProjectsUser;
use App\Models\ProjectsTask;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Services\InboxFilter;

class InboxController extends Controller
{
    public function inbox(Request $request){

        $inbox = InboxFilter::filterQuery($request)->get();

        return view('pages.inbox', [
            'inbox' => $inbox,
            'opened_noti' => null,
        ]);
    }

    public function open($id, Request $request){

        $inbox = InboxFilter::filterQuery($request)->get();

        $opened_noti = Inbox::where('id', $id)->with('user')->first();

        return view('pages.inbox', [
            'inbox' => $inbox,
            'opened_noti' => $opened_noti,
        ]);
    }

    public function markRead(Request $request, $id)
    {
        Inbox::where('id', $id)->update(['is_read' => true]);

        return redirect()->route('inbox.open', array_merge(['id' => $id], $request->query()));
    }

    public function markUnread(Request $request, $id)
    {
        Inbox::where('id', $id)->update(['is_read' => false]);

        return redirect()->route('inbox.open', array_merge(['id' => $id], $request->query()));
    }

    public function delete(Request $request, $id)
    {
        Inbox::where('id', $id)->delete();

        return redirect()->route('dashboard.inbox', $request->query());
    }

    public function markAllRead(Request $request){

        $inbox = InboxFilter::filterQuery($request);

        $inbox->update(['is_read' => true]);

        return redirect()->route('dashboard.inbox', $request->query());
    }
}