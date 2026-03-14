<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reserva test</title>
    <script src="https://js.stripe.com/v3/"></script>

    <style>
        body {
            font-family: Arial;
            background: #f5f5f5;
            padding: 40px
        }

        .card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            width: 420px;
            margin: auto
        }

        button {
            background: #635bff;
            color: white;
            border: none;
            padding: 12px;
            width: 100%;
            cursor: pointer
        }

        #card-element {
            border: 1px solid #ddd;
            padding: 10px;
            margin: 15px 0
        }

        #msg {
            margin-top: 15px;
            font-weight: bold
        }
    </style>
</head>

<body>

    <div class="card">
        <h3>Reservación prueba</h3>

        <label>Categoría</label>
        <select id="categoria">
            @foreach($categorias as $c)
                <option value="{{ $c->precio_noche }}">{{ $c->nombre }} - ${{ number_format($c->precio_noche, 2) }}</option>
            @endforeach
        </select>

        <label>Entrada</label>
        <input type="date" id="entrada">

        <label>Salida</label>
        <input type="date" id="salida">

        <h4>Pago</h4>
        <div id="card-element"></div>
        <button id="pagar">Simular pago</button>

        <div id="msg"></div>
    </div>

    <script>
        const stripe = Stripe("{{env('STRIPE_KEY')}}");
        const elements = stripe.elements();
        const card = elements.create("card", {
            disableLink: true,
            hidePostalCode: true
        });
        card.mount("#card-element");
        const msg = document.getElementById("msg");

        document.getElementById("pagar").onclick = async () => {
            try {
                const precio = Number(document.getElementById("categoria").value);
                if (!Number.isFinite(precio) || precio <= 0) {
                    throw new Error("Selecciona una categoría con precio válido.");
                }
                console.log("[Pago] monto a enviar:", precio);

                const res = await fetch("{{url('/crear-pago-test')}}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{csrf_token()}}",
                        "Accept": "application/json"
                    },
                    body: JSON.stringify({ monto: precio })
                });
                console.log("[Pago] status HTTP:", res.status);

                if (!res.ok) {
                    const body = await res.text();
                    console.error("Respuesta no JSON del servidor:", body);
                    throw new Error(`No se pudo crear el pago (HTTP ${res.status}).`);
                }

                const data = await res.json();
                console.log("[Pago] JSON de respuesta:", data);

                const { error } = await stripe.confirmCardPayment(data.clientSecret, {
                    payment_method: { card: card }
                });

                if (error) {
                    msg.style.color = "red";
                    msg.innerText = error.message;
                    return;
                }

                msg.style.color = "green";
                const entrada = document.getElementById("entrada").value;
                const salida = document.getElementById("salida").value;
                msg.innerText = `Reserva simulada: inicia 1pm ${entrada} y termina 11am ${salida}`;
            } catch (e) {
                msg.style.color = "red";
                msg.innerText = e.message;
            }
        }
    </script>

</body>

</html>
