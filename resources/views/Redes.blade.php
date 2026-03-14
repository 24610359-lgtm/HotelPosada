<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Hotel Paraíso</title>
    <link rel="stylesheet" href="{{ asset('css/interfaz.css') }}">
    <script src="{{ asset('js/interfaz.js') }}" defer></script>
    <style>
        .post-card {
            max-width: 600px;
            margin: auto;
            margin-bottom: 1.5rem;
            padding: 1rem;
            border-radius: 10px;
            background: white;
            box-shadow: 0 2px 6px rgba(0, 0, 0, .1);
        }

        .feed-container {
            max-width: 700px;
            margin: auto;
        }

        .post-card {
            background: white;
            border-radius: 12px;
            padding: 1.2rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 3px 8px rgba(0, 0, 0, .08);
            transition: transform 0.2s ease;
        }

        .post-card:hover {
            transform: translateY(-3px);
        }

        .dark .post-card {
            background: #2a2a2a;
        }

        /* Header */
        .post-header {
            display: flex;
            align-items: center;
            gap: .8rem;
            margin-bottom: 1rem;
        }

        .post-avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: var(--color-Acento);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }

        .post-username {
            font-weight: 600;
        }

        .post-time {
            font-size: .85rem;
            opacity: .6;
        }

        /* Contenido */
        .post-body {
            margin-bottom: 1rem;
            line-height: 1.5;
        }

        /* Tags */
        .post-tags {
            margin-bottom: 1rem;
        }

        .post-tag {
            background: var(--color-NavbarFooterClaro);
            color: var(--color-LetrasNavBarFooter);
            padding: 4px 10px;
            border-radius: 6px;
            font-size: .75rem;
            margin-right: 6px;
        }

        /* Estadísticas */
        .post-stats {
            display: flex;
            justify-content: space-between;
            font-size: .85rem;
            opacity: .7;
            padding-bottom: .8rem;
            border-bottom: 1px solid rgba(0, 0, 0, .1);
        }

        .dark .post-stats {
            border-color: rgba(255, 255, 255, .1);
        }

        /* Botones */
        .post-actions {
            display: flex;
            justify-content: space-around;
            padding-top: .8rem;
        }

        .post-btn {
            background: none;
            border: none;
            cursor: pointer;
            color: var(--color-LetraCuerpoClaro);
            font-weight: 500;
        }

        .dark .post-btn {
            color: var(--color-LetraCuerpoOscuro);
        }

        .post-btn:hover {
            color: var(--color-Acento);
        }
    </style>
</head>

<body class="bg-slate-100 dark:bg-slate-900 text-slate-900 dark:text-slate-100">

    @include('layout.Nav-bar')

    <main class="contenido-principal">

        <hr>

        <h2>Publicaciones</h2>
        <div class="feed-container" id="feed-posts"></div>

    </main>

    @include('layout.Footer')

    <script>
        const API_POSTS = "https://dummyjson.com/posts?limit=6";
        const feed = document.getElementById("feed-posts");

        document.addEventListener("DOMContentLoaded", cargarPosts);

        async function cargarPosts() {
            const res = await fetch(API_POSTS);
            const data = await res.json();

            data.posts.forEach(post => {

                const div = document.createElement("div");
                div.className = "post-card";

                div.innerHTML = `
            <div class="post-header">
                <div class="post-avatar">
                    U${post.userId}
                </div>
                <div>
                    <div class="post-username">Usuario ${post.userId}</div>
                    <div class="post-time">Publicado hace 3 horas</div>
                </div>
            </div>

            <div class="post-body">
                ${post.body}
            </div>

            <div class="post-tags">
                ${post.tags.map(tag => `<span class="post-tag">#${tag}</span>`).join('')}
            </div>

            <div class="post-stats">
                <span>👍 ${post.reactions.likes}</span>
                <span>${post.views} Visualizaciones</span>
            </div>

            <div class="post-actions">
                <button class="post-btn">👍 Me gusta</button>
                <button class="post-btn">💬 Comentar</button>
                <button class="post-btn">↗ Compartir</button>
            </div>
        `;

                feed.appendChild(div);
            });
        }

    </script>

</body>

</html>