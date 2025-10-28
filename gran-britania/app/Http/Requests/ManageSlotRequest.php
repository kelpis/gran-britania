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
            'end_time'   => ['required','date_format:H:i','after:start_time','regex:/^(?:[01]\d|2[0-3]):00$/'],
            'status'     => ['required','in:available,blocked'],
        ];
    }

    public function messages(): array
    {
        return [
            'start_time.regex' => 'La hora de inicio debe ser en punto (HH:00).',
            'end_time.regex'   => 'La hora de fin debe ser en punto (HH:00).',
        ];
    }
}
