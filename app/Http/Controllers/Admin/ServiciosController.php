<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Servicio;
use Illuminate\Http\Request;

class ServiciosController extends Controller
{
    public function index()
    {
        $servicios = Servicio::all();
        return view('Admin.servicios.servicios', compact('servicios'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|max:50',
            'icono' => 'nullable|max:100'
        ]);

        Servicio::create($request->all());

        return back()->with('ok', 'Servicio creado correctamente');
    }

    public function update(Request $request, $id)
    {
        $servicio = Servicio::findOrFail($id);

        $request->validate([
            'nombre' => 'required|max:50',
            'icono' => 'nullable|max:100'
        ]);

        $servicio->update($request->all());

        return back()->with('ok', 'Servicio actualizado');
    }

    public function destroy($id)
    {
        Servicio::findOrFail($id)->delete();
        return back()->with('ok', 'Servicio eliminado');
    }
}
