<x-app-layout>
  <div class="max-w-2xl mx-auto py-8">
    <h1 class="text-2xl font-semibold mb-4 text-center">Reservar una clase</h1>

    {{-- Mostrar errores --}}
    @if ($errors->any())
      <div class="bg-red-50 border border-red-200 p-3 mb-4 rounded">
        <ul class="list-disc pl-5 text-red-600 text-sm">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    {{-- Mensaje de éxito --}}
    @if (session('ok'))
      <div class="bg-green-50 border border-green-200 text-green-700 p-3 mb-4 rounded">
        {{ session('ok') }}
      </div>
    @endif

    {{-- Formulario de reserva --}}
    <form method="POST" action="{{ route('bookings.store') }}" class="space-y-5">
      @csrf

      {{-- Fecha y hora --}}
      <div>
        <label class="block text-sm font-medium mb-1">Fecha*</label>
        <input type="date" name="class_date" class="w-full border rounded p-2" value="{{ old('class_date') }}" min="{{ now()->toDateString() }}" required>
        @error('class_date')
          <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror

        <label class="block text-sm font-medium mb-1 mt-4">Hora*</label>
        <select id="class_time" name="class_time" class="w-full border rounded p-2" required>
          <option value="">— Selecciona hora —</option>
          @foreach (range(9, 21) as $h)
            @php $hh = str_pad($h, 2, '0', STR_PAD_LEFT) . ':00'; @endphp
            <option value="{{ $hh }}" @selected(old('class_time') === $hh)>
              {{ $hh }}
            </option>
          @endforeach
        </select>
        <p id="time-help" class="text-sm text-gray-500 mt-1"></p>
        @error('class_time')
          <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror
      </div>

      {{-- Nombre --}}
      <div>
        <label class="block text-sm font-medium mb-1">Nombre*</label>
        <input type="text" name="name" value="{{ old('name', auth()->user()->name ?? '') }}" class="w-full border rounded p-2" required>
        @error('name')
          <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror
      </div>

      {{-- Email --}}
      <div>
        <label class="block text-sm font-medium mb-1">Correo electrónico*</label>
        <input type="email" name="email" value="{{ old('email', auth()->user()->email ?? '') }}" class="w-full border rounded p-2" required>
        @error('email')
          <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror
      </div>

      {{-- Teléfono --}}
      <div>
        <label class="block text-sm font-medium mb-1">Teléfono</label>
        <input type="text" name="phone" value="{{ old('phone') }}" class="w-full border rounded p-2"
          placeholder="(opcional)">
        @error('phone')
          <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror
      </div>

      {{-- Comentarios --}}
      <div>
        <label class="block text-sm font-medium mb-1">Comentarios</label>
        <textarea name="notes" class="w-full border rounded p-2" rows="3"
          placeholder="Información adicional (opcional)">{{ old('notes') }}</textarea>
        @error('notes')
          <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror
      </div>

      {{-- Botón --}}
      <div class="text-center">
        <button type="submit" class="px-5 py-2 bg-blue-600 text-white font-semibold rounded hover:bg-blue-700">
          Enviar reserva
        </button>
      </div>
    </form>
  </div>
  <script>
    (function(){
      const dateInput = document.querySelector('input[name="class_date"]');
      const timeSelect = document.getElementById('class_time');
      const help = document.getElementById('time-help');
      const url = '{{ route('bookings.availability') }}';

      async function loadTimesFor(date) {
        if (!date) return;
        help.textContent = 'Comprobando disponibilidad...';
        try {
          const res = await fetch(url + '?date=' + encodeURIComponent(date));
          if (!res.ok) throw new Error('Error');
          const data = await res.json();
          // repoblar select
          timeSelect.innerHTML = '<option value="">— Selecciona hora —</option>';
          if ((data.available || []).length === 0) {
            help.textContent = 'No hay horas disponibles para esta fecha.';
            return;
          }
          help.textContent = '';
          data.available.forEach(t => {
            const opt = document.createElement('option');
            opt.value = t;
            opt.textContent = t;
            if ('{{ old('class_time') }}' === t) opt.selected = true;
            timeSelect.appendChild(opt);
          });
        } catch (e) {
          help.textContent = 'No se pudo comprobar disponibilidad.';
        }
      }

      dateInput.addEventListener('change', function(){ loadTimesFor(this.value); });
      // load on page load if date present
      if (dateInput.value) loadTimesFor(dateInput.value);
    })();
  </script>
</x-app-layout>