<?php

use App\Http\Controllers\DocumentOcrController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    // return Inertia::render('Home', [
    //     'canLogin' => Route::has('login'),
    //     'canRegister' => Route::has('register'),
    //     'laravelVersion' => Application::VERSION,
    //     'phpVersion' => PHP_VERSION,
    // ]);
    return Inertia::render('Home');
});
Route::inertia('about','About');
Route::inertia('services','Services/Index')->name('services');

Route::group([], function () {
    Route::get('/pdf-to-scanable-pdf', function () {
        return Inertia::render('Services/Pdf2Pdf');
    })->name('Pdf2Pdf');

    Route::get('/image-to-text', function () {
        return Inertia::render('Services/ImageToText');
    })->name('ImageToText');
});

Route::controller(DocumentOcrController::class)->group(function () {
    Route::post('/convert-pdf-to-doc', 'convertPdfToDoc');
    Route::post('/convert-pdf-to-searchable', 'convertPdfToSearchable')->name('pdfscanable');
    Route::delete('/delete-file', 'removeFile')->name('pdf_delete');
    Route::post('/convert-image-to-text','uploadAndConvertToText')->name('convertionText');
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
