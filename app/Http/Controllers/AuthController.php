<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Models\Usuario;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function vista(Request $request)
    {
        // Guardar la URL donde quería ir el usuario antes del login
        if ($request->has('redirect')) {
            $redirect = $this->normalizarRedirect($request->redirect);
            if ($redirect) {
                Session::put('url_redirect', $redirect);
            }
        }
        
        return view('auth.Acceso');
    }

    public function login(Request $request)
    {
        $request->validate(
            [
                'email' => 'required|email',
                'password' => 'required|min:6'
            ],
            [
                'email.required' => 'El correo es obligatorio',
                'email.email' => 'El correo no es válido',
                'password.required' => 'La contraseña es obligatoria',
                'password.min' => 'Mínimo 6 caracteres'
            ]
        );
        try{
            $usuario = Usuario::where('email', $request->email)
            ->where('activo', true)
            ->first();

        if (!$usuario || !Hash::check($request->password, $usuario->password)) {
            return back()
                ->withErrors(['email' => 'Correo o contraseña incorrectos'])
                ->withInput()
                ->with('form', 'login');
        }

        Session::put('usuario', [
            'id' => $usuario->id,
            'nombre' => $usuario->nombre,
            'apellidos' => $usuario->apellidos,
            'telefono' => $usuario->telefono,
            'email' => $usuario->email,
            'rol' => $usuario->rol,
        ]);

        // Verificar si hay una URL guardada para redirigir
        $redirectUrl = $this->normalizarRedirect(Session::get('url_redirect'));
        Session::forget('url_redirect');
        
        // Redirigir a la URL guardada o a Home por defecto
        return redirect($redirectUrl ?? '/Home');
        } catch (\Exception $e) {
            return back()
                ->withErrors(['email' => 'Error al iniciar sesión. Intente más tarde.'])
                ->withInput();
        }
        
    }



    public function registro(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nombre' => 'required|min:2',
                'apellidos' => 'required|min:2',
                'telefono' => 'nullable|min:8',
                'email' => 'required|email|unique:usuarios,email',
                'password' => 'required|min:6'
            ],
            [
                'nombre.required' => 'El nombre es obligatorio',
                'apellidos.required' => 'Los apellidos son obligatorios',
                'email.required' => 'El correo es obligatorio',
                'email.unique' => 'Este correo ya existe',
                'password.min' => 'Mínimo 6 caracteres'
            ]
        );

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput()
                ->with('form', 'registro');
        }

        Usuario::create([
            'nombre' => $request->nombre,
            'apellidos' => $request->apellidos,
            'telefono' => $request->telefono,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'activo' => true
        ]);

        return redirect('/Login')->with('ok', 'Cuenta creada correctamente');
    }


    public function cerrarSesion()
    {
        Session::forget('usuario');
        Session::invalidate();
        Session::regenerateToken();

        return redirect('/Home');
    }

    private function normalizarRedirect(?string $redirect): ?string
    {
        if (!$redirect) {
            return null;
        }

        $redirect = trim(urldecode($redirect));
        if ($redirect === '') {
            return null;
        }

        if (preg_match('/^https?:\/\//i', $redirect)) {
            $parsed = parse_url($redirect);
            $redirect = ($parsed['path'] ?? '/')
                . (isset($parsed['query']) ? '?' . $parsed['query'] : '');
        }

        if ($redirect[0] !== '/') {
            $redirect = '/' . $redirect;
        }

        $basePath = parse_url(url('/'), PHP_URL_PATH) ?: '';
        $basePath = rtrim($basePath, '/');

        if ($basePath !== '') {
            while ($redirect === $basePath || str_starts_with($redirect, $basePath . '/')) {
                $redirect = substr($redirect, strlen($basePath)) ?: '/';
            }
        }

        $parts = explode('?', $redirect, 2);
        $path = preg_replace('#/+#', '/', $parts[0]) ?: '/';
        $query = isset($parts[1]) ? '?' . $parts[1] : '';

        return $path . $query;
    }
}
