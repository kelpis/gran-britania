<?php

use Illuminate\Support\Facades\Storage;
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
// Admin (agrupado con prefijo y nombres)
/*Route::middleware(['auth', AdminMiddleware::class])
    ->prefix('admin')->name('admin.')->group(function () {

        // Listado de solicitudes de traducción (VISTA: resources/views/admin/translation.blade.php)
        Route::get('/traducciones', function () {
            $items = TranslationRequest::latest()->paginate(20);
            return view('admin.translation', compact('items'));
        })->name('translations.index');

        // Descarga del archivo subido por el usuario
        Route::get('/traducciones/{id}/archivo', function ($id) {
            $tr = TranslationRequest::findOrFail($id);
            return response()->download(storage_path('app/' . $tr->file_path));
        })->name('translations.download');
    });*/

    Route::middleware(['auth', AdminMiddleware::class])
    ->prefix('admin')->name('admin.')->group(function () {

        Route::get('/traducciones', function () {
            $items = TranslationRequest::latest()->paginate(20);
            return view('admin.translation', compact('items'));
        })->name('translations.index');

        Route::get('/traducciones/{id}/archivo', function ($id) {
            $tr = TranslationRequest::findOrFail($id);

            // Comprueba que exista en el disco "local"
            if (!Storage::disk('local')->exists($tr->file_path)) {
                abort(404, 'Archivo no encontrado en el servidor.');
            }

            // Descarga usando Storage (mejor que construir la ruta a mano)
            $filename = basename($tr->file_path); // o guarda nombre original en BD
            return Storage::disk('local')->download($tr->file_path, $filename);
        })->name('translations.download');
    });


require __DIR__.'/auth.php';