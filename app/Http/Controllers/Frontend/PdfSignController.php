<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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

