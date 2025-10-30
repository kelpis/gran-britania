<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Reservas</h2>
    </x-slot>

    <div class="py-6 space-y-6 max-w-6xl mx-auto">
        {{-- Collections are ordered in the controller for performance and separation of concerns. --}}
        {{-- Mensajes de estado --}}
        @if (session('ok'))
            <div class="p-3 rounded bg-green-50 border border-green-200 text-green-800">
                {{ session('ok') }}
            </div>
        @endif

        @if (session('error'))
            <div class="p-3 rounded bg-red-50 border border-red-200 text-red-800">
                {{ session('error') }}
            </div>
        @endif

        {{-- Pendientes --}}
        <div class="bg-white p-4 rounded shadow">
            <h3 class="font-semibold mb-3">Pendientes</h3>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="text-left border-b">
                            <th class="py-2 pr-3">Fecha</th>
                            <th class="py-2 pr-3">Hora</th>
                            <th class="py-2 pr-3">Nombre</th>
                            <th class="py-2 pr-3">Email</th>
                            <th class="py-2 pr-3">Notas</th>
                            <th class="py-2 pr-3">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pendientes as $b)
                            <tr class="border-b">
                                <td class="py-2 pr-3">{{ \Carbon\Carbon::parse($b->class_date)->format('d/m/Y') }}</td>
                                <td class="py-2 pr-3">{{ substr($b->class_time, 0, 5) }}</td>
                                <td class="py-2 pr-3">{{ $b->name }}</td>
                                <td class="py-2 pr-3">
                                    <a href="mailto:{{ $b->email }}" class="underline text-blue-600">
                                        {{ $b->email }}
                                    </a>
                                </td>
                                <td class="py-2 pr-3">{{ $b->notes }}</td>
                                <td class="py-2 pr-3">
                                    <div class="flex items-center gap-2">
                                        {{-- Confirmar --}}
                                        <form method="POST" action="{{ route('admin.bookings.confirm', $b) }}">
                                            @csrf
                                            @method('PATCH')
                        <div class="flex items-center gap-2">
                            <input name="meeting_url" type="url" placeholder="https://meet.google.com/xxx-xxxx-xxx" value="{{ $b->meeting_url ?? '' }}" class="px-2 py-1 border rounded text-sm w-64" aria-label="URL videollamada" />
                            <button type="submit"
                                class="px-3 py-1 rounded bg-green-600 text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                Confirmar
                            </button>
                        </div>
                                        </form>

                                        {{-- Cancelar --}}
                                        <form method="POST" action="{{ route('admin.bookings.cancel', $b) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                    onclick="return confirm('¿Seguro que deseas cancelar esta reserva?')"
                                                    class="px-3 py-1 rounded bg-red-600 text-white hover:bg-red-700">
                                                Cancelar
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-3 text-gray-500 text-center">
                                    No hay reservas pendientes.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Confirmadas (últimas 50) --}}
        <div class="bg-white p-4 rounded shadow">
            <h3 class="font-semibold mb-3">Confirmadas (recientes)</h3>
                <ul class="list-disc pl-6 text-sm">
                @forelse ($confirmadas as $b)
                    <li class="mb-1">
                        {{ \Carbon\Carbon::parse($b->class_date)->format('d/m/Y') }}
                        {{ substr($b->class_time, 0, 5) }} — {{ $b->name }} ({{ $b->email }})
                        @if(!empty($b->meeting_url))
                            — <a href="{{ route('bookings.join', $b) }}" target="_blank" class="text-blue-600 underline">Unirse</a>
                        @endif
                    </li>
                @empty
                    <li class="text-gray-500">Sin confirmadas recientes.</li>
                @endforelse
            </ul>
        </div>

        {{-- Canceladas (últimas 50) --}}
        <div class="bg-white p-4 rounded shadow">
            <h3 class="font-semibold mb-3">Canceladas (recientes)</h3>
            <ul class="list-disc pl-6 text-sm">
                @forelse ($canceladas as $b)
                    <li>
                        {{ \Carbon\Carbon::parse($b->class_date)->format('d/m/Y') }}
                        {{ substr($b->class_time, 0, 5) }} — {{ $b->name }} ({{ $b->email }})
                    </li>
                @empty
                    <li class="text-gray-500">Sin canceladas recientes.</li>
                @endforelse
            </ul>
        </div>
    </div>
</x-app-layout>
