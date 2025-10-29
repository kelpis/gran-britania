@extends('layouts.app')

@section('content')
  <div class="max-w-4xl mx-auto py-12 px-4">
    <h1 class="text-3xl font-bold mb-4">Política de cookies</h1>

    <p class="mb-4 text-gray-700 dark:text-gray-300">En esta web utilizamos cookies propias y de terceros para mejorar la experiencia del usuario, ofrecer funcionalidades y realizar análisis estadísticos. A continuación explicamos qué tipos de cookies usamos, por qué y cómo puedes gestionar o revocar tu consentimiento.</p>

    <h2 class="text-xl font-semibold mt-6 mb-2">¿Qué son las cookies?</h2>
    <p class="text-gray-700 dark:text-gray-300 mb-4">Las cookies son pequeños archivos de texto que se almacenan en tu dispositivo cuando visitas un sitio web. Permiten que el sitio recuerde tus preferencias y, en algunos casos, recopilen información anónima sobre cómo interactúas con la web.</p>

    <h2 class="text-xl font-semibold mt-6 mb-2">Tipos de cookies que utilizamos</h2>
    <ul class="list-disc ml-6 mb-4 text-gray-700 dark:text-gray-300">
      <li><strong>Esenciales:</strong> necesarias para el funcionamiento del sitio (p. ej. sesiones). No requieren consentimiento y se activan automáticamente.</li>
      <li><strong>Preferencias:</strong> recuerdan tus opciones (idioma, apariencia) para mejorar la experiencia.</li>
      <li><strong>Estadísticas / Analytics:</strong> se usan para recopilar datos anónimos sobre el uso (p. ej. Google Analytics). Estas cookies se activan únicamente si aceptas su uso.</li>
      <li><strong>Marketing:</strong> se usan para personalizar publicidad a través de proveedores externos. Se activan solo con tu consentimiento.</li>
    </ul>

    <h2 class="text-xl font-semibold mt-6 mb-2">Proveedores y ejemplos</h2>
    <p class="text-gray-700 dark:text-gray-300 mb-4">Podemos utilizar proveedores de terceros como Google (Analytics) o plataformas de vídeo que pueden establecer cookies cuando reproduces contenido incrustado. Los proveedores y la lista exacta pueden cambiar; si necesitas detalles específicos sobre cada cookie puedes contactarnos (ver abajo).</p>

    <h2 class="text-xl font-semibold mt-6 mb-2">¿Cómo gestionar o revocar el consentimiento?</h2>
    <p class="text-gray-700 dark:text-gray-300 mb-4">Puedes aceptar o rechazar las cookies desde el banner que aparece la primera vez que visitas la página. Para revocar o cambiar tu elección:</p>
    <ol class="list-decimal ml-6 mb-4 text-gray-700 dark:text-gray-300">
      <li>Busca la cookie <code>cookies_consent</code> en las cookies del navegador y elimínala, o</li>
      <li>Haz clic en el enlace de la política en el pie de página y sigue las instrucciones para revocar el consentimiento, o</li>
      <li>Si estás registrado y tu cuenta lo permite, podremos almacenar tu elección en tu perfil. Contacta con nosotros para más información.</li>
    </ol>

    <h2 class="text-xl font-semibold mt-6 mb-2">Duración de las cookies</h2>
    <p class="text-gray-700 dark:text-gray-300 mb-4">La cookie de consentimiento que usamos se guarda durante 365 días. Las cookies de sesión se eliminan al cerrar el navegador; otras cookies de terceros pueden tener sus propias fechas de expiración.</p>

    <h2 class="text-xl font-semibold mt-6 mb-2">Contacto</h2>
    <p class="text-gray-700 dark:text-gray-300 mb-4">Si tienes dudas sobre nuestra política de cookies o quieres que borremos tus consentimientos almacenados, escríbenos a <a href="mailto:info@example.com" class="underline">info@example.com</a>. Sustituye este correo por el real si procede.</p>

    <div class="mt-8">
      <a href="/" class="px-4 py-2 border rounded">Volver al inicio</a>
    </div>
  </div>
@endsection
