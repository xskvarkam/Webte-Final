<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HistoryLog;
use Illuminate\Support\Facades\Http;
class PdfCompressController extends Controller
{
    public function index()
    {
        return view('frontend.compress_pdf');
    }

    public function process(Request $request)
    {

        $request->validate([
            'pdf' => 'required|file|mimes:pdf'
        ]);
        $user = auth()->user();
        $ip = request()->ip();
        $location = Http::get("http://ip-api.com/json/{$ip}")->json();

        HistoryLog::create([
            'user_id' => $user->id,
            'action' => 'compress',
            'location_state' => $location['country'] ?? null,
            'location_city' => $location['city'] ?? null,
            'used_from' => request()->is('api/*') ? 'backend' : 'frontend',
        ]);
        $filename = 'compress_' . time() . '.pdf';
        $request->file('pdf')->storeAs('temp', $filename);

        $input = storage_path('app/private/temp/' . $filename);
        $output = storage_path('app/public/compressed_' . time() . '.pdf');

        $python = base_path('venv/bin/python');
        $script = base_path('scripts/compress_pdf.py');

        $cmd = "$python $script \"$input\" \"$output\"";
        exec($cmd, $outputLines, $resultCode);

        \Log::info("Running compress: $cmd");
        \Log::info("Result: $resultCode");
        \Log::info("Output exists: " . file_exists($output));

        if ($resultCode !== 0 || !file_exists($output)) {
            return back()->withErrors(['Compression failed.']);
        }

        return response()->download($output)->deleteFileAfterSend(true);
    }
}

