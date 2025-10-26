<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClassBookingRequest;
use App\Notifications\BookingReceived;
use App\Notifications\BookingAdminNotification;
use App\Models\ClassBooking;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Notification;



class ClassBookingController extends Controller
{
    // Formulario público
    public function create()
    {
        return view('bookings.create');
    }

    // Guardar reserva
    public function store(StoreClassBookingRequest $request)
    {
       
    // 1️⃣ Recoger datos validados del formulario
    $data = $request->validated();

    // 2️⃣ Comprobar si ya hay una reserva confirmada en esa franja
    $exists = ClassBooking::where('class_date', $data['class_date'])
        ->where('class_time', $data['class_time'])
        ->where('status', 'confirmed')
        ->exists();

    if ($exists) {
        return back()
            ->withErrors(['class_time' => 'Esa franja ya no está disponible.'])
            ->withInput();
    }

    // 3️⃣ Crear la reserva en estado pending
    $booking = ClassBooking::create([
        'class_date' => $data['class_date'],
        'class_time' => $data['class_time'],
        'name'       => $data['name'],
        'email'      => $data['email'],
        'phone'      => $data['phone'] ?? null,
        'notes'      => $data['notes'] ?? null,
        'status'     => 'pending',
    ]);

    // 4️⃣ Enviar notificaciones (usuario y admin)
    try {
        // Usuario
        Notification::route('mail', $booking->email)
            ->notify(new BookingReceived($booking));

        // Pequeña pausa si usas Mailtrap/Ethereal
        sleep(2);

        // Admin
        Notification::route('mail', env('ADMIN_EMAIL', config('mail.from.address')))
            ->notify(new BookingAdminNotification($booking));
    } catch (\Throwable $e) {
        Log::warning('Error al enviar notificación de reserva: ' . $e->getMessage());
    }

    // 5️⃣ Volver con mensaje de éxito
    //return back()->with('ok', 'Solicitud enviada. Revisa tu correo para el acuse.');
    return redirect()->route('bookings.success')
    ->with('ok', 'Solicitud enviada. Revisa tu correo para el acuse.');

}

    
    public function success()
    {
        return view('bookings.success');
    }
}
