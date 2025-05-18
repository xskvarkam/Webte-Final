<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\HistoryLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PdfFromImgController extends Controller
{
    public function index()
    {
        return view('frontend.pdf_from_img');
    }

    public function process(Request $request)
    {
        $request->validate([
            'images' => 'required|array|min:1',
            'images.*' => 'required|file|mimes:jpg,jpeg,png|max:10240',
        ]);

        $user = auth()->user();
        $ip = request()->ip();
        $location = Http::get("http://ip-api.com/json/{$ip}")->json();

        HistoryLog::create([
            'user_id' => $user->id,
            'action' => 'pdf_from_images',
            'location_state' => $location['country'] ?? null,
            'location_city' => $location['city'] ?? null,
            'used_from' => request()->is('api/*') ? 'backend' : 'frontend',
        ]);

        $imagePaths = [];
        foreach ($request->file('images') as $index => $image) {
            $filename = 'input_img_' . time() . "_$index." . $image->getClientOriginalExtension();
            $image->storeAs('temp', $filename);
            $imagePaths[] = storage_path('app/private/temp/' . $filename);
        }

        $output = storage_path('app/public/pdf_from_images_' . time() . '.pdf');
        $python = base_path('venv/bin/python');
        $script = base_path('scripts/pdf_from_img.py');

        $cmd = "$python $script \"$output\"";
        foreach ($imagePaths as $path) {
            $cmd .= " \"$path\"";
        }

        exec($cmd, $outputLines, $resultCode);

        \Log::info("Running image-to-pdf: $cmd");
        \Log::info("Result: $resultCode");
        \Log::info("Output exists: " . file_exists($output));

        if ($resultCode !== 0 || !file_exists($output)) {
            return back()->withErrors(['Image to PDF conversion failed.']);
        }

        return response()->download($output)->deleteFileAfterSend(true);
    }
}
