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

Route::get('/pdf-to-word', function () {
    return Inertia::render('Pdf2Doc');
})->name('Pdf2Doc');

Route::post('/convert-pdf-to-doc', [DocumentOcrController::class, 'convertPdfToDoc']);
Route::post('/convert-pdf-to-searchable', [DocumentOcrController::class, 'convertPdfToSearchable']);

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
