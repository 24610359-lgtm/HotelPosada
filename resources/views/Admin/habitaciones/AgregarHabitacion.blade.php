<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="base-url" content="{{ url('/') }}">


    <title>Admin-Habitaciones</title>

    <link rel="stylesheet" href="{{ asset('css/interfaz.css') }}">
    <script src="{{ asset('js/interfaz.js') }}" defer></script>
    <link rel="stylesheet" href="{{ asset('css/admin-panel.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-categorias.css') }}">
    <script src="{{ asset('js/admin-panel.js') }}" defer></script>
    <script src="{{ asset('js/admin-habitacion.js') }}" defer></script>

</head>

<body>
    @include('layout.Nav-bar')

    <main class="contenido-principal">
        <div class="admin-panel">
            @include('Admin.layout-admin.sub-nav-bar')
            <section class="admin-card">
                <div class="admin-card-header">
                    <h1>Habitaciones</h1>
                    <button class="btn-primario" onclick="abrirModal('modal-crear')">+ Nuevo</button>
                </div>

                @if(session('error'))
                    <p class="mensaje-error">{{session('error')}}</p>
                @endif

                <table class="tabla-admin">
                    <thead>
                        <tr>
                            <th>Número</th>
                            <th>Tipo</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($habitaciones as $h)
                            <tr>
                                <td>{{ $h->numero }}</td>
                                <td>{{ $h->tipo->nombre }}</td>
                                <td>{{ $h->estado }}</td>

                                <td class="acciones">
                                    <button class="btn-editar" data-id="{{$h->id_habitacion}}" data-numero="{{$h->numero}}"
                                        data-tipo="{{$h->id_tipo}}" data-estado="{{$h->estado}}">
                                        ✏️
                                    </button>

                                    <form method="POST" action="{{url('/Admin/Habitaciones/' . $h->id_habitacion)}}">
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

    </main>

    <div class="modal" id="modal-crear">
        <div class="modal-contenido">

            <h2>Nueva Habitación</h2>

            <form method="POST" action="{{url('/Admin/Habitaciones')}}" class="admin-form">
                @csrf

                <input name="numero" placeholder="Número habitación" required>

                <select name="id_tipo">
                    @foreach($tipos as $t)
                        <option value="{{$t->id_tipo}}">{{$t->nombre}}</option>
                    @endforeach
                </select>

                <select name="estado">
                    <option value="disponible">Disponible</option>
                    <option value="sucia">Sucia</option>
                    <option value="mantenimiento">Mantenimiento</option>
                </select>

                <div class="modal-acciones">
                    <button class="btn-primario">Guardar</button>
                    <button type="button" onclick="cerrarModal()" class="btn-cancelar">Cancelar</button>
                </div>

            </form>
        </div>
    </div>

    <div class="modal" id="modal-editar">
        <div class="modal-contenido">

            <h2>Editar Habitación</h2>

            <form method="POST" id="form-editar" class="admin-form">
                @csrf
                @method('PUT')

                <input id="edit-numero" name="numero" required>

                <select id="edit-tipo" name="id_tipo">
                    @foreach($tipos as $t)
                        <option value="{{$t->id_tipo}}">{{$t->nombre}}</option>
                    @endforeach
                </select>

                <select id="edit-estado" name="estado">
                    <option value="disponible">Disponible</option>
                    <option value="sucia">Sucia</option>
                    <option value="mantenimiento">Mantenimiento</option>
                </select>

                <div class="modal-acciones">
                    <button class="btn-primario">Actualizar</button>
                    <button type="button" onclick="cerrarModal()" class="btn-cancelar">Cancelar</button>
                </div>

            </form>
        </div>
    </div>

    <div class="modal" id="modal-eliminar">
        <div class="modal-contenido">

            <h2>Eliminar Habitación</h2>
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

    @include('layout.Footer')
</body>

</html>