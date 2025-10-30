<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ClassBooking;
use App\Notifications\BookingConfirmedNotification;
use App\Notifications\BookingCancelledNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class BookingAdminController extends Controller
{
     public function index()
    {
        $pendientes = ClassBooking::where('status','pending')
            ->orderBy('class_date')->orderBy('class_time')->get();

        // Order all lists by class_date then class_time (ascending)
        $confirmadas = ClassBooking::where('status','confirmed')
            ->orderBy('class_date')->orderBy('class_time')->limit(50)->get();

        $canceladas = ClassBooking::where('status','cancelled')
            ->orderBy('class_date')->orderBy('class_time')->limit(50)->get();

        return view('admin.booking', compact('pendientes','confirmadas','canceladas'));

    }

    public function confirm(ClassBooking $booking)
    {
        // Evitar solape: si ya hay otra confirmada misma fecha/hora
        $exists = ClassBooking::where('id','!=',$booking->id)
            ->where('class_date',$booking->class_date)
            ->where('class_time',$booking->class_time)
            ->where('status','confirmed')
            ->exists();

        if ($exists) {
            return back()->with('error','Ya hay otra reserva confirmada en esa franja.');
        }

        // Permitir que el admin incluya la URL de la videollamada al confirmar
        $data = ['status' => 'confirmed'];
        if (request()->filled('meeting_url')) {
            $data['meeting_url'] = request()->input('meeting_url');
        }

        $booking->update($data);

        // Asegurarnos de refrescar el modelo para que contenga meeting_url actualizado
        $booking->refresh();

        try {
            Notification::route('mail', $booking->email)
                ->notify(new BookingConfirmedNotification($booking));
        } catch (\Throwable $e) {
            Log::warning('Error al enviar confirmación: '.$e->getMessage());
        }

        return back()->with('ok','Reserva confirmada y correo enviado.');
    }

    public function cancel(ClassBooking $booking)
    {
        $booking->update(['status' => 'cancelled']);

        try {
            Notification::route('mail', $booking->email)
                ->notify(new BookingCancelledNotification($booking));
        } catch (\Throwable $e) {
            Log::warning('Error al enviar cancelación: '.$e->getMessage());
        }

        return back()->with('ok','Reserva cancelada y aviso enviado.');
    }
}
