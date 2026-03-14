<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoHabitacion extends Model
{
    protected $table = 'tipos_habitacion';
    protected $primaryKey = 'id_tipo';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'descripcion',
        'precio_noche',
        'capacidad'
    ];
    public function servicios()
    {
        return $this->belongsToMany(
            \App\Models\Servicio::class,
            'tipos_habitacion_servicios',
            'id_tipo',
            'id_servicio'
        );
    }
}
