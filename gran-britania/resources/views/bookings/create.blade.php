<x-guest-layout>
<h1 class="text-2xl font-semibold mb-4">Reservar una clase</h1>

@if ($errors->any())
<div class="bg-red-50 border border-red-200 p-3 mb-4">
  <ul class="list-disc pl-5 text-red-600 text-sm">
    @foreach ($errors->all() as $error)
      <li>{{ $error }}</li>
    @endforeach
  </ul>
</div>
@endif

<form method="POST" action="{{ route('bookings.store') }}" class="space-y-4">
  @csrf
  <div>
    <label class="block text-sm mb-1">Fecha*</label>
    <input type="date" name="class_date" value="{{ old('class_date') }}" class="w-full border rounded p-2" required>
  </div>

  <div>
    <label class="block text-sm mb-1">Hora*</label>
    <input type="time" name="class_time" value="{{ old('class_time') }}" class="w-full border rounded p-2" required>
  </div>

  <div>
    <label class="block text-sm mb-1">Nombre*</label>
    <input name="name" value="{{ old('name') }}" class="w-full border rounded p-2" required>
  </div>

  <div>
    <label class="block text-sm mb-1">Correo*</label>
    <input type="email" name="email" value="{{ old('email') }}" class="w-full border rounded p-2" required>
  </div>

  <div>
    <label class="block text-sm mb-1">Teléfono</label>
    <input name="phone" value="{{ old('phone') }}" class="w-full border rounded p-2">
  </div>

  <div>
    <label class="block text-sm mb-1">Comentarios</label>
    <textarea name="notes" class="w-full border rounded p-2">{{ old('notes') }}</textarea>
  </div>

  <button class="px-4 py-2 bg-blue-600 text-white rounded">Enviar reserva</button>
</form>
</x-guest-layout>
