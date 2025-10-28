<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Editar reserva</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow sm:rounded-lg">
                <form method="POST" action="{{ route('user.bookings.update', $booking) }}">
                    @csrf
                    @method('PUT')

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

                    <div class="mb-4">
                        <label class="block font-medium">Fecha</label>
                        <input type="date" name="class_date" value="{{ old('class_date', $booking->class_date) }}" class="w-full border rounded p-2" required>
                        @error('class_date')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium">Hora</label>
                        <select id="class_time" name="class_time" class="w-full border rounded p-2" required>
                            <option value="">— Selecciona hora —</option>
                            @foreach(range(9,21) as $h)
                                @php $hh = str_pad($h, 2, '0', STR_PAD_LEFT) . ':00'; @endphp
                                <option value="{{ $hh }}" @selected(old('class_time', substr($booking->class_time,0,5)) === $hh)>{{ $hh }}</option>
                            @endforeach
                        </select>
                        <p id="time-help" class="text-sm text-gray-500 mt-1"></p>
                        @error('class_time')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium">Nombre</label>
                        <input type="text" name="name" value="{{ old('name', $booking->name) }}" class="w-full border rounded p-2" required>
                        @error('name')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium">Teléfono</label>
                        <input type="text" name="phone" value="{{ old('phone', $booking->phone) }}" class="w-full border rounded p-2">
                        @error('phone')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium">Notas</label>
                        <textarea name="notes" class="w-full border rounded p-2">{{ old('notes', $booking->notes) }}</textarea>
                        @error('notes')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center gap-2">
                        <button class="bg-blue-600 text-white px-4 py-2 rounded">Guardar</button>
                        <a href="{{ route('user.bookings.index') }}" class="text-gray-600">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        (function(){
            const dateInput = document.querySelector('input[name="class_date"]');
            const timeSelect = document.getElementById('class_time');
            const help = document.getElementById('time-help');
            const url = '{{ route('bookings.availability') }}';
            const except = '{{ $booking->id }}';

            async function loadTimesFor(date) {
                if (!date) return;
                help.textContent = 'Comprobando disponibilidad...';
                try {
                    const res = await fetch(url + '?date=' + encodeURIComponent(date) + '&except=' + encodeURIComponent(except));
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
                        if ('{{ old('class_time', substr($booking->class_time,0,5)) }}' === t) opt.selected = true;
                        timeSelect.appendChild(opt);
                    });
                } catch (e) {
                    help.textContent = 'No se pudo comprobar disponibilidad.';
                }
            }

            dateInput.addEventListener('change', function(){ loadTimesFor(this.value); });
            // load on page load
            if (dateInput.value) loadTimesFor(dateInput.value);
        })();
    </script>
</x-app-layout>
