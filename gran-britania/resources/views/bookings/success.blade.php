<x-guest-layout>
  <div class="p-6 bg-green-50 border border-green-200 rounded">
    <h2 class="text-xl font-semibold mb-2">¡Reserva enviada!</h2>
    <p class="text-sm text-green-800">
      Tu solicitud ha sido registrada en estado <strong>pendiente</strong>.
      Recibirás un correo de confirmación en breve.
    </p>
    <div class="mt-4">
      <a href="{{ route('dashboard') }}" class="inline-block px-4 py-2 bg-blue-600 text-white font-medium rounded hover:bg-blue-700">
        Volver al panel
      </a>
    </div>
  </div>
</x-guest-layout>
