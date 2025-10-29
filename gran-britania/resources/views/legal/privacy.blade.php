@extends('layouts.app')

@section('content')
  <div class="max-w-3xl mx-auto py-12 px-4">
    <h1 class="text-3xl font-bold mb-4">Política de protección de datos (texto de ejemplo)</h1>

    <p class="mb-4 text-gray-700">En Gran Britania tratamos tus datos personales con la máxima confidencialidad y de acuerdo con la normativa vigente de protección de datos. Este texto es un ejemplo orientativo; adapta el contenido real según tu asesoría legal.</p>

    <h2 class="font-semibold mt-6">Responsable del tratamiento</h2>
    <p class="text-gray-700">Gran Britania S.L. — contacto: info@ejemplo.com</p>

    <h2 class="font-semibold mt-6">Finalidad del tratamiento</h2>
    <p class="text-gray-700">Tratamos los datos facilitados a fin de gestionar solicitudes de contacto, reservas de clases y presupuestos de traducción, así como para enviarte comunicaciones relacionadas con dichos servicios.</p>

    <h2 class="font-semibold mt-6">Legitimación</h2>
    <p class="text-gray-700">La base legal para el tratamiento es el consentimiento explícito que nos das al marcar el checkbox en los formularios y la ejecución de las medidas precontractuales en caso de solicitudes o reservas.</p>

    <h2 class="font-semibold mt-6">Conservación</h2>
    <p class="text-gray-700">Conservaremos los datos mientras sean necesarios para la finalidad por la que se recabaron y mientras exista un interés legítimo o obligación legal que lo requiera.</p>

    <h2 class="font-semibold mt-6">Derechos</h2>
    <p class="text-gray-700">Tienes derecho a acceder, rectificar, suprimir, oponerte y solicitar la portabilidad de tus datos, así como a restringir el tratamiento. Para ejercitar estos derechos puedes escribir a info@ejemplo.com.</p>

    <h2 class="font-semibold mt-6">Contacto</h2>
    <p class="text-gray-700">Si tienes dudas sobre esta política, contacta con nosotros en info@ejemplo.com.</p>

    <div class="mt-8">
      <a href="{{ url()->previous() }}" class="px-4 py-2 border rounded">Volver</a>
    </div>
  </div>
@endsection
