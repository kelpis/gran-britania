<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ContactController;
use Illuminate\Support\Facades\Route;
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
});

// Rutas del administrador
Route::middleware(['auth', AdminMiddleware::class])
    ->get('/admin', [AdminController::class, 'index'])
    ->name('admin.index');

// Rutas del formulario de contacto
Route::get('/contacto', [ContactController::class, 'create'])->name('contact.create');
Route::post('/contacto', [ContactController::class, 'store'])->name('contact.store');

require __DIR__.'/auth.php';