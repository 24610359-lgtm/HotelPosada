<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Hotel Paraíso</title>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <link rel="stylesheet" href="{{ asset('css/interfaz.css') }}">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">

    <script src="{{ asset('js/interfaz.js') }}" defer></script>
    <script src="{{ asset('js/mapa.js') }}" defer></script>
</head>


<body class="bg-slate-100 dark:bg-slate-900 text-slate-900 dark:text-slate-100">

    @include('layout.Nav-bar')

    <main class="contenido-principal">
        <section class="hero">
            <div class="hero-overlay">
                <div class="hero-contenido">
                    <h1>Bienvenido a Hotel Posada De La Luz</h1>
                    <p>
                        Donde el lujo se encuentra con la comodidad.
                        Disfruta de una experiencia única en el corazón de la ciudad.
                    </p>

                    <a href="../public/Habitaciones" class="btn-hero">
                        Reservar ahora
                    </a>
                </div>
            </div>
        </section>

        <section class="sobre-hotel">
            <div class="sobre-contenedor">

                <div class="sobre-texto">
                    <h2>Sobre Hotel Posada De La Luz</h2>

                    <p>
                        Desde 2014, Hotel Posada De La Luz ha sido sinónimo de excelencia en hospitalidad.
                        Ubicado estratégicamente en el corazón de la ciudad, ofrecemos una
                        experiencia única que combina confort, elegancia y un servicio personalizado.
                    </p>

                    <p>
                        Nuestro compromiso es hacer de cada estancia un momento inolvidable,
                        brindando atención excepcional a cada uno de nuestros huéspedes.
                    </p>

                    <div class="horarios">
                        <span>🕒 Check-in: 3:00 PM</span>
                        <span>🕛 Check-out: 12:00 PM</span>
                    </div>
                </div>

                <div class="sobre-imagenes">
                    <img src="{{ asset('img/home/HomeEdificio.png') }}" alt="Vista aérea del hotel">
                    <img src="{{ asset('../public/img/home/HomePiscina.png') }}" alt="Vista de la pisina del hotel">
                    <img src="{{ asset('../public/img/home/HomeHabitacion.png') }}"
                        alt="Vista de una habitación del hotel">
                </div>

            </div>
        </section>

        <section class="ubicacion">
            <h2>Nuestra Ubicación</h2>
            <p class="subtitulo">
                En el corazón de la ciudad, cerca de todo lo que necesitas
            </p>

            <div class="ubicacion-contenido">
                <div class="ubicacion-info">
                    <div class="dato">
                        <span class="icono">📍</span>
                        <div>
                            <strong>Dirección</strong>
                            <p>gnacio Aldama s/n, Col. Centro.</p>
                        </div>
                    </div>

                    <div class="dato">
                        <span class="icono">📞</span>
                        <div>
                            <strong>Teléfono</strong>
                            <p>+1 234 567 8900<br>+1 234 567 8901</p>
                        </div>
                    </div>

                    <div class="dato">
                        <span class="icono">✉️</span>
                        <div>
                            <strong>Email</strong>
                            <p>info@posadadelaluz.com<br>reservas@posadadelaluz.com</p>
                        </div>
                    </div>
                </div>

                <div class="ubicacion-mapa">
                    <div id="mapa"></div>
                </div>
            </div>
        </section>

    </main>


    @include('layout.Footer')
</body>

</html>
