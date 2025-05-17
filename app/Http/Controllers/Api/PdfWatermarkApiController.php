<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\HistoryLog;

class PdfWatermarkApiController extends Controller
{
    public function watermark(Request $request)
    {
        $request->validate([
            'pdf' => 'required|file|mimes:pdf',
            'text' => 'required|string|max:100'
        ]);

        $user = $request->user();
        $ip = $request->ip();
        $location = Http::get("http://ip-api.com/json/{$ip}")->json();

        HistoryLog::create([
            'user_id' => $user->id,
            'action' => 'watermark',
            'location_state' => $location['country'] ?? null,
            'location_city' => $location['city'] ?? null,
            'used_from' => 'backend',
        ]);

        $filename = 'watermark_' . time() . '.pdf';
        $request->file('pdf')->storeAs('temp', $filename);

        $input = storage_path('app/private/temp/' . $filename);
        $output = storage_path('app/public/watermarked_' . time() . '.pdf');
        $text = escapeshellarg($request->input('text'));

        $python = base_path('venv/bin/python');
        $script = base_path('scripts/watermark_pdf.py');

        $cmd = "$python $script \"$input\" \"$output\" $text";
        exec($cmd, $outputLines, $resultCode);

        \Log::info("API Watermark CMD: $cmd");
        \Log::info("Result: $resultCode");

        if ($resultCode !== 0 || !file_exists($output)) {
            return response()->json(['message' => 'Watermarking failed.'], 500);
        }

        return response()->download($output, 'watermarked.pdf', [
            'Content-Type' => 'application/pdf',
        ])->deleteFileAfterSend(true);

    }
}
