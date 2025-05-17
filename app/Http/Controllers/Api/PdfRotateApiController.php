<?php

namespace App\Http\Controllers\Api;

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\HistoryLog;

class PdfRotateApiController extends Controller
{
    public function rotate(Request $request)
    {
        $request->validate([
            'pdf' => 'required|file|mimes:pdf',
            'pages' => 'required|string',
            'angle' => 'required|in:90,180,270'
        ]);

        $user = $request->user();
        $ip = $request->ip();
        $location = Http::get("http://ip-api.com/json/{$ip}")->json();

        HistoryLog::create([
            'user_id' => $user->id,
            'action' => 'rotate',
            'location_state' => $location['country'] ?? null,
            'location_city' => $location['city'] ?? null,
            'used_from' => 'backend',
        ]);

        $filename = 'rotate_' . time() . '.pdf';
        $request->file('pdf')->storeAs('temp', $filename);

        $input = storage_path('app/private/temp/' . $filename);
        $output = storage_path('app/public/rotated_' . time() . '.pdf');

        $pages = escapeshellarg($request->input('pages'));
        $angle = escapeshellarg($request->input('angle'));

        $python = base_path('venv/bin/python');
        $script = base_path('scripts/rotate_pdf.py');

        $cmd = "$python $script \"$input\" \"$output\" $pages $angle";
        exec($cmd, $outputLines, $resultCode);

        \Log::info("API Rotate CMD: $cmd");
        \Log::info("Result: $resultCode");

        if ($resultCode !== 0 || !file_exists($output)) {
            return response()->json(['message' => 'Rotate failed.'], 500);
        }

        return response()->download($output, 'rotated.pdf', [
            'Content-Type' => 'application/pdf',
        ])->deleteFileAfterSend(true);
    }
}
