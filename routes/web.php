<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\PdfController;
use App\Http\Controllers\Api\HistoryLogController;

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
    Route::get('/pdf-tools', [PdfController::class, 'index'])->name('pdf.index');
    Route::post('/pdf-upload', [PdfController::class, 'upload'])->name('pdf.upload');
    Route::middleware(['auth'])->post('/log-history', [HistoryLogController::class, 'store'])->name('log.history');
});

require __DIR__.'/auth.php';
