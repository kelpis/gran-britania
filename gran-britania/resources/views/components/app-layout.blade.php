@props(['header' => null])

{{-- Pasamos $slot y $header al layout para que estén disponibles allí --}}
@include('layouts.app', ['header' => $header, 'slot' => $slot])
