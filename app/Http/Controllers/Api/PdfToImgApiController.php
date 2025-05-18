<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\HistoryLog;

class PdfToImgApiController extends Controller
{
    public function convert(Request $request)
    {
        $request->validate([
            'pdf' => 'required|file|mimes:pdf'
        ]);

        $user = $request->user(); // cez Sanctum
        $ip = $request->ip();
        $location = Http::get("http://ip-api.com/json/{$ip}")->json();

        HistoryLog::create([
            'user_id' => $user->id,
            'action' => 'pdf_to_images',
            'location_state' => $location['country'] ?? null,
            'location_city' => $location['city'] ?? null,
            'used_from' => 'backend',
        ]);

        $filename = 'images_' . time() . '.pdf';
        $request->file('pdf')->storeAs('temp', $filename);

        $input = storage_path('app/private/temp/' . $filename);
        $output = storage_path('app/public/pdf_images_' . time() . '.zip');

        $python = base_path('venv/bin/python');
        $script = base_path('scripts/pdf_to_img.py');

        $cmd = "$python $script \"$input\" \"$output\"";
        exec($cmd, $outputLines, $resultCode);

        \Log::info("API PDF to Images CMD: $cmd");
        \Log::info("Result: $resultCode");

        if ($resultCode !== 0 || !file_exists($output)) {
            return response()->json(['message' => 'Conversion failed.'], 500);
        }

        return response()->download($output, 'pdf_images.zip', [
            'Content-Type' => 'application/zip',
        ])->deleteFileAfterSend(true);
    }
}
