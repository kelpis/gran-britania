<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTranslationRequest extends FormRequest
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
            'name' => 'required|string|max:120',
            'email'=> 'required|email',
            'source_lang' => 'required|string|max:10',
            'target_lang' => 'required|string|max:10|different:source_lang',
            'urgency' => 'nullable|in:normal,alta',
            'file' => 'required|file|max:5120', // 5MB
            'comments' => 'nullable|string|max:2000',
            'gdpr' => 'accepted',
        ];
    }
}
