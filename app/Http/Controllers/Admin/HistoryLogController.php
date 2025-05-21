<?php

// app/Http/Controllers/Admin/HistoryLogController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HistoryLog;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Http;
class HistoryLogController extends Controller
{
    public function index()
    {
        $logs = HistoryLog::with('user')->latest()->paginate(20);
        return view('admin.history.index', compact('logs'));
    }

    public function export()
    {
        $logs = HistoryLog::latest()->get();
        $user = auth()->user();
        $ip = request()->ip();
        $location = Http::get("http://ip-api.com/json/{$ip}")->json();

        HistoryLog::create([
            'user_id' => $user->id,
            'action' => 'export history',
            'location_state' => $location['country'] ?? null,
            'location_city' => $location['city'] ?? null,
            'used_from' => request()->is('api/*') ? 'backend' : 'frontend',
        ]);
        $csv = "User ID,Action,Source,City,State,Date\n";
        foreach ($logs as $log) {
            $csv .= "{$log->user_id},{$log->action},{$log->used_from},{$log->location_city},{$log->location_state},{$log->created_at}\n";
        }

        return Response::make($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=history_logs.csv',
        ]);
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
