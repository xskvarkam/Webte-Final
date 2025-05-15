<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PdfController extends Controller
{
    public function index()
    {
        return view('frontend.pdf_tools');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'pdf' => 'required|file|mimes:pdf|max:10240', // 10MB max
        ]);

        $path = $request->file('pdf')->store('uploads', 'public');

        return back()->with('success', 'PDF uploaded successfully!')->with('file', $path);
    }
}

