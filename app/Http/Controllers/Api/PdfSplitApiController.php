<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\HistoryLog;

class PdfSplitApiController extends Controller
{
    public function split(Request $request)
    {
        $request->validate([
            'pdf' => 'required|file|mimes:pdf',
            'range' => 'required|regex:/^\d+\-\d+$/'
        ]);

        $user = $request->user();
        $ip = $request->ip();
        $location = Http::get("http://ip-api.com/json/{$ip}")->json();

        HistoryLog::create([
            'user_id' => $user->id,
            'action' => 'split',
            'location_state' => $location['country'] ?? null,
            'location_city' => $location['city'] ?? null,
            'used_from' => 'backend',
        ]);

        $filename = 'split_' . time() . '.pdf';
        $request->file('pdf')->storeAs('temp', $filename);

        $input = storage_path('app/private/temp/' . $filename);
        $output = storage_path('app/public/split_' . time() . '.pdf');
        $range = escapeshellarg($request->input('range'));

        $python = base_path('venv/bin/python');
        $script = base_path('scripts/split_pdf.py');

        $cmd = "$python $script \"$input\" \"$output\" $range";
        exec($cmd, $outputLines, $resultCode);

        \Log::info("API Split CMD: $cmd");
        \Log::info("Result: $resultCode");

        if ($resultCode !== 0 || !file_exists($output)) {
            return response()->json(['message' => 'Split failed.'], 500);
        }

        return response()->download($output, 'split.pdf', [
            'Content-Type' => 'application/pdf',
        ])->deleteFileAfterSend(true);
    }
}
