<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Hotel Paraíso</title>

    <link rel="stylesheet" href="{{ asset('css/interfaz.css') }}">
    <link rel="stylesheet" href="{{ asset('css/youtube.css') }}">
</head>

<body>

    @include('layout.Nav-bar')

    <main class="contenido-principal">

        <h1 class="yt-title">Buscador YouTube</h1>

        <div class="search-box">
            <input id="searchInput" placeholder="Buscar videos...">
            <button id="btnBuscar">Buscar</button>
        </div>

        <div id="loader" class="loader hidden">Cargando...</div>

        <div id="videos" class="grid"></div>

        <button id="btnMore" class=" more hidden">Cargar más</button>

        <!-- MODAL -->
        <div id="modal" class="modal hidden">
            <div class="modal-content">
                <button id="close" class="modal-close">&times;</button>
                <a id="openYT" target="_blank" class="openYT">Ver en YouTube</a>
                <div id="player"></div>
            </div>
        </div>

    </main>

    @include('layout.Footer')

    <script src="{{ asset('js/interfaz.js') }}"></script>
    <script>
        window.youtubeSearchUrl = @json(url('/youtube-search'));
    </script>
    <script src="{{ asset('js/youtube.js') }}"></script>

</body>

</html>
