<?php

namespace App\Http\Controllers;

use App\Models\ClassBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use App\Notifications\BookingUpdatedNotification;
use App\Notifications\BookingAdminUpdatedNotification;
use App\Notifications\BookingCancelledNotification;
use App\Notifications\BookingAdminCancelledNotification;

class UserBookingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Lista las reservas del usuario (filtradas por email)
    public function index()
    {
        $user = Auth::user();
        $bookings = ClassBooking::where('user_id', $user->id)
            ->orderBy('class_date', 'desc')
            ->orderBy('class_time', 'desc')
            ->get();

        return view('user.bookings.index', compact('bookings'));
    }

    // Formulario para editar una reserva (si le pertenece)
    public function edit(ClassBooking $booking)
    {
        $this->authorizeBooking($booking);

        return view('user.bookings.edit', compact('booking'));
    }

    // Actualiza la reserva (solo campos permitidos)
    public function update(Request $request, ClassBooking $booking)
    {
        $this->authorizeBooking($booking);

        $data = $request->validate([
            'class_date' => 'required|date',
            'class_time' => 'required',
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:50',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Evitar colisiones: comprobar que no exista otra reserva (distinta) en la misma fecha/hora
        $conflict = ClassBooking::where('class_date', $data['class_date'])
            ->where('class_time', $data['class_time'])
            ->where('id', '!=', $booking->id)
            ->whereNotIn('status', ['cancelled', 'rejected'])
            ->exists();

        if ($conflict) {
            return back()->withErrors(['class_time' => 'Esa franja ya está ocupada.'])->withInput();
        }

        $booking->update($data);

        // Notificar al usuario y al admin del cambio
        Notification::route('mail', $booking->email)
            ->notify(new BookingUpdatedNotification($booking));

        Notification::route('mail', env('ADMIN_EMAIL', config('mail.from.address')))
            ->notify(new BookingAdminUpdatedNotification($booking));

        return redirect()->route('user.bookings.index')->with('ok', 'Reserva actualizada correctamente.');
    }

    // Cancela (soft change status) la reserva
    public function destroy(ClassBooking $booking)
    {
        $this->authorizeBooking($booking);

        $booking->update(['status' => 'cancelled']);

        // Notificar al usuario (confirmación de cancelación)
        Notification::route('mail', $booking->email)
            ->notify(new BookingCancelledNotification($booking));

        // Notificar al admin de que el usuario ha cancelado
        Notification::route('mail', env('ADMIN_EMAIL', config('mail.from.address')))
            ->notify(new BookingAdminCancelledNotification($booking));

        return redirect()->route('user.bookings.index')->with('ok', 'Reserva cancelada.');
    }

    protected function authorizeBooking(ClassBooking $booking)
    {
        $user = Auth::user();
        if ($booking->user_id !== $user->id) {
            abort(403);
        }
    }
}
