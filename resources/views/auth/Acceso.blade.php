@php
    $formActivo = session('form', 'login');
@endphp

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Acceso</title>
    <link rel="stylesheet" href="{{ asset('css/interfaz.css') }}">
    <script src="{{ asset('js/interfaz.js') }}" defer></script>

    <style>
        .contenedor {
            width: min(92vw, 420px);
            margin: 70px auto;
            background: #1e293b;
            padding: 24px;
            border-radius: 14px;
            color: white;
            box-shadow: 0 16px 40px rgba(2, 6, 23, 0.35);
        }

        .contenedor form {
            margin-top: 10px;
        }

        .contenedor input {
            width: 100%;
            margin: 8px 0 0;
            padding: 11px 12px;
            border-radius: 8px;
            border: 2px solid #cbd5e1;
            background: #ffffff;
            color: #0f172a;
            opacity: 1;
            outline: none;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
            -webkit-text-fill-color: #0f172a;
        }

        .contenedor input::placeholder {
            color: #64748b;
        }

        .contenedor input:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.18);
        }

        .btn {
            width: 100%;
            margin: 8px 0;
            padding: 10px;
            border-radius: 6px;
            border: none;
            background: #2563eb;
            color: white;
            cursor: pointer;
            font-weight: 600;
        }

        .btn:hover {
            background: #1d4ed8;
        }

        .form-alert {
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 0 0 10px;
            padding: 9px 11px;
            border-radius: 8px;
            background: #fef2f2;
            color: #991b1b;
            border: 1px solid #fecaca;
            border-left: 4px solid #ef4444;
            font-size: 0.9rem;
        }

        .form-alert[hidden] {
            display: none;
        }

        .form-alert-icon {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: #ef4444;
            color: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: 700;
        }

        .error-texto {
            display: flex;
            align-items: center;
            gap: 7px;
            color: #fecaca;
            background: #3f1d2a;
            border: 1px solid #7f1d1d;
            border-left: 3px solid #ef4444;
            border-radius: 8px;
            font-size: 0.82rem;
            margin: 6px 0 2px;
            padding: 7px 10px;
        }

        .input-error {
            border: 2px solid #ef4444;
            background: #fff5f5 !important;
            color: #111827 !important;
            opacity: 1 !important;
            -webkit-text-fill-color: #111827 !important;
            box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.16);
        }

        .error-texto::before {
            content: "!";
            width: 18px;
            height: 18px;
            border-radius: 50%;
            background: #ef4444;
            color: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.72rem;
            font-weight: 700;
            flex: 0 0 auto;
        }
    </style>
</head>

<body>

    @include('layout.Nav-bar')

    <main class="contenido-principal">
        <div class="contenedor">
            <h2 id="titulo">
                {{ $formActivo === 'login' ? 'Iniciar sesión' : 'Registro' }}
            </h2>

            {{-- LOGIN --}}
            <form id="login" method="POST" action="{{ url('/Login') }}" novalidate {{ $formActivo === 'login' ? '' : 'hidden' }}>
                @csrf
                <div class="form-alert" id="login-alert" hidden>
                    <span class="form-alert-icon">!</span>
                    <span>Revisa los campos marcados en rojo.</span>
                </div>

                <input type="email" name="email" placeholder="Correo" value="{{ old('email') }}"
                    class="{{ $errors->has('email') && $formActivo === 'login' ? 'input-error' : '' }}">

                @if ($formActivo === 'login')
                    @error('email')
                        <div class="error-texto">{{ $message }}</div>
                    @enderror
                @endif

                <input type="password" name="password" placeholder="Contraseña"
                    class="{{ $errors->has('password') && $formActivo === 'login' ? 'input-error' : '' }}">

                @if ($formActivo === 'login')
                    @error('password')
                        <div class="error-texto">{{ $message }}</div>
                    @enderror
                @endif

                <button class="btn">Entrar</button>
            </form>

            {{-- REGISTRO --}}
            <form id="registro" method="POST" action="{{ url('/Registro') }}" novalidate
                {{ $formActivo === 'registro' ? '' : 'hidden' }}>
                @csrf
                <div class="form-alert" id="registro-alert" hidden>
                    <span class="form-alert-icon">!</span>
                    <span>Revisa los campos marcados en rojo.</span>
                </div>

                <input name="nombre" placeholder="Nombre" value="{{ old('nombre') }}"
                    class="{{ $errors->has('nombre') ? 'input-error' : '' }}">
                @error('nombre')
                    <div class="error-texto">{{ $message }}</div>
                @enderror

                <input name="apellidos" placeholder="Apellidos" value="{{ old('apellidos') }}"
                    class="{{ $errors->has('apellidos') ? 'input-error' : '' }}">
                @error('apellidos')
                    <div class="error-texto">{{ $message }}</div>
                @enderror

                <input name="telefono" placeholder="Teléfono" value="{{ old('telefono') }}">

                <input type="email" name="email" placeholder="Correo" value="{{ old('email') }}"
                    class="{{ $errors->has('email') ? 'input-error' : '' }}">
                @error('email')
                    <div class="error-texto">{{ $message }}</div>
                @enderror

                <input type="password" name="password" placeholder="Contraseña"
                    class="{{ $errors->has('password') ? 'input-error' : '' }}">
                @error('password')
                    <div class="error-texto">{{ $message }}</div>
                @enderror

                <button class="btn">Registrarse</button>
            </form>

            <button class="btn" id="cambiar">
                {{ $formActivo === 'login' ? 'Crear cuenta' : 'Ya tengo cuenta' }}
            </button>
        </div>
    </main>

    @include('layout.footer')

    <script>
        const btn = document.getElementById('cambiar')
        const login = document.getElementById('login')
        const registro = document.getElementById('registro')
        const titulo = document.getElementById('titulo')
        const loginAlert = document.getElementById('login-alert')
        const registroAlert = document.getElementById('registro-alert')

        function limpiarErrores(formulario, alertBox) {
            formulario.querySelectorAll('.input-error').forEach(i => i.classList.remove('input-error'))
            formulario.querySelectorAll('.error-texto.js-error').forEach(e => e.remove())
            if (alertBox) alertBox.hidden = true
        }

        function mostrarError(input, mensaje) {
            if (!input) return
            input.classList.add('input-error')
            let error = input.nextElementSibling
            if (!error || !error.classList.contains('error-texto') || !error.classList.contains('js-error')) {
                error = document.createElement('div')
                error.className = 'error-texto js-error'
                input.insertAdjacentElement('afterend', error)
            }
            error.textContent = mensaje
        }

        function esCorreoValido(valor) {
            return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(valor)
        }

        function soloDigitos(valor) {
            return (valor || '').replace(/\D/g, '')
        }

        function validarLogin() {
            limpiarErrores(login, loginAlert)
            let valido = true

            const email = login.querySelector('input[name="email"]')
            const password = login.querySelector('input[name="password"]')

            if (!email.value.trim()) {
                mostrarError(email, 'El correo es obligatorio')
                valido = false
            } else if (!esCorreoValido(email.value.trim())) {
                mostrarError(email, 'Ingresa un correo válido')
                valido = false
            }

            if (!password.value) {
                mostrarError(password, 'La contraseña es obligatoria')
                valido = false
            } else if (password.value.length < 6) {
                mostrarError(password, 'Mínimo 6 caracteres')
                valido = false
            }

            if (!valido) loginAlert.hidden = false
            return valido
        }

        function validarRegistro() {
            limpiarErrores(registro, registroAlert)
            let valido = true

            const nombre = registro.querySelector('input[name="nombre"]')
            const apellidos = registro.querySelector('input[name="apellidos"]')
            const telefono = registro.querySelector('input[name="telefono"]')
            const email = registro.querySelector('input[name="email"]')
            const password = registro.querySelector('input[name="password"]')

            if (!nombre.value.trim() || nombre.value.trim().length < 2) {
                mostrarError(nombre, 'Nombre mínimo de 2 caracteres')
                valido = false
            }

            if (!apellidos.value.trim() || apellidos.value.trim().length < 2) {
                mostrarError(apellidos, 'Apellidos mínimo de 2 caracteres')
                valido = false
            }

            const telLimpio = soloDigitos(telefono.value)
            if (telefono.value.trim() && telLimpio.length < 8) {
                mostrarError(telefono, 'Teléfono con al menos 8 dígitos')
                valido = false
            }

            if (!email.value.trim()) {
                mostrarError(email, 'El correo es obligatorio')
                valido = false
            } else if (!esCorreoValido(email.value.trim())) {
                mostrarError(email, 'Ingresa un correo válido')
                valido = false
            }

            if (!password.value) {
                mostrarError(password, 'La contraseña es obligatoria')
                valido = false
            } else if (password.value.length < 6) {
                mostrarError(password, 'Mínimo 6 caracteres')
                valido = false
            }

            if (!valido) registroAlert.hidden = false
            return valido
        }

        btn.onclick = () => {
            const mostrarLogin = login.hidden

            limpiarErrores(login, loginAlert)
            limpiarErrores(registro, registroAlert)

            login.hidden = !mostrarLogin
            registro.hidden = mostrarLogin

            titulo.textContent = mostrarLogin ? 'Iniciar sesión' : 'Registro'
            btn.textContent = mostrarLogin ? 'Crear cuenta' : 'Ya tengo cuenta'
        }

        login.addEventListener('submit', (e) => {
            if (!validarLogin()) e.preventDefault()
        })

        registro.addEventListener('submit', (e) => {
            if (!validarRegistro()) e.preventDefault()
        })

        login.querySelectorAll('input').forEach((input) => {
            input.addEventListener('input', () => {
                if (input.classList.contains('input-error')) {
                    limpiarErrores(login, loginAlert)
                }
            })
        })

        registro.querySelectorAll('input').forEach((input) => {
            input.addEventListener('input', () => {
                if (input.classList.contains('input-error')) {
                    limpiarErrores(registro, registroAlert)
                }
            })
        })
    </script>

</body>

</html>
