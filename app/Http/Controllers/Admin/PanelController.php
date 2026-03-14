<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Helpers\Seguridad;

class PanelController extends Controller
{
    public function index()
    {
        if (!Seguridad::puede('recepcion')) {
            return redirect('/Home')
                ->with('error', 'No tienes permisos para acceder a esta sección');
        }

        return view('admin.Panel');
    }
}
