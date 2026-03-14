<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservacion extends Model
{
    protected $table = 'reservaciones';
    protected $primaryKey = 'id_reservacion';
    public $timestamps = false;

    protected $fillable = [
        'id_usuario',
        'id_habitacion',
        'fecha_entrada',
        'fecha_salida',
        'estado'
    ];

    public function habitacion()
    {
        return $this->belongsTo(Habitacion::class, 'id_habitacion', 'id_habitacion');
    }
}
