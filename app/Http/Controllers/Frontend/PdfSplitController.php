<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PdfSplitController extends Controller
{
    public function index()
    {
        return view('frontend.split_pdf');
    }

    public function process(Request $request)
    {
        $request->validate([
            'pdf' => 'required|file|mimes:pdf',
            'range' => 'required|regex:/^\d+\-\d+$/'
        ]);

        $filename = 'split_' . time() . '.pdf';
        $request->file('pdf')->storeAs('temp', $filename);

        $input = storage_path('app/private/temp/' . $filename);

        $output = storage_path('app/public/split_' . time() . '.pdf');
        $range = $request->input('range');

        $python = base_path('venv/bin/python');
        $script = base_path('scripts/split_pdf.py');

        $cmd = "$python $script $input $output $range";
        exec($cmd, $outputLines, $resultCode);

        \Log::info("Running split: $cmd");
        \Log::info("Result: $resultCode");
        \Log::info("Output exists: " . file_exists($output));

        if ($resultCode !== 0 || !file_exists($output)) {
            return back()->withErrors(['Split failed.']);
        }

        return response()->download($output)->deleteFileAfterSend(true);
    }
}

