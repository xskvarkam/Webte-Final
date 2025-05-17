<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\HistoryLog;

class PdfExtractApiController extends Controller
{
    public function extract(Request $request)
    {
        $request->validate([
            'pdf' => 'required|file|mimes:pdf',
        ]);

        $user = $request->user();
        $ip = $request->ip();
        $location = Http::get("http://ip-api.com/json/{$ip}")->json();

        HistoryLog::create([
            'user_id' => $user->id,
            'action' => 'extract',
            'location_state' => $location['country'] ?? null,
            'location_city' => $location['city'] ?? null,
            'used_from' => 'backend',
        ]);

        $filename = 'extract_' . time() . '.pdf';
        $request->file('pdf')->storeAs('temp', $filename);

        $input = storage_path('app/private/temp/' . $filename);
        $output = storage_path('app/private/temp/output_' . time() . '.txt');

        $python = base_path('venv/bin/python');
        $script = base_path('scripts/extract_text.py');

        $cmd = "$python $script \"$input\" \"$output\"";
        exec($cmd, $outputLines, $resultCode);

        \Log::info("API Extract CMD: $cmd");
        \Log::info("Result: $resultCode");

        if ($resultCode !== 0 || !file_exists($output)) {
            return response()->json(['message' => 'Text extraction failed.'], 500);
        }

        $text = file_get_contents($output);

        return response()->download($output, 'extracted.pdf', [
            'Content-Type' => 'application/pdf',
        ])->deleteFileAfterSend(true);
    }
}
