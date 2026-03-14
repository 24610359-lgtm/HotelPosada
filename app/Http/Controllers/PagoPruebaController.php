<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagoPruebaController extends Controller
{
    public function index()
    {
        return view('pago-prueba');
    }

    public function procesar(Request $request)
    {
        // simulación de pago
        return response()->json([
            'success' => true,
            'message' => 'Pago realizado correctamente (modo prueba)'
        ]);
    }
}
