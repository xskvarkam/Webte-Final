<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\HistoryLog;

class PdfEditApiController extends Controller
{
    public function edit(Request $request)
    {
        $request->validate([
            'pdf' => 'required|file|mimes:pdf',
            'title' => 'required|string',
            'author' => 'nullable|string',
        ]);

        $user = $request->user(); // from Sanctum token
        $ip = $request->ip();
        $location = Http::get("http://ip-api.com/json/{$ip}")->json();

        // Log API usage
        HistoryLog::create([
            'user_id' => $user->id,
            'action' => 'edit',
            'location_state' => $location['country'] ?? null,
            'location_city' => $location['city'] ?? null,
            'used_from' => 'backend',
        ]);

        // Handle file
        $filename = 'edit_' . time() . '.pdf';
        $request->file('pdf')->storeAs('temp', $filename);
        $inputPath = storage_path("app/private/temp/{$filename}");
        $outputPath = storage_path("app/public/edited_" . time() . ".pdf");

        $title = $request->input('title');
        $author = $request->input('author', '');

        $python = base_path('venv/bin/python');
        $script = base_path('scripts/edit_metadata.py');

        $cmd = "$python $script \"$inputPath\" \"$outputPath\" \"$title\" \"$author\"";
        exec($cmd, $outputLines, $resultCode);

        \Log::info("API Edit CMD: $cmd");
        \Log::info("Result: $resultCode");

        if ($resultCode !== 0 || !file_exists($outputPath)) {
            return response()->json(['message' => 'Failed to edit PDF.'], 500);
        }

        return response()->download($outputPath, 'edited.pdf', [
            'Content-Type' => 'application/pdf',
        ])->deleteFileAfterSend(true);
    }
}
