<?php

namespace App\Http\Controllers\Api;

use App\Models\HistoryLog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class HistoryLogController extends Controller
{
    public function store(Request $request)
    {
        HistoryLog::create([
            'user_id' => auth()->id(),
            'action' => $request->action,
            'location_state' => $request->location_state,
            'location_city' => $request->location_city,
            'used_from' => $request->used_from ?? 'frontend',
        ]);

        return response()->json(['status' => 'ok']);
    }
    public function clear()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        \App\Models\HistoryLog::truncate(); // or ->delete() if you want to fire model events
        return redirect()->route('admin.history.index')->with('success', 'History cleared.');
    }
}
