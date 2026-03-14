<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <link rel="stylesheet" href="{{ asset('css/interfaz.css') }}">
    <script src="{{ asset('js/interfaz.js') }}" defer></script>
    <link rel="stylesheet" href="{{ asset('css/admin-panel.css') }}">
    <script src="{{ asset('js/admin-panel.js') }}" defer></script>

</head>

<body>
    @include('layout.Nav-bar')

    <main class="contenido-principal">
        <div class="admin-panel">
            @include('Admin.layout-admin.sub-nav-bar')
            <section class="admin-card">
                <div class="admin-card-header">
                    <h1>Tipos de Habitación</h1>
                     <button class="btn-primario" onclick="">cambiar</button> 
                </div>

                @if(session('ok'))
                    <p class="mensaje-ok">{{ session('ok') }}</p>
                @endif

                
            </section>
        </div>

    </main>

    @include('layout.Footer')
</body>

</html>