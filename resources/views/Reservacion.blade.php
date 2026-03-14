<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Reservaciones - Posada De La Luz</title>

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('css/interfaz.css') }}">
    <link rel="stylesheet" href="{{ asset('css/reservacion.css') }}">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">

    <!-- JavaScript -->
    <script src="{{ asset('js/interfaz.js') }}" defer></script>
    <script src="{{ asset('js/reservacion.js') }}" defer></script>
    
    <!-- Variables globales para JS -->
    <script>
        window.reservacionStoreUrl = "{{ route('reservaciones.store') }}";
        window.disponibilidadUrl = "{{ route('reservaciones.disponibilidad') }}";
        window.loginUrl = "{{ url('/Login') }}";
        window.appBaseUrl = "{{ url('/') }}";
        window.usuarioAutenticado = {{ session()->has('usuario') ? 'true' : 'false' }};
    </script>
</head>

<body>

    @include('layout.Nav-bar')

    <main class="contenido-principal">
        
        <div class="contenedor-reservacion">
            
            <!-- Título principal -->
            <h1 class="titulo-reservacion">🏨 Reserva tu Habitación</h1>
            
            <p style="text-align: center; margin-bottom: 40px; font-size: 1.1rem; opacity: 0.8;">
                Elige la categoría de habitación que más se adapte a tus necesidades
            </p>

            <!-- Grid de categorías de habitaciones -->
            <div class="categorias-habitaciones">
                
                @forelse($tiposHabitacion as $tipo)
                    @php
                        // Obtener habitaciones disponibles de este tipo
                        $habitacionesDelTipo = $habitacionesDisponibles->get($tipo->id_tipo, collect());
                        $totalHabitaciones = $habitacionesDelTipo->count();
                    @endphp

                    {{-- Solo mostrar categorías que tienen habitaciones asignadas --}}
                    @if($totalHabitaciones > 0)
                        <!-- Tarjeta de categoría -->
                        <div class="categoria-card" data-id="{{ $tipo->id_tipo }}">
                            
                            <!-- Imagen representativa (podría ser dinámica) -->
                            <div class="categoria-imagen">
                                @switch($tipo->nombre)
                                    @case('Suite')
                                        🏰
                                        @break
                                    @case('Habitación Doble')
                                        🛏️
                                        @break
                                    @case('Habitación Familiar')
                                        👨‍👩‍👧‍👦
                                        @break
                                    @case('Habitación Deluxe')
                                        💎
                                        @break
                                    @default
                                        🏠
                                @endswitch
                            </div>
                            
                            <!-- Contenido de la tarjeta -->
                            <div class="categoria-contenido">
                                <h3 class="categoria-nombre">{{ $tipo->nombre }}</h3>
                                
                                @if($tipo->descripcion)
                                    <p class="categoria-descripcion">{{ $tipo->descripcion }}</p>
                                @endif
                                
                                <!-- Información de precio y capacidad -->
                                <div class="categoria-info">
                                    <span class="categoria-precio">${{ number_format($tipo->precio_noche, 2) }} <small>/noche</small></span>
                                    <span class="categoria-capacidad">
                                        👥 {{ $tipo->capacidad }} {{ $tipo->capacidad == 1 ? 'huésped' : 'huéspedes' }}
                                    </span>
                                </div>
                                
                                <!-- Servicios incluidos -->
                                @if($tipo->servicios && $tipo->servicios->count() > 0)
                                    <div class="categoria-servicios">
                                        @foreach($tipo->servicios as $servicio)
                                            <span class="servicio-tag">
                                                {!! $servicio->icono ?? '✓' !!} {{ $servicio->nombre }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif
                                
                                <!-- Botón de reserva -->
                                <button class="btn-reservar" 
                                        data-id="{{ $tipo->id_tipo }}"
                                        data-nombre="{{ $tipo->nombre }}"
                                        data-precio="{{ $tipo->precio_noche }}"
                                        data-habitaciones="{{ $totalHabitaciones }}">
                                    📅 Reservar Ahora
                                </button>
                            </div>
                        </div>

                        <!-- Datos de habitaciones disponibles (para JS) -->
                        @foreach($habitacionesDelTipo as $habitacion)
                            <div class="habitacion-disponible" 
                                 data-numero="{{ $habitacion->numero }}" 
                                 data-id-habitacion="{{ $habitacion->id_habitacion }}"
                                 data-id="{{ $tipo->id_tipo }}">
                            </div>
                        @endforeach

                    @endif
                    
                @empty
                    <div style="grid-column: 1 / -1; text-align: center; padding: 60px 20px;">
                        <p style="font-size: 1.2rem; opacity: 0.6;">
                            😔 No hay categorías de habitaciones disponibles en este momento.
                        </p>
                        <p style="margin-top: 10px; opacity: 0.5;">
                            Por favor, contacta a recepción para más información.
                        </p>
                    </div>
                @endforelse
                
            </div>

        </div>

    </main>

    @include('layout.Footer')

    <!-- ============================================ -->
    <!-- MODAL DE RESERVACIÓN -->
    <!-- ============================================ -->
    <div class="modal-reservacion" id="modal-reservacion">
        <div class="modal-reservacion-contenido">
            <span class="modal-cerrar" onclick="cerrarModalReservacion()">&times;</span>
            
            <!-- Detalles del tipo de habitación -->
            <div class="detalles-tipo">
                <h4 id="modal-tipo-nombre">Habitación</h4>
                <p><strong>Precio por noche:</strong> <span id="modal-tipo-precio">$0.00</span></p>
            </div>
            
            <!-- Mensaje de disponibilidad -->
            <div id="mensaje-disponibilidad"></div>
            
            <!-- Formulario de reservación -->
            <form id="form-reservacion" class="form-reservacion" novalidate>
                @csrf
                
                <input type="hidden" id="input-id-tipo" name="id_tipo" value="">
                <input type="hidden" id="input-noches" name="noches" value="">
                <input type="hidden" id="input-precio-total" name="precio_total" value="">
                
                <div class="form-grupo">
                    <label for="fecha-entrada">📅 Fecha de Entrada</label>
                    <input type="date" id="fecha-entrada" name="fecha_entrada" required>
                </div>
                
                <div class="form-grupo">
                    <label for="fecha-salida">📅 Fecha de Salida</label>
                    <input type="date" id="fecha-salida" name="fecha_salida" required>
                </div>
                
                <div class="form-grupo">
                    <label for="metodo-pago">💳 Método de Pago</label>
                    <select id="metodo-pago" name="metodo_pago" required>
                        <option value="">Selecciona un método de pago</option>
                        <option value="efectivo">💵 Efectivo (Pagar en recepción)</option>
                        <option value="tarjeta">💳 Pago con Tarjeta (Stripe)</option>
                    </select>
                </div>
                
                <!-- Resumen de precio -->
                <div class="resumen-reservacion">
                    <div class="resumen-fila">
                        <span>Precio por noche:</span>
                        <span id="resumen-precio-noche">$0.00</span>
                    </div>
                    <div class="resumen-fila">
                        <span>Número de noches:</span>
                        <span id="resumen-noches">0</span>
                    </div>
                    <div class="resumen-fila resumen-total">
                        <span>Total a pagar:</span>
                        <span id="resumen-total">$0.00</span>
                    </div>
                </div>
                
                <div class="modal-acciones-reservacion">
                    <button type="submit" class="btn-reservar-modal">
                        ✅ Confirmar Reservación
                    </button>
                    <button type="button" class="btn-cancelar-reservacion" onclick="cerrarModalReservacion()">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ============================================ -->
    <!-- MODAL DE CONFIRMACIÓN -->
    <!-- ============================================ -->
    <div class="modal-reservacion" id="modal-confirmacion">
        <div class="modal-reservacion-contenido" style="text-align: center;">
            <span class="modal-cerrar" onclick="cerrarModalConfirmacion()">&times;</span>
            
            <div style="font-size: 4rem; margin-bottom: 20px;">🎉</div>
            
            <h2 style="margin-bottom: 20px;">¡Reservación Exitosa!</h2>
            
            <div id="confirmacion-mensaje" class="mensaje-reservacion mensaje-exito">
                Tu reservación ha sido creada correctamente.
            </div>
            
            <div id="confirmacion-detalles" style="text-align: left; margin: 20px 0;">
                <!-- Detalles se insertan dinámicamente -->
            </div>
            
            <p style="opacity: 0.7; margin-top: 20px;">
                Te hemos enviado un correo de confirmación.<br>
                Gracias por elegir Posada De La Luz.
            </p>
            
            <button id="btn-confirmacion-ok" class="btn-reservar-modal" style="margin-top: 20px;">
                Aceptar
            </button>
        </div>
    </div>

</body>

</html>
