<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\HistoryLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PdfDeleteController extends Controller
{
    public function index()
    {
        return view('frontend.delete_pdf');
    }

    public function process(Request $request)
    {

        $request->validate([
            'pdf' => 'required|file|mimes:pdf',
            'pages' => 'required|string'
        ]);
        $user = auth()->user();
        $ip = request()->ip();
        $location = Http::get("http://ip-api.com/json/{$ip}")->json();

        HistoryLog::create([
            'user_id' => $user->id,
            'action' => 'delete',
            'location_state' => $location['country'] ?? null,
            'location_city' => $location['city'] ?? null,
            'used_from' => request()->is('api/*') ? 'backend' : 'frontend',
        ]);
        $filename = 'delete_' . time() . '.pdf';
        $request->file('pdf')->storeAs('temp', $filename);

        $input = storage_path('app/private/temp/' . $filename);
        $output = storage_path('app/public/deleted_' . time() . '.pdf');
        $pages = $request->input('pages');

        $python = base_path('venv/bin/python');
        $script = base_path('scripts/delete_pages.py');

        $cmd = "$python $script $input $output $pages";
        exec($cmd, $outputLines, $resultCode);

        \Log::info("Running delete: $cmd");
        \Log::info("Result: $resultCode");
        \Log::info("Output exists: " . file_exists($output));

        if ($resultCode !== 0 || !file_exists($output)) {
            return back()->withErrors(['Delete failed.']);
        }

        return response()->download($output)->deleteFileAfterSend(true);
    }
}

