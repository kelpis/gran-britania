<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreTranslationRequest;
use App\Models\TranslationRequest;
use Illuminate\Support\Facades\Notification;
use App\Notifications\TranslationReceived;
use App\Notifications\TranslationAdminAlert;

class TranslationRequestController extends Controller
{
    // GET /traduccion
    public function create()
    {
        return view('translation.create'); // asegúrate que existe esta Blade
    }

    // POST /traduccion
    public function store(StoreTranslationRequest $request)
    {
        $path = $request->file('file')->store('translations');

        $data = $request->safe()->except('file');
        // Mapear consentimiento GDPR
        if (isset($data['gdpr']) && $data['gdpr']) {
            $data['gdpr_given'] = true;
            $data['gdpr_at'] = now();
            unset($data['gdpr']);
        }

        $tr = TranslationRequest::create(array_merge($data, ['file_path' => $path]));

        // correo al usuario
        Notification::route('mail', $tr->email)
            ->notify(new TranslationReceived($tr));

        // (opcional) correo al admin con pequeña pausa si usas Mailtrap free
        sleep(2);
        Notification::route('mail', env('ADMIN_EMAIL', config('mail.from.address')))
            ->notify(new TranslationAdminAlert($tr));

        return back()->with('ok', 'Solicitud enviada. Revisa tu correo para el acuse.');
    }
}
