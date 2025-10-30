<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\Recaptcha;

class StoreContactRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // permite a cualquier usuario usar el formulario
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'    => 'required|string|max:120',
            'email'   => 'required|email',
            'subject' => 'nullable|string|max:160',
            'message' => 'required|string|max:2000',
            'gdpr'    => 'accepted',
            // reCAPTCHA v2 field (Google) - validated via App\Rules\Recaptcha
            // validate reCAPTCHA v3 with a conservative threshold (0.5) and expected action 'contact'
            'g-recaptcha-response' => ['required', new Recaptcha(0.5, 'contact')],
        ];
    }

     public function messages()
    {
        return [
            'name.required' => 'Por favor indica tu nombre.',
            'email.required' => 'Por favor indica un correo válido.',
            'message.required' => 'Escribe tu mensaje antes de enviarlo.',
            'gdpr.accepted' => 'Debes aceptar la política de protección de datos para continuar.',
        ];
    }
}
