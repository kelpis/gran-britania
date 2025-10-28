<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ManageSlotRequest;
use App\Models\AvailabilitySlot;
use App\Models\ClassBooking;
use Illuminate\Http\Request;

class AvailabilityAdminController extends Controller
{
    public function index()
    {
        $slots = AvailabilitySlot::orderBy('date')->orderBy('start_time')->paginate(30);
        return view('admin.availability', compact('slots'));
    }

    // Crear o actualizar (upsert) un slot puntual
    public function store(ManageSlotRequest $request)
    {
        $data = $request->validated();

        // upsert por (date, start, end)
        $slot = AvailabilitySlot::firstOrNew([
            'date' => $data['date'],
            'start_time' => $data['start_time'],
            'end_time' => $data['end_time'],
        ]);

        // si se quiere bloquear y hay reserva confirmada (en ese rango), evitarlo
        $hasConfirmed = ClassBooking::where('class_date', $data['date'])
            ->where('class_time', $data['start_time'])
            ->where('status','confirmed')
            ->exists();

        if ($data['status'] === 'blocked' && $hasConfirmed) {
            return back()->with('error','No se puede bloquear: existe una reserva confirmada en esa franja.');
        }

        $slot->status = $data['status'];
        $slot->save();

        return back()->with('ok','Franja guardada.');
    }

    // Generador en lote (laborables, por rango de fechas y horas)
    public function generate(Request $request)
    {
        // Validación básica de fechas y estado. Las horas se validan más abajo
        // dependiendo de si se está generando franjas horarias o bloqueando días completos.
        $request->validate([
            'from_date' => ['required','date','after_or_equal:today'],
            'to_date'   => ['required','date','after_or_equal:from_date'],
            'status'    => ['required','in:available,blocked'],
            'full_day'  => ['nullable','boolean'], // si true -> bloquea días enteros (00:00-24:00)
        ]);

    $from = \Carbon\Carbon::parse($request->from_date);
    $to   = \Carbon\Carbon::parse($request->to_date);
        $count = 0;
        $created = [];

    // Si no se envían horas, asumimos bloqueo de días completos.
    $isFullDay = $request->boolean('full_day') || !$request->has('start_hour');

        // Validación de horas sólo si no pedimos días completos
        if (!$isFullDay) {
            $request->validate([
                'start_hour'=> ['required','integer','between:0,23'],
                'end_hour'  => ['required','integer','between:1,24'],
            ]);

            if ((int)$request->end_hour <= (int)$request->start_hour) {
                return back()->with('error','La hora de fin debe ser mayor que la hora de inicio.');
            }
        }

        for ($date = $from->copy(); $date->lte($to); $date->addDay()) {
            // Excluir fines de semana siempre (validación centralizada en requests / reglas de negocio)
            if ($date->isWeekend()) continue;

            if ($isFullDay) {
                // Crear una franja que cubra todo el día
                $start = '00:00';
                $end = '24:00';

                AvailabilitySlot::updateOrCreate(
                    ['date'=>$date->toDateString(),'start_time'=>$start,'end_time'=>$end],
                    ['status'=>$request->status]
                );
                $count++;
                $created[] = $date->toDateString() . ' ' . $start . '-' . $end;
                continue;
            }

            for ($h = (int)$request->start_hour; $h < (int)$request->end_hour; $h++) {
                $start = sprintf('%02d:00', $h);
                $end   = sprintf('%02d:00', $h+1);

                AvailabilitySlot::updateOrCreate(
                    ['date'=>$date->toDateString(),'start_time'=>$start,'end_time'=>$end],
                    ['status'=>$request->status]
                );
                $count++;
                $created[] = $date->toDateString() . ' ' . $start . '-' . $end;
            }
        }

        // Devolvemos en la sesión el detalle de las franjas creadas para depuración en UI.
        return back()->with('ok', "Generadas/actualizadas {$count} franjas.")->with('generated', $created);
    }

    // Toggle rápido available/blocked
    public function toggle(AvailabilitySlot $slot)
    {
        // evitar bloquear si hay confirmada
        $hasConfirmed = ClassBooking::where('class_date', $slot->date)
            ->where('class_time', $slot->start_time)
            ->where('status','confirmed')
            ->exists();

        if ($slot->status === 'available' && $hasConfirmed) {
            return back()->with('error','No se puede bloquear: hay una reserva confirmada en esa franja.');
        }

        $slot->status = $slot->status === 'available' ? 'blocked' : 'available';
        $slot->save();

        return back()->with('ok','Franja actualizada.');
    }

    // Eliminar slot (si no compromete reservas confirmadas)
    public function destroy(AvailabilitySlot $slot)
    {
        $hasConfirmed = ClassBooking::where('class_date', $slot->date)
            ->where('class_time', $slot->start_time)
            ->where('status','confirmed')
            ->exists();

        if ($hasConfirmed) {
            return back()->with('error','No se puede borrar: hay una reserva confirmada asociada.');
        }

        $slot->delete();
        return back()->with('ok','Franja eliminada.');
    }
}
