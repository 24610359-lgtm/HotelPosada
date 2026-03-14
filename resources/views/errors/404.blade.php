<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>404 · Página no encontrada</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        :root {
            --bg: #0f172a;
            --fg: #e5e7eb;
            --accent: #38bdf8;
            --muted: #94a3b8;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: radial-gradient(circle at top, #020617, var(--bg));
            color: var(--fg);
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }

        .container {
            text-align: center;
            padding: 2rem;
            max-width: 500px;
        }

        h1 {
            font-size: clamp(4rem, 10vw, 6rem);
            margin: 0;
            color: var(--accent);
        }

        h2 {
            font-weight: 600;
            margin: 0.5rem 0;
        }

        p {
            color: var(--muted);
            line-height: 1.6;
            margin: 1rem 0 2rem;
        }

        a {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            border-radius: 999px;
            background: var(--accent);
            color: #020617;
            font-weight: 600;
            text-decoration: none;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        a:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(56, 189, 248, 0.35);
        }

        .small {
            margin-top: 1.5rem;
            font-size: 0.85rem;
            color: var(--muted);
        }
    </style>
</head>

<body>
    
    <div class="container">
        <h1>404</h1>
        <h2>PAGINA NO ENCONTRADA</h2>
        

        <a href="{{ url('/Home') }}">
            Volver a casa
        </a>

        <div class="small">
            Este sitio ya no forma parte o nunca existio
        </div>
    </div>
</body>

</html>