@if(session()->has('usuario'))
    <h1 style="font-size: 1.2rem; font-weight: 500;">
        {{ session('usuario.nombre') }} {{ session('usuario.apellidos') }}
        · {{ session('usuario.email') }}
        · {{ session('usuario.telefono') ?? 'Sin teléfono' }}
        · Rol: {{ session('usuario.rol') }}
    </h1>
@endif
