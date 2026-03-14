<?php

namespace App\Http\Controllers;

use App\Models\TipoHabitacion;
use App\Models\Habitacion;
use App\Models\Reservacion;
use App\Models\Servicio;
use App\Helpers\Seguridad;
use Illuminate\Http\Request;

class ReservacionController extends Controller
{
    /**
     * Muestra la interfaz de reservación con las categorías de habitaciones
     */
    public function index()
    {
        // Obtener todos los tipos de habitación con sus servicios relacionados
        $tiposHabitacion = TipoHabitacion::with('servicios')->get();
        
        // Obtener las habitaciones disponibles de cada tipo
        $habitacionesDisponibles = Habitacion::where('estado', 'disponible')
            ->with('tipo')
            ->get()
            ->groupBy('id_tipo');

        return view('Reservacion', compact('tiposHabitacion', 'habitacionesDisponibles'));
    }

    /**
     * Busca habitaciones disponibles para un tipo específico
     */
    public function disponibilidad(Request $request)
    {
        $request->validate([
            'id_tipo' => 'required|exists:tipos_habitacion,id_tipo',
            'fecha_entrada' => 'required|date|after_or_equal:today',
            'fecha_salida' => 'required|date|after:fecha_entrada',
        ]);

        $idTipo = $request->id_tipo;
        $fechaEntrada = $request->fecha_entrada;
        $fechaSalida = $request->fecha_salida;

        // Obtener habitaciones de este tipo
        $habitaciones = Habitacion::where('id_tipo', $idTipo)->get();

        // Filtrar las que no tienen reservaciones que se crucen con las fechas solicitadas
        $habitacionesDisponibles = $habitaciones->filter(function ($habitacion) use ($fechaEntrada, $fechaSalida) {
            // Verificar si hay reservaciones activas que se crucen con las fechas
            $reservacionesConflicto = Reservacion::where('id_habitacion', $habitacion->id_habitacion)
                ->where('estado', 'activa')
                ->where(function ($query) use ($fechaEntrada, $fechaSalida) {
                    $query->whereBetween('fecha_entrada', [$fechaEntrada, $fechaSalida])
                        ->orWhereBetween('fecha_salida', [$fechaEntrada, $fechaSalida])
                        ->orWhere(function ($query) use ($fechaEntrada, $fechaSalida) {
                            $query->where('fecha_entrada', '<=', $fechaEntrada)
                                ->where('fecha_salida', '>=', $fechaSalida);
                        });
                })
                ->count();

            return $reservacionesConflicto === 0;
        });

        return response()->json([
            'disponibles' => $habitacionesDisponibles->count(),
            'habitaciones' => $habitacionesDisponibles->values()
        ]);
    }

    /**
     * Crea una nueva reservación
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_habitacion' => 'required|exists:habitaciones,id_habitacion',
            'fecha_entrada' => 'required|date|after_or_equal:today',
            'fecha_salida' => 'required|date|after:fecha_entrada',
            'metodo_pago' => 'required|in:efectivo,tarjeta',
        ]);

        // Verificar que la habitación esté disponible en esas fechas
        $conflicto = Reservacion::where('id_habitacion', $request->id_habitacion)
            ->where('estado', 'activa')
            ->where(function ($query) use ($request) {
                $query->whereBetween('fecha_entrada', [$request->fecha_entrada, $request->fecha_salida])
                    ->orWhereBetween('fecha_salida', [$request->fecha_entrada, $request->fecha_salida])
                    ->orWhere(function ($query) use ($request) {
                        $query->where('fecha_entrada', '<=', $request->fecha_entrada)
                            ->where('fecha_salida', '>=', $request->fecha_salida);
                    });
            })
            ->exists();

        if ($conflicto) {
            return response()->json([
                'mensaje' => 'La habitación no está disponible en las fechas seleccionadas.'
            ], 422);
        }

        // Obtener el usuario actual usando Seguridad helper
        $usuario = Seguridad::usuario();
        
        // Si no hay usuario autenticado, usar un usuario por defecto (para pruebas)
        // En producción, debería requerir autenticación
        if (!$usuario) {
            // Buscar un usuario cliente existente o crear la reservación sin usuario
            $idUsuario = 1; // Usuario por defecto
        } else {
            $idUsuario = $usuario['id'] ?? 1;
        }

        // Calcular el número de noches y el precio total
        $habitacion = Habitacion::with('tipo')->find($request->id_habitacion);
        $fechaEntrada = new \DateTime($request->fecha_entrada);
        $fechaSalida = new \DateTime($request->fecha_salida);
        $noches = $fechaEntrada->diff($fechaSalida)->days;
        $precioTotal = $noches * $habitacion->tipo->precio_noche;

        // Crear la reservación
        $reservacion = Reservacion::create([
            'id_usuario' => $idUsuario,
            'id_habitacion' => $request->id_habitacion,
            'fecha_entrada' => $request->fecha_entrada,
            'fecha_salida' => $request->fecha_salida,
            'estado' => 'activa',
        ]);

        return response()->json([
            'mensaje' => 'Reservación creada correctamente',
            'reservacion' => $reservacion,
            'noches' => $noches,
            'precio_total' => $precioTotal,
            'precio_por_noche' => $habitacion->tipo->precio_noche
        ]);
    }

    /**
     * Muestra los detalles de un tipo de habitación específico
     */
    public function show($id)
    {
        $tipoHabitacion = TipoHabitacion::with('servicios')->findOrFail($id);
        $habitaciones = Habitacion::where('id_tipo', $id)
            ->where('estado', 'disponible')
            ->get();

        return response()->json([
            'tipo' => $tipoHabitacion,
            'habitaciones_disponibles' => $habitaciones
        ]);
    }
}
