<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Traducciones</h2>
    </x-slot>
    <div class="py-6 max-w-5xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow sm:rounded-lg p-6">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left">
                        <th class="p-2">Fecha</th>
                        <th class="p-2">Nombre</th>
                        <th class="p-2">Email</th>
                        <th class="p-2">Idiomas</th>
                        <th class="p-2">Urgencia</th>
                        <th class="p-2">Archivo</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $tr)
                        <tr class="border-t">
                            <td class="p-2">{{ $tr->created_at->format('d/m/Y H:i') }}</td>
                            <td class="p-2">{{ $tr->name }}</td>
                            <td class="p-2">{{ $tr->email }}</td>
                            <td class="p-2">{{ $tr->source_lang }} → {{ $tr->target_lang }}</td>
                            <td class="p-2">{{ ucfirst($tr->urgency) }}</td>
                            <td class="p-2">
                                <a href="{{ route('admin.translations.download', $tr->id) }}"
                                    class="text-blue-600 hover:underline">
                                    Descargar
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-4">{{ $items->links() }}</div>
        </div>
    </div>
</x-app-layout>