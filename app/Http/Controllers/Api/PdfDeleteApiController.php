<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\HistoryLog;

class PdfDeleteApiController extends Controller
{
    public function delete(Request $request)
    {
        $request->validate([
            'pdf' => 'required|file|mimes:pdf',
            'pages' => 'required|string'
        ]);

        $user = $request->user();
        $ip = $request->ip();
        $location = Http::get("http://ip-api.com/json/{$ip}")->json();

        HistoryLog::create([
            'user_id' => $user->id,
            'action' => 'delete',
            'location_state' => $location['country'] ?? null,
            'location_city' => $location['city'] ?? null,
            'used_from' => 'backend',
        ]);

        $filename = 'delete_' . time() . '.pdf';
        $request->file('pdf')->storeAs('temp', $filename);

        $input = storage_path('app/private/temp/' . $filename);
        $output = storage_path('app/public/deleted_' . time() . '.pdf');
        $pages = escapeshellarg($request->input('pages'));

        $python = base_path('venv/bin/python');
        $script = base_path('scripts/delete_pages.py');

        $cmd = "$python $script \"$input\" \"$output\" $pages";
        exec($cmd, $outputLines, $resultCode);

        \Log::info("API Delete CMD: $cmd");
        \Log::info("Result: $resultCode");

        if ($resultCode !== 0 || !file_exists($output)) {
            return response()->json(['message' => 'Delete failed.'], 500);
        }

        return response()->download($output, 'deleted.pdf', [
            'Content-Type' => 'application/pdf',
        ])->deleteFileAfterSend(true);
    }
}
