<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class UbicacionController extends Controller
{
    public function show(): JsonResponse
    {
        return response()->json([
            'hotel' => 'Hotel Posada De La Luz',
            'direccion' => 'C. Aldama s/n, Col. Centro, 93570 Tecolutla, Ver.',
            'latitud' => 20.47766,
            'longitud' => -97.00765,
        ]);
    }
}
