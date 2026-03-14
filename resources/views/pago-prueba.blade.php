<!DOCTYPE html>
<html>

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pago de prueba</title>

    <style>
        body {
            font-family: Arial;
            background: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, .2);
            text-align: center;
        }

        button {
            padding: 10px 20px;
            border: none;
            background: #28a745;
            color: white;
            border-radius: 5px;
            cursor: pointer;
        }

        #mensaje {
            margin-top: 15px;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <div class="card">
        <h2>Pago de prueba</h2>
        <p>Monto: $100</p>
        <button id="btnPago">Pagar</button>
        <div id="mensaje"></div>
    </div>

    <script>
        document.getElementById('btnPago').addEventListener('click', () => {

            fetch('/procesar-pago-prueba', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ monto: 100 })
            })
                .then(res => res.json())
                .then(data => {
                    const msg = document.getElementById('mensaje');
                    msg.innerHTML = data.message;
                    msg.style.color = data.success ? 'green' : 'red';
                });
        });
    </script>

</body>

</html>