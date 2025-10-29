<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Preparamos una lista simple de servicios para la vista pública.
        // Si en el futuro quieres persistirlos en BD, podemos leer desde un modelo Service.
        $services = [
            'classes' => [
                ['name' => 'Clase individual (1 hora)', 'duration' => '60 min', 'price' => '€30', 'description' => 'Clase privada online adaptada a tus necesidades.'],
                ['name' => 'Pack 5 clases', 'duration' => '5 x 60 min', 'price' => '€140', 'description' => 'Mejora rápida con seguimiento personalizado.'],
            ],
            'translations' => [
                ['name' => 'Traducción general', 'turnaround' => '48-72 h', 'price' => 'Desde €0.08/ palabra', 'description' => 'Traducción profesional en varios idiomas.'],
                ['name' => 'Traducción jurada', 'turnaround' => '5-7 días', 'price' => 'Precio a medida', 'description' => 'Documentos oficiales con certificación.'],
            ],
        ];

        return view('services.index', compact('services'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Service $service)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Service $service)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Service $service)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Service $service)
    {
        //
    }
}
