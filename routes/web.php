<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\PdfController;
use App\Http\Controllers\Admin\HistoryLogController;
use App\Http\Controllers\Frontend\PdfEditController;
use App\Http\Controllers\Frontend\PdfMergeController;
use App\Http\Controllers\Frontend\PdfSplitController;
use App\Http\Controllers\Frontend\PdfDeleteController;
use App\Http\Controllers\Frontend\PdfExtractController;
use App\Http\Controllers\Frontend\PdfRotateController;
use App\Http\Controllers\Frontend\PdfWatermarkController;
use App\Http\Controllers\Frontend\PdfCompressController;
use App\Http\Controllers\Frontend\PdfReverseController;
use App\Http\Controllers\Frontend\PdfSignController;

use App\Http\Middleware\AdminMiddleware;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/api-token', [ProfileController::class, 'generateToken'])->name('profile.generate-token');
    
    Route::get('/pdf-tools', [PdfController::class, 'index'])->name('pdf.index');
    Route::post('/pdf-upload', [PdfController::class, 'upload'])->name('pdf.upload');
    Route::middleware(['auth'])->post('/log-history', [HistoryLogController::class, 'store'])->name('log.history');
    Route::get('/tools/edit', [PdfEditController::class, 'index'])->name('pdf.edit');
    Route::post('/tools/edit', [PdfEditController::class, 'process'])->name('pdf.edit.process');
    Route::get('/tools/merge', [PdfMergeController::class, 'index'])->name('pdf.merge');
    Route::post('/tools/merge', [PdfMergeController::class, 'process'])->name('pdf.merge.process');
    Route::get('/tools/split', [PdfSplitController::class, 'index'])->name('pdf.split');
    Route::post('/tools/split', [PdfSplitController::class, 'process'])->name('pdf.split.process');

    Route::get('/tools/delete', [PdfDeleteController::class, 'index'])->name('pdf.delete');
    Route::post('/tools/delete', [PdfDeleteController::class, 'process'])->name('pdf.delete.process');

    Route::get('/tools/extract', [PdfExtractController::class, 'index'])->name('pdf.extract');
    Route::post('/tools/extract', [PdfExtractController::class, 'process'])->name('pdf.extract.process');

    Route::get('/tools/rotate', [PdfRotateController::class, 'index'])->name('pdf.rotate');
    Route::post('/tools/rotate', [PdfRotateController::class, 'process'])->name('pdf.rotate.process');

    Route::get('/tools/watermark', [PdfWatermarkController::class, 'index'])->name('pdf.watermark');
    Route::post('/tools/watermark', [PdfWatermarkController::class, 'process'])->name('pdf.watermark.process');

    Route::get('/tools/compress', [PdfCompressController::class, 'index'])->name('pdf.compress');
    Route::post('/tools/compress', [PdfCompressController::class, 'process'])->name('pdf.compress.process');

    Route::get('/tools/reverse', [PdfReverseController::class, 'index'])->name('pdf.reverse');
    Route::post('/tools/reverse', [PdfReverseController::class, 'process'])->name('pdf.reverse.process');

    Route::get('/tools/sign', [PdfSignController::class, 'index'])->name('pdf.sign');
    Route::post('/tools/sign', [PdfSignController::class, 'process'])->name('pdf.sign.process');


    Route::get('/set-locale', function (\Illuminate\Http\Request $request) {
        $locale = $request->query('locale');

        if (in_array($locale, ['en', 'sk'])) {
            session(['locale' => $locale]);
        }

        return redirect()->back();
    })->name('set-locale');

});
Route::get('/docs', function () {
    return view('swagger');
});
Route::middleware(['auth', AdminMiddleware::class])
    ->prefix('admin')
    ->group(function () {
        Route::get('/history', [HistoryLogController::class, 'index'])->name('admin.history.index');
        Route::get('/history/export', [HistoryLogController::class, 'export'])->name('admin.history.export');
    });
require __DIR__.'/auth.php';
