@extends('layouts.app')

@section('content')
  <div class="max-w-4xl mx-auto py-12 px-4">
    <h1 class="text-3xl font-bold mb-6">Servicios</h1>

    <section class="mb-8">
      <h2 class="text-2xl font-semibold mb-3">Clases online</h2>
      <p class="text-gray-600 mb-4">Ofrecemos clases online individuales y grupales para mejorar habilidades lingüísticas y comunicación profesional.</p>

      <div class="grid md:grid-cols-2 gap-4">
        @foreach($services['classes'] as $s)
          <div class="border rounded p-4">
            <h3 class="font-semibold">{{ $s['name'] }}</h3>
            @if(!empty($s['duration']))<div class="text-sm text-gray-500">Duración: {{ $s['duration'] }}</div>@endif
            @if(!empty($s['price']))<div class="text-sm text-gray-500">Precio: {{ $s['price'] }}</div>@endif
            <p class="mt-2 text-gray-700">{{ $s['description'] }}</p>
          </div>
        @endforeach
      </div>
    </section>

    <section>
      <h2 class="text-2xl font-semibold mb-3">Servicios de traducción</h2>
      <p class="text-gray-600 mb-4">Traducciones profesionales y juradas. Suba su documento y le informaremos del presupuesto.</p>

      <div class="grid md:grid-cols-2 gap-4">
        @foreach($services['translations'] as $s)
          <div class="border rounded p-4">
            <h3 class="font-semibold">{{ $s['name'] }}</h3>
            @if(!empty($s['turnaround']))<div class="text-sm text-gray-500">Plazo: {{ $s['turnaround'] }}</div>@endif
            @if(!empty($s['price']))<div class="text-sm text-gray-500">Precio estimado: {{ $s['price'] }}</div>@endif
            <p class="mt-2 text-gray-700">{{ $s['description'] }}</p>
          </div>
        @endforeach
      </div>
    </section>

    <div class="mt-8">
      <a href="{{ route('translation.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded">Solicitar presupuesto de traducción</a>
      <a href="{{ route('bookings.create') }}" class="ml-3 px-4 py-2 border rounded">Reservar una clase</a>
    </div>
  </div>
@endsection
