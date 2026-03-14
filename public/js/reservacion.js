/**
 * JavaScript para la Interfaz de Reservación con Modales
 */

document.addEventListener('DOMContentLoaded', function() {
    
    // Elementos del DOM
    const modalReservacion = document.getElementById('modal-reservacion');
    const modalConfirmacion = document.getElementById('modal-confirmacion');
    const formReservacion = document.getElementById('form-reservacion');
    if (formReservacion) {
        // Desactiva la validación nativa (tooltips del navegador).
        formReservacion.setAttribute('novalidate', 'novalidate');
    }
    
    // Variables para almacenar datos
    let tipoHabitacionActual = null;
    let precioPorNoche = 0;
    let habitacionesDisponiblesData = [];
    
    // CSRF Token
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    
    // URLs del servidor
    const storeUrl = window.reservacionStoreUrl || '/reservaciones/store';
    const disponibilidadUrl = window.disponibilidadUrl || '/reservaciones/disponibilidad';
    
    // ============================================
    // FUNCIONES DE MODALES
    // ============================================
    
    /**
     * Abre un modal específico
     */
    window.abrirModalReservacion = function(id) {
        const modal = document.getElementById(id);
        if (modal) {
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden'; // Evitar scroll
        }
    };
    
    /**
     * Cierra todos los modales
     */
    window.cerrarModalReservacion = function() {
        const modales = document.querySelectorAll('.modal-reservacion');
        modales.forEach(modal => {
            modal.style.display = 'none';
        });
        document.body.style.overflow = 'auto'; // Restaurar scroll
        
        // Resetear formulario y mensajes
        if (formReservacion) {
            formReservacion.reset();
        }
        limpiarErrores();
        limpiarMensajes();
    };
    
    /**
     * Cierra el modal de confirmación
     */
    window.cerrarModalConfirmacion = function() {
        if (modalConfirmacion) {
            modalConfirmacion.style.display = 'none';
        }
        document.body.style.overflow = 'auto';
    };
    
    // Cerrar modal al hacer clic fuera
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('modal-reservacion')) {
            cerrarModalReservacion();
        }
    });
    
    // Cerrar con tecla Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            cerrarModalReservacion();
        }
    });
    
    // ============================================
    // BOTONES DE RESERVA EN CATEGORÍAS
    // ============================================
    
    // Variable global para verificar si el usuario está autenticado
    window.usuarioAutenticado = window.usuarioAutenticado || false;
    
    // Asignar eventos a los botones de reserva
    document.querySelectorAll('.btn-reservar').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation(); // Evitar bubbling
            
            // Verificar si el usuario está autenticado
            if (!window.usuarioAutenticado) {
                // Guardar una ruta relativa a la app (sin repetir base del proyecto/public).
                const appBasePath = window.appBaseUrl ? new URL(window.appBaseUrl, window.location.origin).pathname : '';
                let rutaActual = window.location.pathname;
                if (appBasePath && rutaActual.startsWith(appBasePath)) {
                    rutaActual = rutaActual.slice(appBasePath.length) || '/';
                }
                rutaActual += window.location.search;
                const loginPath = window.loginUrl || '/Login';
                window.location.href = loginPath + '?redirect=' + encodeURIComponent(rutaActual);
                return;
            }
            
            const idTipo = this.dataset.id;
            const nombreTipo = this.dataset.nombre;
            const habitacionesCount = parseInt(this.dataset.habitaciones) || 0;
            precioPorNoche = parseFloat(this.dataset.precio);
            
            // Guardar datos de habitaciones disponibles
            tipoHabitacionActual = {
                id: idTipo,
                nombre: nombreTipo,
                precio: precioPorNoche,
                totalHabitaciones: habitacionesCount
            };
            
            // Cargar habitaciones disponibles
            cargarHabitacionesDisponibles(idTipo);
            
            // Establecer fecha mínima (hoy)
            const hoy = new Date().toISOString().split('T')[0];
            const inputFechaEntrada = document.getElementById('fecha-entrada');
            const inputFechaSalida = document.getElementById('fecha-salida');
            
            if (inputFechaEntrada && inputFechaSalida) {
                inputFechaEntrada.value = '';
                inputFechaSalida.value = '';
                inputFechaEntrada.min = hoy;
                inputFechaSalida.min = hoy;
            }
            
            abrirModalReservacion('modal-reservacion');
            inicializarModal(idTipo, nombreTipo, precioPorNoche);
        });
    });
    
    // También permitir click en toda la tarjeta
    document.querySelectorAll('.categoria-card').forEach(card => {
        card.addEventListener('click', function(e) {
            // Solo si no se hizo clic en el botón
            if (!e.target.classList.contains('btn-reservar')) {
                const btn = this.querySelector('.btn-reservar');
                if (btn) {
                    btn.click();
                }
            }
        });
    });
    
    // ============================================
    // INICIALIZAR MODAL DE RESERVACIÓN
    // ============================================
    
    function inicializarModal(idTipo, nombreTipo, precio) {
        // Actualizar contenido del modal
        document.getElementById('modal-tipo-nombre').textContent = nombreTipo;
        document.getElementById('modal-tipo-precio').textContent = '$' + precio.toFixed(2);
        document.getElementById('input-id-tipo').value = idTipo;
        
        // Resetear resumen
        document.getElementById('resumen-noches').textContent = '0';
        document.getElementById('resumen-precio-noche').textContent = '$' + precio.toFixed(2);
        document.getElementById('resumen-total').textContent = '$0.00';
        
        // Resetear mensajes
        document.getElementById('mensaje-disponibilidad').innerHTML = '';
        
        // Limpiar errores previos
        limpiarErrores();
    }
    
    // ============================================
    // CARGAR HABITACIONES DISPONIBLES
    // ============================================
    
    // Variable para almacenar la primera habitación disponible
    let habitacionAutomaticaId = null;
    
    function cargarHabitacionesDisponibles(idTipo) {
        // Buscar las habitaciones disponibles en el DOM
        const habitacionesDivs = document.querySelectorAll('.habitacion-disponible[data-id="' + idTipo + '"]');
        
        // Crear input oculto para la habitación si no existe
        let inputHabitacionOculto = document.getElementById('input-id-habitacion-oculto');
        if (!inputHabitacionOculto) {
            inputHabitacionOculto = document.createElement('input');
            inputHabitacionOculto.type = 'hidden';
            inputHabitacionOculto.id = 'input-id-habitacion-oculto';
            inputHabitacionOculto.name = 'id_habitacion';
            document.getElementById('form-reservacion').appendChild(inputHabitacionOculto);
        }
        
        if (habitacionesDivs.length > 0) {
            // Guardar la primera habitación disponible
            habitacionAutomaticaId = habitacionesDivs[0].dataset.idHabitacion;
            inputHabitacionOculto.value = habitacionAutomaticaId;
            console.log('Habitación automática asignada:', habitacionAutomaticaId);
        } else {
            habitacionAutomaticaId = null;
            inputHabitacionOculto.value = '';
        }
    }
    
    // ============================================
    // VALIDACIÓN CON MENSAJES JS Y CSS
    // ============================================
    
    /**
     * Muestra un error en un campo específico
     */
    function mostrarError(campoId, mensaje) {
        const campo = document.getElementById(campoId);
        if (!campo) return;
        
        // Agregar clase de error
        campo.classList.add('input-error');
        
        // Crear o actualizar mensaje de error
        let errorDiv = campo.parentElement.querySelector('.error-mensaje');
        if (!errorDiv) {
            errorDiv = document.createElement('div');
            errorDiv.className = 'error-mensaje';
            campo.parentElement.appendChild(errorDiv);
        }
        errorDiv.innerHTML = '<i class="error-icon">⚠️</i> ' + mensaje;
        errorDiv.style.display = 'block';
    }
    
    /**
     * Limpia todos los errores
     */
    function limpiarErrores() {
        document.querySelectorAll('.input-error').forEach(campo => {
            campo.classList.remove('input-error');
        });
        document.querySelectorAll('.error-mensaje').forEach(msg => {
            msg.style.display = 'none';
            msg.innerHTML = '';
        });
    }
    
    /**
     * Valida el formulario antes de enviar
     */
    function validarFormulario() {
        let esValido = true;
        limpiarErrores();
        
        const fechaEntrada = document.getElementById('fecha-entrada');
        const fechaSalida = document.getElementById('fecha-salida');
        const metodoPago = document.getElementById('metodo-pago');
        
        // Validar fecha de entrada
        if (!fechaEntrada.value) {
            mostrarError('fecha-entrada', 'Selecciona la fecha de entrada');
            esValido = false;
        } else {
            const hoy = new Date();
            hoy.setHours(0, 0, 0, 0);
            const fechaSel = new Date(fechaEntrada.value);
            
            if (fechaSel < hoy) {
                mostrarError('fecha-entrada', 'La fecha debe ser hoy o posterior');
                esValido = false;
            }
        }
        
        // Validar fecha de salida
        if (!fechaSalida.value) {
            mostrarError('fecha-salida', 'Selecciona la fecha de salida');
            esValido = false;
        } else if (fechaEntrada.value && fechaSalida.value) {
            const entrada = new Date(fechaEntrada.value);
            const salida = new Date(fechaSalida.value);
            
            if (salida <= entrada) {
                mostrarError('fecha-salida', 'La salida debe ser posterior a la entrada');
                esValido = false;
            }
        }
        
        // Validar método de pago
        if (!metodoPago.value) {
            mostrarError('metodo-pago', 'Selecciona un método de pago');
            esValido = false;
        }

        if (!esValido) {
            mostrarMensaje('error', 'Revisa los campos marcados en rojo para continuar.');
        }
        
        return esValido;
    }
    
    function mostrarMensaje(tipo, texto) {
        const mensajeDiv = document.getElementById('mensaje-disponibilidad');
        let icono = '';
        switch(tipo) {
            case 'exito': icono = '✓'; break;
            case 'error': icono = '!'; break;
            case 'cargando': icono = '...'; break;
            default: icono = 'i';
        }
        mensajeDiv.innerHTML = `
            <span class="mensaje-reservacion mensaje-${tipo}">
                <span class="mensaje-icono">${icono}</span>
                <span>${texto}</span>
            </span>
        `;
    }
    
    /**
     * Limpia mensajes
     */
    function limpiarMensajes() {
        document.getElementById('mensaje-disponibilidad').innerHTML = '';
    }
    
    // ============================================
    // CALCULAR PRECIO Y RESUMEN
    // ============================================
    
    const inputFechaEntrada = document.getElementById('fecha-entrada');
    const inputFechaSalida = document.getElementById('fecha-salida');
    const selectHabitacion = document.getElementById('habitacion');
    
    if (inputFechaEntrada && inputFechaSalida) {
        
        // Event listeners para calcular precio
        inputFechaEntrada.addEventListener('change', function() {
            // La fecha de salida debe ser posterior a la entrada
            if (inputFechaSalida.value && inputFechaSalida.value <= this.value) {
                // Agregar un día a la fecha de entrada
                const siguienteDia = new Date(this.value);
                siguienteDia.setDate(siguienteDia.getDate() + 1);
                inputFechaSalida.value = siguienteDia.toISOString().split('T')[0];
            }
            inputFechaSalida.min = this.value;
            
            actualizarResumen();
            
            // Verificar disponibilidad si hay fechas válidas
            if (inputFechaEntrada.value && inputFechaSalida.value) {
                verificarDisponibilidad();
            } else {
                limpiarMensajes();
            }
        });
        
        inputFechaSalida.addEventListener('change', function() {
            actualizarResumen();
            
            // Verificar disponibilidad si hay fechas válidas
            if (inputFechaEntrada.value && inputFechaSalida.value) {
                verificarDisponibilidad();
            } else {
                limpiarMensajes();
            }
        });
        
        if (selectHabitacion) {
            selectHabitacion.addEventListener('change', function() {
                // Limpiar error si selecciona una habitación
                if (this.value) {
                    const errorDiv = this.parentElement.querySelector('.error-mensaje');
                    if (errorDiv) {
                        errorDiv.style.display = 'none';
                    }
                    this.classList.remove('input-error');
                }
            });
        }
    }
    
    function actualizarResumen() {
        if (!inputFechaEntrada || !inputFechaSalida) return;
        
        const fechaEntrada = new Date(inputFechaEntrada.value);
        const fechaSalida = new Date(inputFechaSalida.value);
        
        if (inputFechaEntrada.value && inputFechaSalida.value && fechaSalida > fechaEntrada) {
            // Calcular número de noches
            const diffTime = Math.abs(fechaSalida - fechaEntrada);
            const noches = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            
            // Calcular precio total
            const precioTotal = noches * precioPorNoche;
            
            // Actualizar resumen
            document.getElementById('resumen-noches').textContent = noches;
            document.getElementById('resumen-precio-noche').textContent = '$' + precioPorNoche.toFixed(2);
            document.getElementById('resumen-total').textContent = '$' + precioTotal.toFixed(2);
            
            // Guardar valores para el submit
            document.getElementById('input-noches').value = noches;
            document.getElementById('input-precio-total').value = precioTotal;
        }
    }
    
    // ============================================
    // VERIFICAR DISPONIBILIDAD
    // ============================================
    
    function verificarDisponibilidad() {
        if (!inputFechaEntrada.value || !inputFechaSalida.value) return;
        
        const idTipo = document.getElementById('input-id-tipo').value;
        
        mostrarMensaje('cargando', '⏳ Verificando disponibilidad...');
        
        fetch(disponibilidadUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                id_tipo: idTipo,
                fecha_entrada: inputFechaEntrada.value,
                fecha_salida: inputFechaSalida.value
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.disponibles > 0) {
                mostrarMensaje('exito', `✅ ${data.disponibles} habitación(es) disponible(s) para estas fechas`);
            } else {
                mostrarMensaje('error', '❌ No hay habitaciones disponibles para estas fechas');
            }
        })
        .catch(error => {
            console.error('Error al verificar disponibilidad:', error);
            mostrarMensaje('error', '❌ Error al verificar disponibilidad. Intenta de nuevo.');
        });
    }
    
    // ============================================
    // ENVIAR FORMULARIO DE RESERVACIÓN
    // ============================================
    
    if (formReservacion) {
        formReservacion.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            // Validar con JS antes de enviar
            if (!validarFormulario()) {
                return; // No enviar si hay errores
            }
            
            const btnSubmit = formReservacion.querySelector('.btn-reservar-modal');
            const textoOriginal = btnSubmit.textContent;
            btnSubmit.textContent = '⏳ Procesando...';
            btnSubmit.disabled = true;
            
            try {
                const formData = new FormData(formReservacion);
                
                const response = await fetch(storeUrl, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: formData
                });
                
                const data = await response.json();
                
                if (response.ok) {
                    // Mostrar modal de confirmación
                    document.getElementById('confirmacion-mensaje').textContent = data.mensaje || '¡Reservación creada exitosamente!';
                    document.getElementById('confirmacion-detalles').innerHTML = `
                        <p><strong>Noches:</strong> ${data.noches}</p>
                        <p><strong>Precio por noche:</strong> $${data.precio_por_noche}</p>
                        <p><strong>Total:</strong> $${data.precio_total}</p>
                    `;
                    
                    abrirModalReservacion('modal-confirmacion');
                    cerrarModalReservacion();
                } else {
                    // Mostrar error del servidor
                    mostrarMensaje('error', data.mensaje || 'Error al crear la reservación');
                }
            } catch (error) {
                console.error('Error:', error);
                mostrarMensaje('error', '❌ Error de conexión. Intenta de nuevo.');
            } finally {
                btnSubmit.textContent = textoOriginal;
                btnSubmit.disabled = false;
            }
        });
    }
    
    // ============================================
    // BOTONES DE CERRAR MODAL
    // ============================================
    
    // Botones de cancelar
    document.querySelectorAll('.btn-cancelar-reservacion').forEach(btn => {
        btn.addEventListener('click', function() {
            cerrarModalReservacion();
        });
    });
    
    // Botones de cerrar (X)
    document.querySelectorAll('.modal-cerrar').forEach(btn => {
        btn.addEventListener('click', function() {
            cerrarModalReservacion();
        });
    });
    
    // Botón OK en modal de confirmación
    const btnConfirmacionOk = document.getElementById('btn-confirmacion-ok');
    if (btnConfirmacionOk) {
        btnConfirmacionOk.addEventListener('click', function() {
            cerrarModalConfirmacion();
            location.reload();
        });
    }
});
