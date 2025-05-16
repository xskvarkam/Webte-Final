<?php

namespace App\Http\Controllers\Frontend;

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\HistoryLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
class PdfEditController extends Controller
{
    public function index()
    {
        return view('frontend.edit_pdf');
    }

    public function process(Request $request)
    {

        $request->validate([
            'pdf' => 'required|file|mimes:pdf',
            'title' => 'required|string',
            'author' => 'nullable|string',
        ]);
        $user = auth()->user();
        $ip = request()->ip();
        $location = Http::get("http://ip-api.com/json/{$ip}")->json();

        HistoryLog::create([
            'user_id' => $user->id,
            'action' => 'edit',
            'location_state' => $location['country'] ?? null,
            'location_city' => $location['city'] ?? null,
            'used_from' => request()->is('api/*') ? 'backend' : 'frontend',
        ]);
        $filename = 'edit_' . time() . '.pdf';
        $request->file('pdf')->storeAs('temp', $filename);

        $inputPath = storage_path('app/private/temp/' . $filename);
        $outputPath = storage_path('app/public/edited_' . time() . '.pdf');

        $title = $request->input('title');
        $author = $request->input('author', '');

        $python = base_path('venv/bin/python');
        $script = base_path('scripts/edit_metadata.py');

        // Escape and quote everything correctly
        $cmd = "$python $script \"$inputPath\" \"$outputPath\" \"$title\" \"$author\"";

        exec($cmd, $outputLines, $resultCode);

        \Log::info("Edit metadata command: $cmd");
        \Log::info("Result: $resultCode");
        \Log::info("Output exists: " . file_exists($outputPath));

        if ($resultCode !== 0 || !file_exists($outputPath)) {
            return back()->withErrors(['Failed to edit PDF.']);
        }

        return response()->download($outputPath)->deleteFileAfterSend(true);
    }
}

