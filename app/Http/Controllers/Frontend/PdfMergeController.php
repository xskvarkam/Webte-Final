<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PdfMergeController extends Controller
{
    public function index()
    {
        return view('frontend.merge_pdf');
    }

    public function process(Request $request)
    {
        $request->validate([
            'pdf1' => 'required|file|mimes:pdf',
            'pdf2' => 'required|file|mimes:pdf'
        ]);

        // Create unique filenames
        $filename1 = 'input1_' . time() . '.pdf';
        $filename2 = 'input2_' . time() . '.pdf';

        // Store uploaded files in app/private/temp/
        $request->file('pdf1')->storeAs('/temp', $filename1);
        $request->file('pdf2')->storeAs('/temp', $filename2);

        // Build full paths
        $input1 = storage_path('app/private/temp/' . $filename1);
        $input2 = storage_path('app/private/temp/' . $filename2);
        $output = storage_path('app/public/merged_' . time() . '.pdf');

        // Paths to virtualenv and script
        $python = base_path('venv/bin/python');
        $script = base_path('scripts/merge_pdf.py');

        // Build and run command
        $cmd = "$python $script $input1 $input2 $output";
        exec($cmd, $outputLines, $resultCode);

        \Log::info("Running merge: $cmd");
        \Log::info("Result: $resultCode");
        \Log::info("Output exists: " . file_exists($output));

        if ($resultCode !== 0 || !file_exists($output)) {
            return back()->withErrors(['Merge failed.']);
        }

        return response()->download($output)->deleteFileAfterSend(true);
    }

}
