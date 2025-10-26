<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreClassBookingRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array {
        return [
            'class_date' => ['required','date','after_or_equal:today'],
            'class_time' => ['required','date_format:H:i'],
            'name'       => ['required','string','max:120'],
            'email'      => ['required','email'],
            'phone'      => ['nullable','string','max:40'],
            'notes'      => ['nullable','string','max:255'],
        ];
    }
}