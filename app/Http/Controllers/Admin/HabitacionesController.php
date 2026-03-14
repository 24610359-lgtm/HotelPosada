<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Habitacion;
use App\Models\TipoHabitacion;
use Illuminate\Http\Request;

class HabitacionesController extends Controller
{
    public function index()
    {
        $habitaciones = Habitacion::with('tipo')->get();
        $tipos = TipoHabitacion::all();

        return view('Admin.Habitaciones.AgregarHabitacion', compact('habitaciones', 'tipos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'numero' => 'required|unique:habitaciones,numero',
            'id_tipo' => 'required',
            'estado' => 'required'
        ]);

        Habitacion::create($request->all());

        return back()->with('ok', 'Habitación creada');
    }

    public function update(Request $request, $id)
    {
        $hab = Habitacion::findOrFail($id);

        $request->validate([
            'numero' => "required|unique:habitaciones,numero,$id,id_habitacion",
            'id_tipo' => 'required',
            'estado' => 'required'
        ]);

        $hab->update($request->all());

        return back()->with('ok', 'Habitación actualizada');
    }

    public function destroy($id)
    {
        $hab = Habitacion::with('reservaciones')->findOrFail($id);

        if ($hab->reservaciones->count() > 0) {
            return back()->with('error', 'No se puede eliminar, tiene reservaciones');
        }

        $hab->delete();

        return back()->with('ok', 'Habitación eliminada');
    }
}
