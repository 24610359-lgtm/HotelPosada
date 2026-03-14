<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use App\Models\Categoria;
use App\Models\TipoHabitacion;

class ReservaTestController extends Controller
{
    public function vista()
    {
        $categorias = TipoHabitacion::all(); // solo consulta
        return view('reservacion_test', compact('categorias'));
    }

    public function crearPago(Request $request)
    {
        $request->validate([
            'monto' => 'required|numeric|min:1',
        ]);

        Stripe::setApiKey(env('STRIPE_SECRET'));

        $intent = PaymentIntent::create([
            'amount' => (int) round($request->monto * 100),
            'currency' => 'mxn',
            'payment_method_types' => ['card'],
        ]);

        return response()->json([
            'clientSecret' => $intent->client_secret
        ]);
    }
}
