<?php

use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ClassBookingController;
use App\Http\Controllers\BookingAdminController;
use App\Http\Controllers\AvailabilityAdminController;
use App\Http\Controllers\TranslationRequestController;
use App\Http\Controllers\UserBookingController;
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

//ROUTAS RESERVA CLASE (solo usuarios autenticados pueden reservar)
Route::middleware('auth')->group(function () {
    Route::get('/reservar', [ClassBookingController::class, 'create'])
        ->name('bookings.create');

    Route::post('/reservar', [ClassBookingController::class, 'store'])
        ->name('bookings.store');
    
    // Endpoint para consultar horas disponibles para una fecha
    Route::get('/reservar/disponibilidad', [ClassBookingController::class, 'availability'])
        ->name('bookings.availability');
});

Route::get('/reservar/ok', [ClassBookingController::class, 'success'])
    ->name('bookings.success');

// Panel de usuario: mis reservas (loggeados)
Route::middleware('auth')->group(function () {
    Route::get('/mis-reservas', [UserBookingController::class, 'index'])->name('user.bookings.index');
    Route::get('/mis-reservas/{booking}/editar', [UserBookingController::class, 'edit'])->name('user.bookings.edit');
    Route::put('/mis-reservas/{booking}', [UserBookingController::class, 'update'])->name('user.bookings.update');
    Route::delete('/mis-reservas/{booking}', [UserBookingController::class, 'destroy'])->name('user.bookings.destroy');

    // Permitir al usuario descargar su propio archivo de traducción (si subió uno)
    Route::get('/mis-traducciones/{id}/archivo', function ($id) {
        $tr = TranslationRequest::findOrFail($id);

        // Asegurar que la traducción pertenece al usuario loggeado
        if ($tr->email !== auth()->user()->email) {
            abort(403);
        }

        if (!Storage::disk('local')->exists($tr->file_path)) {
            abort(404, 'Archivo no encontrado en el servidor.');
        }

        $filename = basename($tr->file_path);
        return Storage::disk('local')->download($tr->file_path, $filename);
    })->name('user.translations.download');

    // Página dedicada: Mis traducciones (lista del usuario)
    Route::get('/mis-traducciones', function () {
        $user = auth()->user();
        $items = TranslationRequest::where('email', $user->email)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('user.translations.index', compact('items'));
    })->name('user.translations.index');
});

//ROUTES SOLICITAR TRADUCCION
Route::get('/traduccion', [TranslationRequestController::class, 'create'])->name('translation.create');
Route::post('/traduccion', [TranslationRequestController::class, 'store'])
    ->middleware('throttle:5,1')  // rate limit opcional
    ->name('translation.store');

//ROUTES ADMIN 

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


        Route::get('/reservas', [BookingAdminController::class, 'index'])->name('bookings.index');
        Route::patch('/reservas/{booking}/confirmar', [BookingAdminController::class, 'confirm'])->name('bookings.confirm');
        Route::patch('/reservas/{booking}/cancelar', [BookingAdminController::class, 'cancel'])->name('bookings.cancel');
        //ROUTE FRANJAS HORARIAS
        Route::get('/disponibilidad', [AvailabilityAdminController::class, 'index'])->name('availability.index');
        Route::post('/disponibilidad', [AvailabilityAdminController::class, 'store'])->name('availability.store');
        Route::post('/disponibilidad/generar', [AvailabilityAdminController::class, 'generate'])->name('availability.generate');
        Route::patch('/disponibilidad/{slot}/toggle', [AvailabilityAdminController::class, 'toggle'])->name('availability.toggle');
        Route::delete('/disponibilidad/{slot}', [AvailabilityAdminController::class, 'destroy'])->name('availability.destroy');
    });


require __DIR__ . '/auth.php';
