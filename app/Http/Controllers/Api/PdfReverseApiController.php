<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\HistoryLog;

class PdfReverseApiController extends Controller
{
    public function reverse(Request $request)
    {
        $request->validate([
            'pdf' => 'required|file|mimes:pdf',
        ]);

        $user = $request->user();
        $ip = $request->ip();
        $location = Http::get("http://ip-api.com/json/{$ip}")->json();

        HistoryLog::create([
            'user_id' => $user->id,
            'action' => 'reverse',
            'location_state' => $location['country'] ?? null,
            'location_city' => $location['city'] ?? null,
            'used_from' => 'backend',
        ]);

        $filename = 'reverse_' . time() . '.pdf';
        $request->file('pdf')->storeAs('temp', $filename);

        $input = storage_path('app/private/temp/' . $filename);
        $output = storage_path('app/public/reversed_' . time() . '.pdf');

        $python = base_path('venv/bin/python');
        $script = base_path('scripts/reverse_pdf.py');

        $cmd = "$python \"$script\" \"$input\" \"$output\"";
        exec($cmd, $outputLines, $resultCode);

        \Log::info("API Reverse CMD: $cmd");
        \Log::info("Result: $resultCode");

        if ($resultCode !== 0 || !file_exists($output)) {
            return response()->json(['message' => 'Failed to reverse PDF.'], 500);
        }

        return response()->download($output, 'reversed.pdf', [
            'Content-Type' => 'application/pdf',
        ])->deleteFileAfterSend(true);    }
}
