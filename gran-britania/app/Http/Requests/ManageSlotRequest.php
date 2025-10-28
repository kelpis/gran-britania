<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ManageSlotRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'date'       => ['required','date','after_or_equal:today'],
            'start_time' => ['required','date_format:H:i','regex:/^(?:[01]\d|2[0-3]):00$/'],
            // end_time admite HH:00 entre 00:00-23:00 o el valor especial 24:00 para representar fin de día
            'end_time'   => ['required','regex:/^(?:(?:[01]\d|2[0-3]):00|24:00)$/'],
            'status'     => ['required','in:available,blocked'],
        ];
    }

    public function messages(): array
    {
        return [
            'start_time.regex' => 'La hora de inicio debe ser en punto (HH:00).',
            'end_time.regex'   => 'La hora de fin debe ser en punto (HH:00) o 24:00 para indicar fin de día.',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($v) {
            if (! $this->filled('start_time') || ! $this->filled('end_time')) return;

            $start = $this->input('start_time');
            $end = $this->input('end_time');

            // convertir a minutos; 24:00 -> 1440
            $toMinutes = fn($t) => ($t === '24:00') ? 24*60 : (intval(substr($t,0,2)) * 60 + intval(substr($t,3,2)));

            $s = $toMinutes($start);
            $e = $toMinutes($end);

            if ($e <= $s) {
                $v->errors()->add('end_time', 'La hora de fin debe ser posterior a la hora de inicio.');
            }
        });
    }
}
