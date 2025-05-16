<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\HistoryLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
class PdfRotateController extends Controller
{
    public function index()
    {
        return view('frontend.rotate_pdf');
    }

    public function process(Request $request)
    {

        $request->validate([
            'pdf' => 'required|file|mimes:pdf',
            'pages' => 'required|string',
            'angle' => 'required|in:90,180,270'
        ]);
        $user = auth()->user();
        $ip = request()->ip();
        $location = Http::get("http://ip-api.com/json/{$ip}")->json();

        HistoryLog::create([
            'user_id' => $user->id,
            'action' => 'rotate',
            'location_state' => $location['country'] ?? null,
            'location_city' => $location['city'] ?? null,
            'used_from' => request()->is('api/*') ? 'backend' : 'frontend',
        ]);
        $filename = 'rotate_' . time() . '.pdf';
        $request->file('pdf')->storeAs('temp', $filename);

        $input = storage_path('app/private/temp/' . $filename);
        $output = storage_path('app/public/rotated_' . time() . '.pdf');

        $pages = $request->input('pages');
        $angle = $request->input('angle');

        $python = base_path('venv/bin/python');
        $script = base_path('scripts/rotate_pdf.py');

        $cmd = "$python $script \"$input\" \"$output\" \"$pages\" \"$angle\"";
        exec($cmd, $outputLines, $resultCode);

        \Log::info("Running rotate: $cmd");
        \Log::info("Result: $resultCode");
        \Log::info("Output exists: " . file_exists($output));

        if ($resultCode !== 0 || !file_exists($output)) {
            return back()->withErrors(['Rotate failed.']);
        }

        return response()->download($output)->deleteFileAfterSend(true);
    }
}

