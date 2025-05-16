<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\HistoryLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
class PdfSignController extends Controller
{
    public function index()
    {
        return view('frontend.sign_pdf');
    }

    public function process(Request $request)
    {

        $request->validate([
            'pdf' => 'required|file|mimes:pdf',
            'signature' => 'required|string|max:100'
        ]);
        $user = auth()->user();
        $ip = request()->ip();
        $location = Http::get("http://ip-api.com/json/{$ip}")->json();

        HistoryLog::create([
            'user_id' => $user->id,
            'action' => 'sign',
            'location_state' => $location['country'] ?? null,
            'location_city' => $location['city'] ?? null,
            'used_from' => request()->is('api/*') ? 'backend' : 'frontend',
        ]);
        $filename = 'sign_' . time() . '.pdf';
        $request->file('pdf')->storeAs('temp', $filename);

        $input = storage_path('app/private/temp/' . $filename);
        $output = storage_path('app/public/signed_' . time() . '.pdf');
        $signature = $request->input('signature');

        $python = base_path('venv/bin/python');
        $script = base_path('scripts/sign_pdf.py');

        $cmd = "$python \"$script\" \"$input\" \"$output\" \"$signature\"";
        exec($cmd, $outputLines, $resultCode);

        \Log::info("Running sign: $cmd");
        \Log::info("Result: $resultCode");
        \Log::info("Output exists: " . file_exists($output));

        if ($resultCode !== 0 || !file_exists($output)) {
            return back()->withErrors(['Failed to sign PDF.']);
        }

        return response()->download($output)->deleteFileAfterSend(true);
    }
}

