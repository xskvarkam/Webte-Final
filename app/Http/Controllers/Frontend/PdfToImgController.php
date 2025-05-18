<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HistoryLog;
use Illuminate\Support\Facades\Http;

class PdfToImgController extends Controller
{
    public function index()
    {
        return view('frontend.pdf_to_img');
    }

    public function process(Request $request)
    {
        $request->validate([
            'pdf' => 'required|file|mimes:pdf'
        ]);

        $user = auth()->user();
        $ip = request()->ip();
        $location = Http::get("http://ip-api.com/json/{$ip}")->json();

        HistoryLog::create([
            'user_id' => $user->id,
            'action' => 'pdf_to_img',
            'location_state' => $location['country'] ?? null,
            'location_city' => $location['city'] ?? null,
            'used_from' => request()->is('api/*') ? 'backend' : 'frontend',
        ]);

        $filename = 'images_' . time() . '.pdf';
        $request->file('pdf')->storeAs('temp', $filename);

        $input = storage_path('app/private/temp/' . $filename);
        $outputName = 'pdf_images_' . time() . '.zip';
        $output = storage_path('app/public/' . $outputName);

        $python = base_path('venv/bin/python');
        $script = base_path('scripts/pdf_to_img.py');

        $cmd = "$python $script \"$input\" \"$output\"";
        exec($cmd, $outputLines, $resultCode);

        \Log::info("Running pdf_to_img: $cmd");
        \Log::info("Result: $resultCode");
        \Log::info("Output exists: " . file_exists($output));

        if ($resultCode !== 0 || !file_exists($output)) {
            return back()->withErrors(['Conversion failed']);
        }

        return response()->download($output)->deleteFileAfterSend(true);
    }
}
