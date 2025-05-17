<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\HistoryLog;

class PdfSignApiController extends Controller
{
    public function sign(Request $request)
    {
        $request->validate([
            'pdf' => 'required|file|mimes:pdf',
            'signature' => 'required|string|max:100'
        ]);

        $user = $request->user();
        $ip = $request->ip();
        $location = Http::get("http://ip-api.com/json/{$ip}")->json();

        HistoryLog::create([
            'user_id' => $user->id,
            'action' => 'sign',
            'location_state' => $location['country'] ?? null,
            'location_city' => $location['city'] ?? null,
            'used_from' => 'backend',
        ]);

        $filename = 'sign_' . time() . '.pdf';
        $request->file('pdf')->storeAs('temp', $filename);

        $input = storage_path('app/private/temp/' . $filename);
        $output = storage_path('app/public/signed_' . time() . '.pdf');
        $signature = escapeshellarg($request->input('signature'));

        $python = base_path('venv/bin/python');
        $script = base_path('scripts/sign_pdf.py');

        $cmd = "$python \"$script\" \"$input\" \"$output\" $signature";
        exec($cmd, $outputLines, $resultCode);

        \Log::info("API Sign CMD: $cmd");
        \Log::info("Result: $resultCode");

        if ($resultCode !== 0 || !file_exists($output)) {
            return response()->json(['message' => 'Failed to sign PDF.'], 500);
        }

        return response()->download($output, 'signed.pdf', [
            'Content-Type' => 'application/pdf',
        ])->deleteFileAfterSend(true);
    }
}
