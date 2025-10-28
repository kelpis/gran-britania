<x-app-layout>
  <x-slot name="header"><h2 class="font-semibold text-xl">Disponibilidad</h2></x-slot>

  <div class="py-6 max-w-6xl mx-auto space-y-6">
    @if (session('ok'))
      <div class="p-3 bg-green-50 border border-green-200 text-green-800 rounded">{{ session('ok') }}</div>
    @endif
    @if (session('error'))
      <div class="p-3 bg-red-50 border border-red-200 text-red-800 rounded">{{ session('error') }}</div>
    @endif

    {{-- Crear/editar un slot puntual --}}
    <div class="bg-white p-4 rounded shadow space-y-4">
      <h3 class="font-semibold">Añadir/actualizar franja puntual</h3>
      <form method="POST" action="{{ route('admin.availability.store') }}" class="grid md:grid-cols-5 gap-3">
        @csrf
        <input type="date" name="date" class="border rounded p-2" required min="{{ now()->toDateString() }}">
        <input type="time" name="start_time" class="border rounded p-2" step="3600" required>
        <input type="time" name="end_time" class="border rounded p-2" step="3600" required>
        <select name="status" class="border rounded p-2" required>
          <option value="available">Disponible</option>
          <option value="blocked">Bloqueado</option>
        </select>
        <button class="px-4 py-2 bg-blue-600 text-white rounded">Guardar</button>
      </form>
      @error('date')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
      @error('start_time')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
      @error('end_time')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
    </div>

    {{-- Generador en lote --}}
    <div class="bg-white p-4 rounded shadow space-y-4">
      <h3 class="font-semibold">Generar franjas (lote)</h3>
      <form method="POST" action="{{ route('admin.availability.generate') }}" class="grid md:grid-cols-6 gap-3">
        @csrf
        <div>
          <label class="block text-xs text-gray-500">Desde</label>
          <input type="date" name="from_date" class="border rounded p-2" required min="{{ now()->toDateString() }}">
        </div>
        <div>
          <label class="block text-xs text-gray-500">Hasta</label>
          <input type="date" name="to_date" class="border rounded p-2" required min="{{ now()->toDateString() }}">
        </div>
        <div>
          <label class="block text-xs text-gray-500">Hora inicio</label>
          <input type="number" name="start_hour" class="border rounded p-2" min="0" max="23" value="9" required>
        </div>
        <div>
          <label class="block text-xs text-gray-500">Hora fin</label>
          <input type="number" name="end_hour" class="border rounded p-2" min="1" max="24" value="21" required>
        </div>
        <div>
          <label class="block text-xs text-gray-500">Estado</label>
          <select name="status" class="border rounded p-2" required>
            <option value="available">Disponible</option>
            <option value="blocked">Bloqueado</option>
          </select>
        </div>
        <label class="inline-flex items-center gap-2">
          <input type="checkbox" name="weekends" value="1"> Incluir fines de semana
        </label>
        <div class="md:col-span-6">
          <button class="px-4 py-2 bg-blue-600 text-white rounded">Generar</button>
        </div>
      </form>
    </div>

    {{-- Tabla de slots --}}
    <div class="bg-white p-4 rounded shadow">
      <h3 class="font-semibold mb-3">Franjas</h3>
      <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
          <thead>
            <tr class="text-left border-b">
              <th class="py-2 pr-3">Fecha</th>
              <th class="py-2 pr-3">Inicio</th>
              <th class="py-2 pr-3">Fin</th>
              <th class="py-2 pr-3">Estado</th>
              <th class="py-2 pr-3">Acciones</th>
            </tr>
          </thead>
          <tbody>
          @forelse ($slots as $s)
            <tr class="border-b">
              <td class="py-2 pr-3">{{ \Carbon\Carbon::parse($s->date)->format('d/m/Y') }}</td>
              <td class="py-2 pr-3">{{ substr($s->start_time,0,5) }}</td>
              <td class="py-2 pr-3">{{ substr($s->end_time,0,5) }}</td>
              <td class="py-2 pr-3">
                <span class="px-2 py-0.5 rounded text-white {{ $s->status==='available' ? 'bg-green-600' : 'bg-gray-500' }}">
                  {{ $s->status === 'available' ? 'Disponible' : 'Bloqueado' }}
                </span>
              </td>
              <td class="py-2 pr-3">
                <div class="flex items-center gap-2">
                  <form method="POST" action="{{ route('admin.availability.toggle', $s) }}">
                    @csrf @method('PATCH')
                    <button class="px-3 py-1 rounded bg-yellow-600 text-white hover:bg-yellow-700">
                      Cambiar estado
                    </button>
                  </form>
                  <form method="POST" action="{{ route('admin.availability.destroy', $s) }}"
                        onsubmit="return confirm('¿Eliminar esta franja?');">
                    @csrf @method('DELETE')
                    <button class="px-3 py-1 rounded bg-red-600 text-white hover:bg-red-700">
                      Eliminar
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          @empty
            <tr><td colspan="5" class="py-3 text-gray-500">No hay franjas creadas.</td></tr>
          @endforelse
          </tbody>
        </table>
      </div>

      <div class="mt-3">{{ $slots->links() }}</div>
    </div>
  </div>
</x-app-layout>