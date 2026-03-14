<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Seguridad;
use App\Http\Controllers\Controller;
use App\Models\Servicio;
use Illuminate\Http\Request;
use App\Models\TipoHabitacion;
use Symfony\Component\HttpFoundation\JsonResponse;

class TiposHabitacionController extends Controller
{
    private function soloAdmin()
    {
        if (!Seguridad::es('admin')) {
            return redirect('/Admin')
                ->with('error', 'No tienes permisos para esta sección');
        }
        return null;
    }

    public function index()
    {
        if ($r = $this->soloAdmin()) return $r;

        $tipos = TipoHabitacion::all();
        return view('admin.tipos.index', compact('tipos'));
    }

    public function crear()
    {
        if ($r = $this->soloAdmin()) return $r;

        return view('admin.tipos.crear');
    }

    public function guardar(Request $request)
    {
        if ($r = $this->soloAdmin()) return $r;

        $request->validate([
            'nombre' => 'required|min:3',
            'precio_noche' => 'required|numeric|min:0',
            'capacidad'  => 'required|numeric|min:0'
        ]);

        TipoHabitacion::create($request->only('nombre', 'descripcion', 'precio_noche', 'capacidad'));

        return redirect('/Admin/Tipos-Habitacion')
            ->with('ok', 'Tipo de habitación creado');
    }

    public function editar($id)
    {
        if ($r = $this->soloAdmin()) return $r;

        $tipo = TipoHabitacion::findOrFail($id);
        return view('Admin.tipos.editar', compact('tipo'));
    }

    public function actualizar(Request $request, $id)
    {
        if ($r = $this->soloAdmin()) return $r;

        $request->validate([
            'nombre' => 'required|min:3',
            'precio_noche' => 'required|numeric|min:0',
            'capacidad' => 'required|numeric|min:0'
        ]);

        $tipo = TipoHabitacion::findOrFail($id);
        $tipo->update($request->only('nombre', 'descripcion', 'precio_noche', 'capacidad'));

        return redirect('/Admin/Tipos-Habitacion')
            ->with('ok', 'Tipo actualizado');
    }

    public function eliminar($id)
    {
        if ($r = $this->soloAdmin()) return $r;

        $tipo = TipoHabitacion::findOrFail($id);
        
        // Eliminar los registros de la tabla pivote antes de eliminar el tipo
        $tipo->servicios()->detach();
        
        $tipo->delete();

        return redirect('/Admin/Tipos-Habitacion')
            ->with('ok', 'Tipo eliminado');
    }

    public function servicios($id): JsonResponse
    {
        $tipo = TipoHabitacion::with('servicios')->findOrFail($id);
        $todos = Servicio::all();

        return response()->json([
            'asignados' => $tipo->servicios,
            'todos' => $todos
        ]);
    }

    public function asignarServicio(Request $request, $id): JsonResponse
    {
        $tipo = TipoHabitacion::findOrFail($id);
        $tipo->servicios()->syncWithoutDetaching([$request->id_servicio]);

        return response()->json(['ok' => true]);
    }

    public function quitarServicio($id, $servicio): JsonResponse
    {
        $tipo = TipoHabitacion::findOrFail($id);
        $tipo->servicios()->detach($servicio);

        return response()->json(['ok' => true]);
    }
}
