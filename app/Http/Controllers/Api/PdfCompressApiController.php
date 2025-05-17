<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\HistoryLog;

class PdfCompressApiController extends Controller
{
    public function compress(Request $request)
    {
        $request->validate([
            'pdf' => 'required|file|mimes:pdf'
        ]);

        $user = $request->user(); // comes from Sanctum auth
        $ip = $request->ip();
        $location = Http::get("http://ip-api.com/json/{$ip}")->json();

        HistoryLog::create([
            'user_id' => $user->id,
            'action' => 'compress',
            'location_state' => $location['country'] ?? null,
            'location_city' => $location['city'] ?? null,
            'used_from' => 'backend',
        ]);

        $filename = 'compress_' . time() . '.pdf';
        $request->file('pdf')->storeAs('temp', $filename);

        $input = storage_path('app/private/temp/' . $filename);
        $output = storage_path('app/public/compressed_' . time() . '.pdf');

        $python = base_path('venv/bin/python');
        $script = base_path('scripts/compress_pdf.py');

        $cmd = "$python $script \"$input\" \"$output\"";
        exec($cmd, $outputLines, $resultCode);

        \Log::info("API Compress CMD: $cmd");
        \Log::info("Result: $resultCode");

        if ($resultCode !== 0 || !file_exists($output)) {
            return response()->json(['message' => 'Compression failed.'], 500);
        }

        return response()->download($output, 'compressed.pdf', [
            'Content-Type' => 'application/pdf',
        ])->deleteFileAfterSend(true);
    }
}
