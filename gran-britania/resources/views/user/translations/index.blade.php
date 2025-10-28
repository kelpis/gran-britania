<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Mis traducciones</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-4">
            @if(session('ok'))
                <div class="bg-green-100 text-green-800 p-3 rounded">{{ session('ok') }}</div>
            @endif

            @if($items->isEmpty())
                <div class="bg-white p-6 shadow sm:rounded-lg">No tienes solicitudes de traducción.</div>
            @else
                <div class="bg-white p-6 shadow sm:rounded-lg">
                    <table class="w-full table-auto">
                        <thead>
                            <tr class="text-left border-b">
                                <th class="py-2">Enviado</th>
                                <th class="py-2">Idiomas</th>
                                <th class="py-2">Urgencia</th>
                                <th class="py-2">Comentarios</th>
                                <th class="py-2">Archivo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $t)
                                <tr class="border-b">
                                    <td class="py-2">{{ \Carbon\Carbon::parse($t->created_at)->format('d/m/Y H:i') }}</td>
                                    <td class="py-2">{{ strtoupper($t->source_lang) }} → {{ strtoupper($t->target_lang) }}</td>
                                    <td class="py-2">{{ ucfirst($t->urgency) }}</td>
                                    <td class="py-2">{{ \Illuminate\Support\Str::limit($t->comments, 80) }}</td>
                                    <td class="py-2">
                                        @if($t->file_path && \Illuminate\Support\Facades\Storage::disk('local')->exists($t->file_path))
                                            <a href="{{ route('user.translations.download', $t->id) }}" class="text-blue-600">Descargar</a>
                                        @else
                                            —
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
