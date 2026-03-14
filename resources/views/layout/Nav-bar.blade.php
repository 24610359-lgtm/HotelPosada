@php use App\Helpers\Seguridad; @endphp

<header class="navbar-fija">
    <div class="contenedor-nav">
        <div class="logo">
            🏨 <span>Posada De La Luz</span>
        </div>

        <nav class="nav-links">
            {{-- Publicas --}}
            <a href="{{ url('/Home') }}" class="{{ request()->is('Home') ? 'activo' : '' }}">Home</a>
            <a href="{{ url('/reservaciones') }}" class="{{ request()->is('reservaciones*') ? 'activo' : '' }}">Reservar </a>
            {{--
            <a href="{{ url('/Habitaciones') }}"
                class="{{ request()->is('Habitaciones*') ? 'activo' : '' }}">Habitaciones</a>
            <a href="{{ url('/youtube') }}" class="{{ request()->is('youtube*') ? 'activo' : '' }}">YouTube</a>
            --}}
            <a href="{{ url('/Redes') }}" class="{{ request()->is('Redes*') ? 'activo' : '' }}">Redes</a>

            {{-- Cliente 
            @if(Seguridad::puede('cliente'))

            @endif
            --}}

            {{-- Recepcion --}}
            @if(Seguridad::puede('recepcion'))
                <a href="{{ url('/Recepcion') }}" class="{{ request()->is('Recepcion*') ? 'activo' : '' }}">
                    Recepción
                </a>
            @endif

            {{-- Limpieza --}}
            @if(Seguridad::puede('limpieza'))
                <a href="{{ url('/Limpieza') }}" class="{{ request()->is('Limpieza*') ? 'activo' : '' }}">
                    Limpieza
                </a>
            @endif

            {{-- Admin --}}
            @if(Seguridad::puede('admin'))
                <a href="{{ url('/Admin') }}" class="{{ request()->is('Admin*') ? 'activo' : '' }}">
                    Admin
                </a>
            @endif

            {{-- Sesion --}}
            @if (session()->has('usuario'))
                <a href="#" class="nav-link-logout"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    Cerrar sesión
                </a>

                <form id="logout-form" action="{{ url('/Logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            @else
                <a href="{{ url('/Login') }}" class="{{ request()->is('Login*') ? 'activo' : '' }}">
                    Login
                </a>
            @endif

            <button id="botonTema" class="boton-tema">🌙</button>
        </nav>

        <div class="hamburger" id="hamburger">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>
</header>