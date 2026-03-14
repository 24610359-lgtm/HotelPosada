<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Session;

class Seguridad
{
    public static function usuario()
    {
        return Session::get('usuario');
    }

    public static function autenticado()
    {
        return Session::has('usuario');
    }

    public static function rol()
    {
        return Session::get('usuario.rol');
    }

    public static function es($rol)
    {
        return self::rol() === $rol;
    }

    /**
     * admin > recepcion > limpieza > cliente
     */
    public static function puede($rolRequerido)
    {
        if (!self::autenticado()) {
            return false;
        }

        $jerarquia = [
            'admin' => 4,
            'recepcion' => 3,
            'limpieza' => 2,
            'cliente' => 1,
        ];

        $rolUsuario = self::rol();

        // Validaciones de seguridad
        if (!isset($jerarquia[$rolUsuario]) || !isset($jerarquia[$rolRequerido])) {
            return false;
        }

        return $jerarquia[$rolUsuario] >= $jerarquia[$rolRequerido];
    }
}
