<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PdfWatermarkController extends Controller
{
    public function index()
    {
        return view('frontend.watermark_pdf');
    }

    public function process(Request $request)
    {
        $request->validate([
            'pdf' => 'required|file|mimes:pdf',
            'text' => 'required|string|max:100'
        ]);

        $filename = 'watermark_' . time() . '.pdf';
        $request->file('pdf')->storeAs('temp', $filename);

        $input = storage_path('app/private/temp/' . $filename);
        $output = storage_path('app/public/watermarked_' . time() . '.pdf');
        $text = $request->input('text');

        $python = base_path('venv/bin/python');
        $script = base_path('scripts/watermark_pdf.py');

        $cmd = "$python $script \"$input\" \"$output\" \"$text\"";
        exec($cmd, $outputLines, $resultCode);

        \Log::info("Running watermark: $cmd");
        \Log::info("Result: $resultCode");
        \Log::info("Output exists: " . file_exists($output));

        if ($resultCode !== 0 || !file_exists($output)) {
            return back()->withErrors(['Watermarking failed.']);
        }

        return response()->download($output)->deleteFileAfterSend(true);
    }
}

