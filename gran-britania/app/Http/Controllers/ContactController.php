<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactRequest;
use App\Models\ContactMessage;
use App\Notifications\ContactReceived;
use App\Notifications\ContactAdminAlert;
use Illuminate\Support\Facades\Notification;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Muestra el formulario de contacto
     */
    public function create()
    {
        return view('contact.create');
    }

    /**
     * Procesa el envío del formulario
     */
    public function store(StoreContactRequest $request)
    {
        // 1️⃣ Guarda el mensaje en base de datos
        $data = $request->validated();

        // Mapear el checkbox 'gdpr' (aceptado) a los campos persistidos
        if (isset($data['gdpr']) && $data['gdpr']) {
            $data['gdpr_given'] = true;
            $data['gdpr_at'] = now();
            // Remove the raw 'gdpr' input so it doesn't cause mass-assignment issues
            unset($data['gdpr']);
        }

        $msg = ContactMessage::create($data);

        // 2️⃣ Envía un acuse al usuario
        Notification::route('mail', $msg->email)
            ->notify(new ContactReceived($msg));
        //usleep(1500_000); // 0.7 segundos (ajusta 500-1000 ms si hace falta)
        //sleep(2);

        Notification::route('mail', env('ADMIN_EMAIL'))
            ->notify(new ContactAdminAlert($msg));


        // 4️⃣ Devuelve al formulario con mensaje de éxito
        return back()->with('ok', 'Mensaje enviado correctamente. Revisa tu correo para el acuse.');
    }
}
