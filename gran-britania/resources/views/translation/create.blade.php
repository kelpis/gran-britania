<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">Solicitud de traducción</h2>
  </x-slot>

  <div class="py-6">
    <div class="max-w-xl mx-auto sm:px-6 lg:px-8">

      @if (session('ok'))
        <div class="mb-4 bg-green-100 text-green-700 p-3 rounded">{{ session('ok') }}</div>
      @endif
      @if ($errors->any())
        <div class="mb-4 bg-red-100 text-red-700 p-3 rounded">
          <ul class="list-disc ml-5">
            @foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach
          </ul>
        </div>
      @endif

      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
        <form method="POST" action="{{ route('translation.store') }}" enctype="multipart/form-data" class="space-y-4" data-grecaptcha="v3" data-recaptcha-action="translation">
          @csrf

          <div>
            <label class="block mb-1 font-medium">Nombre</label>
            <input name="name" class="w-full border rounded p-2" value="{{ old('name') }}" required>
          </div>

          <div>
            <label class="block mb-1 font-medium">Email</label>
            <input type="email" name="email" class="w-full border rounded p-2" value="{{ old('email') }}" required>
          </div>

          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block mb-1 font-medium">Idioma origen</label>
              <input name="source_lang" class="w-full border rounded p-2" value="{{ old('source_lang') }}" required>
            </div>
            <div>
              <label class="block mb-1 font-medium">Idioma destino</label>
              <input name="target_lang" class="w-full border rounded p-2" value="{{ old('target_lang') }}" required>
            </div>
          </div>

          <div>
            <label class="block mb-1 font-medium">Urgencia</label>
            <select name="urgency" class="w-full border rounded p-2">
              <option value="normal" {{ old('urgency')==='normal'?'selected':'' }}>Normal</option>
              <option value="alta" {{ old('urgency')==='alta'?'selected':'' }}>Alta</option>
            </select>
          </div>

          <div>
            <label class="block mb-1 font-medium">Archivo</label>
            <input type="file" name="file" class="w-full" required>
          </div>

          <div>
            <label class="block mb-1 font-medium">Observaciones (opcional)</label>
            <textarea name="comments" rows="4" class="w-full border rounded p-2">{{ old('comments') }}</textarea>
          </div>

          <div class="flex items-start gap-3">
            <label class="inline-flex items-center text-sm">
              <input type="checkbox" name="gdpr" value="1" required class="border">
              <span class="ml-2">He leído y acepto la <a href="{{ route('privacy') }}" class="text-blue-600 underline">política de protección de datos</a>.</span>
            </label>
          </div>

          {{-- reCAPTCHA v3: token se inyecta por JS desde layout cuando data-grecaptcha="v3" está presente --}}

          <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Enviar</button>
        </form>
      </div>
    </div>
  </div>
</x-app-layout>
