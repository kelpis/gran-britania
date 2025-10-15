<x-app-layout>
  <div class="p-6">
    <h1 class="text-2xl font-bold">Panel de Administrador</h1>
    <p class="mt-2">Bienvenida/o, {{ Auth::user()->name }}. Solo los administradores pueden ver esto.</p>
  </div>
</x-app-layout>