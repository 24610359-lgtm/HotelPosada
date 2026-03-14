<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Hotel Paraíso</title>
    <link rel="stylesheet" href="{{ asset('css/interfaz.css') }}">
    <script src="{{ asset('js/interfaz.js') }}" defer></script>


    <style>
        .reseñas-section {
            margin-top: 2rem;
        }

        .btn-acento {
            background: var(--color-Acento);
            color: white;
            border: none;
            padding: .6rem 1rem;
            cursor: pointer;
            border-radius: 5px;
        }

        .lista-reseñas {
            margin-top: 1.5rem;
            display: grid;
            gap: 1rem;
        }

        .reseña-card {
            padding: 1rem;
            background: rgba(0, 0, 0, 0.05);
            border-radius: 8px;
        }

        .dark .reseña-card {
            background: rgba(255, 255, 255, 0.08);
        }

        .modal {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .5);
            display: none;
            justify-content: center;
            align-items: center;
        }

        .modal-contenido {
            background: var(--color-CuerpoClaro);
            padding: 2rem;
            border-radius: 10px;
            width: 90%;
            max-width: 400px;
            position: relative;
        }

        .dark .modal-contenido {
            background: var(--color-CuerpoOscuro);
        }

        .modal input,
        .modal textarea,
        .modal select {
            width: 100%;
            margin-bottom: 1rem;
            padding: .5rem;
        }

        .cerrar-modal {
            position: absolute;
            top: 10px;
            right: 10px;
            background: none;
            border: none;
            font-size: 1.2rem;
            cursor: pointer;
        }
    </style>
</head>

<body class="bg-slate-100 dark:bg-slate-900 text-slate-900 dark:text-slate-100">

    @include('layout.Nav-bar')

    <main class="contenido-principal">
        <section class="reseñas-section">
            <h2>Reseñas</h2>
            <button id="abrirModal" class="btn-acento">Agregar reseña</button>

            <div id="listaReseñas" class="lista-reseñas"></div>
        </section>

        <!-- Modal -->
        <div id="modalReseña" class="modal">
            <div class="modal-contenido">
                <h3>Nueva reseña</h3>
                <form id="formReseña">
                    <input type="text" id="nombre" placeholder="Tu nombre" required>
                    <textarea id="comentario" placeholder="Escribe tu experiencia..." required></textarea>
                    <select id="calificacion" required>
                        <option value="">Calificación</option>
                        <option value="5">⭐⭐⭐⭐⭐</option>
                        <option value="4">⭐⭐⭐⭐</option>
                        <option value="3">⭐⭐⭐</option>
                        <option value="2">⭐⭐</option>
                        <option value="1">⭐</option>
                    </select>
                    <button type="submit" class="btn-acento">Guardar</button>
                </form>
                <button id="cerrarModal" class="cerrar-modal">✖</button>
            </div>
        </div>

    </main>

    @include('layout.Footer')

    <script type="module">
        import { initializeApp } from "https://www.gstatic.com/firebasejs/12.9.0/firebase-app.js"; 
        import { getAnalytics } from "https://www.gstatic.com/firebasejs/12.9.0/firebase-analytics.js";
        import {
            getFirestore,
            collection,
            addDoc,
            onSnapshot,
            serverTimestamp,
            query,
            orderBy
        } from "https://www.gstatic.com/firebasejs/12.9.0/firebase-firestore.js";

        const firebaseConfig = {
            apiKey: "AIzaSyD39tRmFKZ4qt5efZul2lDijAFB4iNdjro",
            authDomain: "posadadelaluz.firebaseapp.com",
            projectId: "posadadelaluz",
            storageBucket: "posadadelaluz.firebasestorage.app",
            messagingSenderId: "1025291391764",
            appId: "1:1025291391764:web:5be407c43cf4874dc26801",
            measurementId: "G-N6YBXG1K4W"
        };

        const app = initializeApp(firebaseConfig);
        getAnalytics(app);
        const db = getFirestore(app);

        const modal = document.getElementById("modalReseña");
        const abrir = document.getElementById("abrirModal");
        const cerrar = document.getElementById("cerrarModal");
        const form = document.getElementById("formReseña");
        const lista = document.getElementById("listaReseñas");

        abrir.onclick = () => modal.style.display = "flex";
        cerrar.onclick = () => modal.style.display = "none";
        window.onclick = (e) => {
            if (e.target === modal) modal.style.display = "none";
        };

        form.addEventListener("submit", async (e) => {
            e.preventDefault();

            const nombre = document.getElementById("nombre").value.trim();
            const comentario = document.getElementById("comentario").value.trim();
            const calificacion = parseInt(document.getElementById("calificacion").value);

            if (!nombre || !comentario || !calificacion) {
                alert("Completa todos los campos.");
                return;
            }

            try {
                await addDoc(collection(db, "resenas"), {
                    nombre,
                    comentario,
                    calificacion,
                    fecha: serverTimestamp()
                });

                form.reset();
                modal.style.display = "none";
            } catch (error) {
                console.error("Error al guardar:", error);
                alert("Hubo un error al guardar la reseña.");
            }
        });

        // Mostrar
        const q = query(collection(db, "resenas"), orderBy("fecha", "desc"));

        onSnapshot(q, (snapshot) => {
            lista.innerHTML = "";

            if (snapshot.empty) {
                lista.innerHTML = "<p>No hay reseñas todavía.</p>";
                return;
            }

            snapshot.forEach((doc) => {
                const data = doc.data();

                const card = document.createElement("div");
                card.classList.add("reseña-card");

                card.innerHTML = `
                <strong>${data.nombre}</strong>
                <p>${data.comentario}</p>
                <div>${"⭐".repeat(data.calificacion)}</div>
            `;

                lista.appendChild(card);
            });
        });
    </script>

</body>

</html>