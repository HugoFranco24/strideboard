<?php

namespace App\Services;

use App\Models\Inbox;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;

class InboxFilter
{
    public static function filterQuery(Request $request): Builder
    {
        $query = Inbox::where('receiver_id', auth()->id())
                      ->orderByDesc('created_at')
                      ->with('user');

        if ($request->filled('unread') && $request->unread == true) {
            $query->where('is_read', false);
        }

        if ($request->filled('subject') && $request->subject !== 'no_filter') {
            $query->where('type', $request->subject);
        }

        if ($request->filled('date') && $request->date !== 'no_filter') {
            if ($request->date == 'today') {
                $query->whereDate('created_at', Carbon::today());
            } elseif ($request->date == 'week') {
                $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
            } else {
                $query->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]);
            }
        }

        return $query;
    }
}