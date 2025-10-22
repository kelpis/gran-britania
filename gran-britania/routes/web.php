<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\TranslationRequestController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AdminMiddleware;
use App\Models\TranslationRequest;

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
});

// Rutas del administrador
Route::middleware(['auth', AdminMiddleware::class])
    ->get('/admin', [AdminController::class, 'index'])
    ->name('admin.index');

// Rutas del formulario de contacto
Route::get('/contacto', [ContactController::class, 'create'])->name('contact.create');
Route::post('/contacto', [ContactController::class, 'store'])->name('contact.store');

//ROUTAS SOLICITAR TRADUCCION
Route::get('/traduccion', [TranslationRequestController::class,'create'])->name('translation.create');
Route::post('/traduccion', [TranslationRequestController::class,'store'])
    ->middleware('throttle:5,1')  // rate limit opcional
    ->name('translation.store');

// Traducciones (panel admin)
Route::middleware(['auth', AdminMiddleware::class])
    ->get('/admin/traducciones', function () {
        $items = TranslationRequest::latest()->paginate(20);
        return view('admin.translation', compact('items'));
    })
    ->name('admin.translation');



require __DIR__.'/auth.php';