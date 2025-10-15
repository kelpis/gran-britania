<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController; // 👈 añade esta línea
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

// 👇 Añade aquí tu ruta de administrador

//Route::middleware(['auth', AdminMiddleware::class])
    //->get('/admin', fn() => 'OK ADMIN DASHBOARD');
Route::middleware(['auth', AdminMiddleware::class])
    ->get('/admin', [AdminController::class, 'index'])
    ->name('admin.index');
// (Puedes dejar tu prueba si quieres)
//Route::middleware(['auth', 'admin'])->get('/admin-test', fn() => 'solo admin');

require __DIR__.'/auth.php';
