<div class="admin-header">
    <h1>Panel de Administración</h1>
    <p>Gestiona tu hotel desde aquí</p>
</div>

<nav class="admin-subnav">
    <a href="{{ url('/Admin')}}" class="{{ request()->is(patterns: '/Admin') ? 'activo' : '' }}">Dashboard</a>
    <a href="/Admin/Reservas">Reservas</a>
    <a href="{{ url('/Admin/Habitaciones') }}">Habitaciones</a>
    <a href="{{ url('/Admin/Tipos-Habitacion') }}"
        class="{{ request()->is(patterns: '/Admin/Tipos-Habitacion*') ? 'activo' : '' }}">Categorías</a>
    <a href="{{ url('/Admin/Servicios') }}">Servicios</a>
</nav>