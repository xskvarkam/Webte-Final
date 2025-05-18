<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\HistoryLog;

class PdfFromImgApiController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'images' => 'required|array|min:1',
            'images.*' => 'file|mimes:jpg,jpeg,png|max:51200',
        ]);

        $user = $request->user(); // cez Sanctum
        $ip = $request->ip();
        $location = Http::get("http://ip-api.com/json/{$ip}")->json();

        HistoryLog::create([
            'user_id' => $user->id,
            'action' => 'pdf_from_images',
            'location_state' => $location['country'] ?? null,
            'location_city' => $location['city'] ?? null,
            'used_from' => 'backend',
        ]);

        $imagePaths = [];
        foreach ($request->file('images') as $index => $image) {
            $filename = 'api_img_' . time() . '_' . $index . '.' . $image->getClientOriginalExtension();
            $image->storeAs('temp', $filename);
            $imagePaths[] = storage_path('app/private/temp/' . $filename);
        }

        $outputName = 'pdf_from_api_images_' . time() . '.pdf';
        $output = storage_path('app/public/' . $outputName);

        $python = base_path('venv/bin/python');
        $script = base_path('scripts/pdf_from_img.py');

        $cmd = "$python \"$script\" \"$output\"";
        foreach ($imagePaths as $path) {
            $cmd .= " \"" . $path . "\"";
        }

        exec($cmd, $outputLines, $resultCode);

        \Log::info("API Img2PDF CMD: $cmd");
        \Log::info("Result: $resultCode");

        if ($resultCode !== 0 || !file_exists($output)) {
            return response()->json(['message' => 'Image to PDF conversion failed.'], 500);
        }

        return response()->download($output, $outputName, [
            'Content-Type' => 'application/pdf',
        ])->deleteFileAfterSend(true);
    }
}
