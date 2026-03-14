<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrar Servicios</title>

    <link rel="stylesheet" href="{{ asset('css/interfaz.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-panel.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-servicios.css') }}">

    <script src="{{ asset('js/interfaz.js') }}" defer></script>
    <script src="{{ asset('js/admin-panel.js') }}" defer></script>
    <script src="{{ asset('js/admin-servicios.js') }}" defer></script>
</head>

<body>

    @include('layout.Nav-bar')

    <main class="contenido-principal">
        <div class="admin-panel">

            @include('Admin.layout-admin.sub-nav-bar')

            <section class="admin-card">

                <div class="admin-card-header">
                    <h1>Servicios</h1>
                    <button class="btn-primario" onclick="abrirModal('modal-crear')">
                        + Nuevo Servicio
                    </button>
                </div>

                @if(session('ok'))
                    <p class="mensaje-ok">{{ session('ok') }}</p>
                @endif

                <table class="tabla-admin">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Icono</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($servicios as $servicio)
                            <tr>
                                <td>{{ $servicio->nombre }}</td>

                                <td>
                                    {{ $servicio->icono ?? '—' }}
                                </td>

                                <td class="acciones">

                                    <button class="btn-editar" data-id="{{ $servicio->id_servicio }}"
                                        data-nombre="{{ $servicio->nombre }}" data-icono="{{ $servicio->icono }}">
                                        ✏️
                                    </button>

                                    <form method="POST" action="{{ url('/Admin/Servicios/' . $servicio->id_servicio) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-eliminar">
                                            🗑️
                                        </button>
                                    </form>

                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" style="text-align:center; opacity:.6;">
                                    No hay servicios registrados.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

            </section>
        </div>


        <div class="modal" id="modal-crear">
            <div class="modal-contenido">

                <h2>Nuevo Servicio</h2>

                <form method="POST" action="{{ url('/Admin/Servicios') }}" class="admin-form">

                    @csrf

                    <input type="text" name="nombre" placeholder="Nombre del servicio" required>

                    <input type="text" name="icono" placeholder="icono (opcional)">

                    <div class="modal-acciones">
                        <button type="submit" class="btn-primario">
                            Guardar
                        </button>
                        <button type="button" onclick="cerrarModal()" class="btn-cancelar">
                            Cancelar
                        </button>
                    </div>

                </form>
            </div>
        </div>


        <div class="modal" id="modal-editar">
            <div class="modal-contenido">

                <h2>Editar Servicio</h2>

                <form method="POST" id="form-editar" class="admin-form" data-action="{{ url('/Admin/Servicios') }}">

                    @csrf
                    @method('PUT')

                    <input type="text" name="nombre" id="edit-nombre" required>

                    <input type="text" name="icono" id="edit-icono">

                    <div class="modal-acciones">
                        <button type="submit" class="btn-primario">
                            Actualizar
                        </button>
                        <button type="button" onclick="cerrarModal()" class="btn-cancelar">
                            Cancelar
                        </button>
                    </div>

                </form>

            </div>
        </div>

        <div class="modal" id="modal-eliminar">
            <div class="modal-contenido">

                <h2>Eliminar Servicio</h2>
                <p>Esta acción no se puede deshacer</p>

                <form method="POST" id="form-eliminar">
                    @csrf
                    @method('DELETE')

                    <div class="modal-acciones">
                        <button class="btn-cancelar">Eliminar</button>
                        <button type="button" onclick="cerrarModal()" class="btn-primario">Cancelar</button>
                    </div>

                </form>

            </div>
        </div>

    </main>

    @include('layout.Footer')

</body>

</html>