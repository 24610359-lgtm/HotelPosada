<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Habitacion extends Model
{
    protected $table = 'habitaciones';
    protected $primaryKey = 'id_habitacion';
    public $timestamps = false;

    protected $fillable = [
        'numero',
        'id_tipo',
        'estado'
    ];

    public function tipo()
    {
        return $this->belongsTo(TipoHabitacion::class, 'id_tipo', 'id_tipo');
    }

    public function reservaciones()
    {
        return $this->hasMany(Reservacion::class, 'id_habitacion', 'id_habitacion');
    }
    
}
