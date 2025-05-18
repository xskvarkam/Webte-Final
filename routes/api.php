<?php

use App\Http\Controllers\Api\PdfToImgApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PdfDeleteApiController;
use App\Http\Controllers\Api\PdfCompressApiController;
use App\Http\Controllers\Api\PdfEditApiController;
use App\Http\Controllers\Api\PdfExtractApiController;
use App\Http\Controllers\Api\PdfMergeApiController;
use App\Http\Controllers\Api\PdfReverseApiController;
use App\Http\Controllers\Api\PdfRotateApiController;
use App\Http\Controllers\Api\PdfSignApiController;
use App\Http\Controllers\Api\PdfSplitApiController;
use App\Http\Controllers\Api\PdfWatermarkApiController;
use App\Http\Controllers\Api\PdfFromImgApiController;

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/pdf/edit', [PdfEditApiController::class, 'edit']);
    Route::post('/pdf/compress', [PdfCompressApiController::class, 'compress']);
    Route::post('/pdf/delete', [PdfDeleteApiController::class, 'delete']);
    Route::post('/pdf/extract', [PdfExtractApiController::class, 'extract']);
    Route::post('/pdf/merge', [PdfMergeApiController::class, 'merge']);
    Route::post('/pdf/reverse', [PdfReverseApiController::class, 'reverse']);
    Route::post('/pdf/rotate', [PdfRotateApiController::class, 'rotate']);
    Route::post('/pdf/sign', [PdfSignApiController::class, 'sign']);
    Route::post('/pdf/split', [PdfSplitApiController::class, 'split']);
    Route::post('/pdf/watermark', [PdfWatermarkApiController::class, 'watermark']);
    Route::post('/pdf/to-img', [PdfToImgApiController::class, 'convert']);
    Route::post('/pdf/from-img', [PdfFromImgApiController::class, 'create']);
    // add others...
});
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
