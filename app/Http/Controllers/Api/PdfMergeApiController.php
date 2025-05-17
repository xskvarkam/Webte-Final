<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\HistoryLog;

class PdfMergeApiController extends Controller
{
    public function merge(Request $request)
    {
        $request->validate([
            'pdf1' => 'required|file|mimes:pdf',
            'pdf2' => 'required|file|mimes:pdf'
        ]);

        $user = $request->user();
        $ip = $request->ip();
        $location = Http::get("http://ip-api.com/json/{$ip}")->json();

        HistoryLog::create([
            'user_id' => $user->id,
            'action' => 'merge',
            'location_state' => $location['country'] ?? null,
            'location_city' => $location['city'] ?? null,
            'used_from' => 'backend',
        ]);

        $filename1 = 'input1_' . time() . '.pdf';
        $filename2 = 'input2_' . time() . '.pdf';

        $request->file('pdf1')->storeAs('temp', $filename1);
        $request->file('pdf2')->storeAs('temp', $filename2);

        $input1 = storage_path('app/private/temp/' . $filename1);
        $input2 = storage_path('app/private/temp/' . $filename2);
        $output = storage_path('app/public/merged_' . time() . '.pdf');

        $python = base_path('venv/bin/python');
        $script = base_path('scripts/merge_pdf.py');

        $cmd = "$python $script \"$input1\" \"$input2\" \"$output\"";
        exec($cmd, $outputLines, $resultCode);

        \Log::info("API Merge CMD: $cmd");
        \Log::info("Result: $resultCode");

        if ($resultCode !== 0 || !file_exists($output)) {
            return response()->json(['message' => 'Merge failed.'], 500);
        }

        return response()->download($output, 'merged.pdf', [
            'Content-Type' => 'application/pdf',
        ])->deleteFileAfterSend(true);
    }
}
