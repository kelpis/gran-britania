<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
            // Debe ser HH:00 (00–23 en punto)
            'class_time' => [
                'required',
                'date_format:H:i',
                'regex:/^(?:[01]\d|2[0-3]):00$/', // solo en punto
            ],
            'name'       => ['required', 'string', 'max:120'],
            'email'      => ['required', 'email'],
            'phone'      => ['nullable', 'string', 'max:40'],
            'notes'      => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
{
    return [
        'class_time.regex' => 'Selecciona una hora en punto (por ejemplo, 10:00, 11:00...).',
    ];
}
}
