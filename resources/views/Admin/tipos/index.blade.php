<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="base-url" content="{{ url('/') }}">


    <title>Document</title>

    <link rel="stylesheet" href="{{ asset('css/interfaz.css') }}">
    <script src="{{ asset('js/interfaz.js') }}" defer></script>
    <link rel="stylesheet" href="{{ asset('css/admin-panel.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-categorias.css') }}">
    <script src="{{ asset('js/admin-panel.js') }}" defer></script>
    <script src="{{ asset('js/admin-categorias.js') }}" defer></script>

</head>

<body>
    @include('layout.Nav-bar')

    <main class="contenido-principal">
        <div class="admin-panel">
            @include('Admin.layout-admin.sub-nav-bar')
            <section class="admin-card">
                <div class="admin-card-header">
                    <h1>Tipos de Habitación</h1>
                    <button class="btn-primario" onclick="abrirModal('modal-crear')">+ Nuevo</button>
                </div>

                @if(session('ok'))
                    <p class="mensaje-ok">{{ session('ok') }}</p>
                @endif

                <table class="tabla-admin">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Precio / noche</th>
                            <th>Capacidad</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tipos as $tipo)
                            <tr>
                                <td>{{ $tipo->nombre }}</td>
                                <td>{{ $tipo->descripcion }}</td>
                                <td>${{ number_format($tipo->precio_noche, 2) }}</td>
                                <td>{{ number_format($tipo->capacidad) }}</td>
                                <td class="acciones">
                                    <button class="btn-editar" data-id="{{ $tipo->id_tipo }}"
                                        data-nombre="{{ $tipo->nombre }}" data-descripcion="{{ $tipo->descripcion }}"
                                        data-precio="{{ $tipo->precio_noche }}" data-capacidad="{{ $tipo->capacidad }}">
                                        ✏️
                                    </button>

                                    <button class="btn-servicios" data-id="{{ $tipo->id_tipo }}"
                                        data-nombre="{{ $tipo->nombre }}">
                                        🧩
                                    </button>

                                    <form method="POST" action="{{ url('/Admin/Tipos-Habitacion/' . $tipo->id_tipo) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn-eliminar">🗑️</button>
                                    </form>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </section>

        </div>

        <div class="modal" id="modal-crear">
            <div class="modal-contenido">
                <h2>Nuevo Tipo de Habitación</h2>

                <form method="POST" action="{{ url('/Admin/Tipos-Habitacion') }}" class="admin-form">
                    @csrf
                    <input name="nombre" placeholder="Nombre" required>
                    <textarea name="descripcion" placeholder="Descripción"></textarea>
                    <input name="precio_noche" type="number" step="0.01" placeholder="Precio" required>
                    <input name="capacidad" type="number" step="1" placeholder="capacidad" required>

                    <div class="modal-acciones">
                        <button type="submit" class="btn-primario">Guardar</button>
                        <button type="button" onclick="cerrarModal()" class="btn-cancelar">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="modal" id="modal-editar">
            <div class="modal-contenido">
                <h2>Editar Tipo</h2>

                <form method="POST" id="form-editar" class="admin-form"
                    data-action="{{ url('/Admin/Tipos-Habitacion') }}">
                    @csrf
                    @method('PUT')

                    <input name="nombre" id="edit-nombre" required>
                    <textarea name="descripcion" id="edit-descripcion"></textarea>
                    <input name="precio_noche" id="edit-precio" type="number" step="0.01" required>
                    <input name="capacidad" id="edit-capacidad" type="number" step="1" required>

                    <div class="modal-acciones">
                        <button type="submit" class="btn-primario">Actualizar</button>
                        <button type="button" onclick="cerrarModal()" class="btn-cancelar">Cancelar</button>
                    </div>
                </form>

            </div>
        </div>

        <div class="modal" id="modal-servicios">
            <div class="modal-contenido">
                <h2 id="titulo-servicios"></h2>

                <div class="modal-servicios-container">
                    <div class="columna">
                        <h3>Asignados</h3>
                        <ul id="lista-asignados"></ul>
                    </div>

                    <div class="columna">
                        <h3>Disponibles</h3>
                        <ul id="lista-disponibles"></ul>
                    </div>
                </div>

                <div class="modal-acciones">
                    <button type="button" onclick="cerrarModal()" class="btn-cancelar">Cerrar</button>
                </div>
            </div>
        </div>

        <div class="modal" id="modal-eliminar">
            <div class="modal-contenido">

                <h2>Eliminar Tipo De Habitación</h2>
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