<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClassBookingRequest;
use App\Models\ClassBooking;
use App\Models\AvailabilitySlot;
use App\Notifications\BookingReceived;
use App\Notifications\BookingAdminNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Http\Request;

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
        // 1) Datos validados
        $data = $request->validated();

        // 2) Evitar franja ocupada: no permitir si ya existe otra reserva para la misma
        //    fecha/hora cuyo estado NO sea 'cancelled' o 'rejected' (p.ej. pending/confirmed)
        $exists = ClassBooking::where('class_date', $data['class_date'])
            ->where('class_time', $data['class_time'])
            ->whereNotIn('status', ['cancelled', 'rejected'])
            ->exists();

        if ($exists) {
            return back()
                ->withErrors(['class_time' => 'Lo sentimos — esa franja ya está ocupada.'])
                ->withInput();
        }

        // 3) Crear en pending
        // Antes de crear, comprobar que la franja no esté bloqueada por admin
        $isBlocked = AvailabilitySlot::where('date', $data['class_date'])
            ->where('status', 'blocked')
            ->get()
            ->filter(function ($slot) use ($data) {
                // convertir tiempos a minutos para comparar rangos
                [$h, $m] = explode(':', substr($data['class_time'], 0, 5));
                $tMin = intval($h) * 60 + intval($m);

                [$sH, $sM] = explode(':', substr($slot->start_time, 0, 5));
                [$eH, $eM] = explode(':', substr($slot->end_time, 0, 5));
                $sMin = intval($sH) * 60 + intval($sM);
                // soportar end_time == '24:00'
                $eMin = intval($eH) * 60 + intval($eM);

                return $tMin >= $sMin && $tMin < $eMin;
            })->isNotEmpty();

        if ($isBlocked) {
            return back()->withErrors(['class_time' => 'Esa franja está bloqueada por el administrador.'])->withInput();
        }
        $payload = [
            'class_date' => $data['class_date'],
            'class_time' => $data['class_time'],
            'name'       => $data['name'],
            'email'      => $data['email'],
            'phone'      => $data['phone'] ?? null,
            'notes'      => $data['notes'] ?? null,
            'status'     => 'pending',
        ];

        // Asignar user_id si hay usuario autenticado
        if (auth()->check()) {
            $payload['user_id'] = auth()->id();
        }

        // Mapear consentimiento GDPR si viene en el request
        if (isset($data['gdpr']) && $data['gdpr']) {
            $payload['gdpr_given'] = true;
            $payload['gdpr_at'] = now();
        }

        $booking = ClassBooking::create($payload);

        // 4) Notificaciones (usuario + admin)
        try {
            Notification::route('mail', $booking->email)
                ->notify(new BookingReceived($booking));

            // si usas Mailtrap/Ethereal free, una pequeña pausa ayuda a no saturar
            sleep(2);

            Notification::route('mail', env('ADMIN_EMAIL', config('mail.from.address')))
                ->notify(new BookingAdminNotification($booking));
        } catch (\Throwable $e) {
            Log::warning('Error al enviar notificación de reserva: ' . $e->getMessage());
        }

        // 5) Mensaje de éxito
        return redirect()->route('bookings.success')
            ->with('ok', 'Solicitud enviada correctamente. Revisa tu correo para la confirmación.');
    }

    public function success()
    {
        return view('bookings.success');
    }

    // Devuelve las horas disponibles para una fecha dada en formato JSON
    public function availability(Request $request)
    {
        $date = $request->query('date');
        $exceptId = $request->query('except'); // optional booking id to ignore (for edit)

        if (! $date) {
            return response()->json(['error' => 'date parameter required'], 422);
        }

        // Horas posibles (en punto) de 09:00 a 21:00
        $all = [];
        foreach (range(9, 21) as $h) {
            $hh = str_pad($h, 2, '0', STR_PAD_LEFT) . ':00';
            $all[] = $hh;
        }

        // Obtener reservas no canceladas/rechazadas para esa fecha
        $query = ClassBooking::where('class_date', $date)
            ->whereNotIn('status', ['cancelled', 'rejected']);

        if ($exceptId) {
            $query->where('id', '!=', $exceptId);
        }

        $taken = $query->get()->map(function ($b) {
            return substr($b->class_time, 0, 5);
        })->toArray();

        // Excluir franjas bloqueadas por admin
        $blockedSlots = AvailabilitySlot::where('date', $date)
            ->where('status', 'blocked')
            ->get();

        // Convertir a minutos para comparar
        $toMinutes = function ($time) {
            [$H, $M] = explode(':', substr($time, 0, 5));
            return intval($H) * 60 + intval($M);
        };

        $blockedTimes = [];
        foreach ($all as $t) {
            $tMin = $toMinutes($t);
            foreach ($blockedSlots as $slot) {
                $sMin = $toMinutes($slot->start_time);
                $eMin = $toMinutes($slot->end_time);
                if ($tMin >= $sMin && $tMin < $eMin) {
                    $blockedTimes[] = $t;
                    break;
                }
            }
        }

        $available = array_values(array_filter($all, function ($t) use ($taken, $blockedTimes) {
            return ! in_array($t, $taken) && ! in_array($t, $blockedTimes);
        }));

        return response()->json(['available' => $available]);
    }
}
