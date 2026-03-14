document.addEventListener("DOMContentLoaded", () => {
    const modalReserva = document.getElementById("modalReserva");
    const idTipo = document.getElementById("id_tipo");
    const tituloReserva = document.getElementById("tituloReserva");
    const formReserva = document.getElementById("formReserva");
    const metodoPago = document.getElementById("metodoPago");
    const stripeContainer = document.getElementById("stripeContainer");

    const reservacionStoreUrl = window.reservacionStoreUrl || "/reservaciones/store";
    const stripeIntentUrl = window.stripeIntentUrl || "/stripe/intent";
    const stripePublicKey = window.stripePublicKey || "";
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute("content")
        || formReserva.querySelector('input[name="_token"]')?.value
        || "";

    let stripe = null;
    let card = null;
    let cardMounted = false;

    if (stripePublicKey) {
        stripe = Stripe(stripePublicKey);
        card = stripe.elements().create("card");
    }

    document.querySelectorAll(".btn-reservar").forEach((btn) => {
        btn.onclick = () => {
            modalReserva.style.display = "flex";
            idTipo.value = btn.dataset.id;
            tituloReserva.innerText = "Reservar " + btn.dataset.nombre;
        };
    });

    window.cerrarModal = function cerrarModal() {
        modalReserva.style.display = "none";
        stripeContainer.style.display = "none";
    };

    formReserva.onsubmit = async (e) => {
        e.preventDefault();

        if (metodoPago.value === "caja") {
            await guardarReservacion();
            return;
        }

        if (!stripe || !card) {
            alert("Falta configurar STRIPE_KEY en el servidor.");
            return;
        }

        stripeContainer.style.display = "block";
        if (!cardMounted) {
            card.mount("#card-element");
            cardMounted = true;
        }

        const amount = getMontoCentavos();

        const res = await fetch(stripeIntentUrl, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfToken,
                "Accept": "application/json",
            },
            body: JSON.stringify({ amount }),
        });

        if (!res.ok) {
            alert("No se pudo iniciar el pago.");
            return;
        }

        const data = await res.json();
        const { error, paymentIntent } = await stripe.confirmCardPayment(data.clientSecret, {
            payment_method: { card },
        });

        if (error) {
            alert(error.message || "El pago fue rechazado.");
            return;
        }

        if (paymentIntent && paymentIntent.status === "succeeded") {
            await guardarReservacion();
        }
    };

    function getMontoCentavos() {
        const btn = document.querySelector(`.btn-reservar[data-id="${idTipo.value}"]`);
        const precio = btn ? parseFloat(btn.dataset.precio || "0") : 0;
        return Math.round(precio * 100);
    }

    async function guardarReservacion() {
        const fd = new FormData(formReserva);

        const res = await fetch(reservacionStoreUrl, {
            method: "POST",
            headers: { "X-CSRF-TOKEN": csrfToken, "Accept": "application/json" },
            body: fd,
        });

        const data = await res.json().catch(() => ({}));

        if (!res.ok) {
            alert(data.mensaje || "No se pudo crear la reservacion.");
            return;
        }

        alert(data.mensaje || "Reservacion creada");
        location.reload();
    }
});
