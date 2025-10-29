<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\ClassBooking;
use Illuminate\Contracts\Validation\Validator;
use Carbon\Carbon;

class StoreClassBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
         'class_date' => ['required', 'date', 'after_or_equal:today'],
            'class_time' => ['required', 'date_format:H:i'],
            'name'       => ['required', 'string', 'max:120'],
            'email'      => ['required', 'email'],
            'phone'      => ['nullable', 'string', 'max:40'],
            'notes'      => ['nullable', 'string', 'max:255'],
            'gdpr'       => ['accepted'],
        ];
    }

    protected function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {
            $data = $this->only(['class_date', 'class_time']);

            if (empty($data['class_date']) || empty($data['class_time'])) {
                return;
            }

            $exists = ClassBooking::where('class_date', $data['class_date'])
                ->where('class_time', $data['class_time'])
                ->whereNotIn('status', ['cancelled', 'rejected'])
                ->exists();

            if ($exists) {
                $validator->errors()->add('class_time', 'Lo sentimos — esa franja ya está ocupada.');
            }

            // Validación: no permitir fines de semana
            try {
                $dt = Carbon::parse($data['class_date']);
                $dow = $dt->dayOfWeek; // 0 = domingo, 6 = sábado
                if ($dow === Carbon::SATURDAY || $dow === Carbon::SUNDAY) {
                    $validator->errors()->add('class_date', 'No es posible reservar en fines de semana. Por favor elige un día laborable.');
                }
            } catch (\Throwable $e) {
                // si no se puede parsear, dejar que la regla 'date' reporte el error
            }
        });
    }

    public function messages(): array
    {
        return [
            'availability_slot_id.required' => 'Selecciona una franja disponible.',
            'availability_slot_id.exists'   => 'La franja no está disponible o incumple las reglas (L–V, 09:00–21:00).',
            'class_date.required' => 'Selecciona una fecha válida.',
            'class_date.date' => 'La fecha no tiene un formato válido.',
        ];
    }
}
