// ==============================
// BASE URL GLOBAL
// ==============================
const BASE_URL = document.querySelector('meta[name="base-url"]').content;


// ==============================
// DOM READY
// ==============================
document.addEventListener('DOMContentLoaded', () => {

    // Confirmación eliminar
    document.querySelectorAll('.btn-eliminar').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();

            abrirModal('modal-eliminar');

            document.getElementById('form-eliminar').action =
                btn.closest('form').action;
        })
    });


    // ==============================
    // EDITAR TIPO HABITACIÓN
    // ==============================
    document.querySelectorAll('.btn-editar').forEach(btn => {
        btn.addEventListener('click', () => {

            document.getElementById('edit-nombre').value = btn.dataset.nombre;
            document.getElementById('edit-descripcion').value = btn.dataset.descripcion;
            document.getElementById('edit-precio').value = btn.dataset.precio;
            document.getElementById('edit-capacidad').value = btn.dataset.capacidad;

            const form = document.getElementById('form-editar');
            form.action = `${form.dataset.action}/${btn.dataset.id}`;

            abrirModal('modal-editar');
        });
    });


    // ==============================
    // SERVICIOS POR TIPO
    // ==============================
    document.querySelectorAll('.btn-servicios').forEach(btn => {
        btn.addEventListener('click', async () => {

            const id = btn.dataset.id;
            const nombre = btn.dataset.nombre;

            document.getElementById('titulo-servicios').innerText =
                `Servicios de ${nombre}`;

            await actualizarServicios(id);

            abrirModal('modal-servicios');
        });
    });

});


// ==============================
// FUNCIONES GLOBALES
// ==============================

function abrirModal(id) {
    document.getElementById(id).style.display = 'flex';
}

function cerrarModal() {
    document.querySelectorAll('.modal')
        .forEach(m => m.style.display = 'none');
}


// ==============================
// AGREGAR SERVICIO
// ==============================
async function agregarServicio(idTipo, idServicio) {

    await fetch(`${BASE_URL}/Admin/Tipos-Habitacion/${idTipo}/Servicios`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document
                .querySelector('meta[name="csrf-token"]')
                .content
        },
        body: JSON.stringify({ id_servicio: idServicio })
    });

    await actualizarServicios(idTipo);
}


// ==============================
// QUITAR SERVICIO
// ==============================
async function quitarServicio(idTipo, idServicio) {

    await fetch(`${BASE_URL}/Admin/Tipos-Habitacion/${idTipo}/Servicios/${idServicio}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document
                .querySelector('meta[name="csrf-token"]')
                .content
        }
    });

    await actualizarServicios(idTipo);
}


// ==============================
// RENDERIZAR SERVICIOS (ÚNICA FUENTE)
// ==============================
async function actualizarServicios(id) {

    try {

        const response = await fetch(
            `${BASE_URL}/Admin/Tipos-Habitacion/${id}/Servicios`
        );

        if (!response.ok) {
            throw new Error('Error al obtener servicios');
        }

        const data = await response.json();

        const asignados = document.getElementById('lista-asignados');
        const disponibles = document.getElementById('lista-disponibles');

        asignados.innerHTML = '';
        disponibles.innerHTML = '';

        const idsAsignados = data.asignados.map(s => s.id_servicio);

        // Asignados
        data.asignados.forEach(s => {
            asignados.innerHTML += `
                <li class="item-servicio asignado">
                    <span>${s.nombre}</span>
                    <button onclick="quitarServicio(${id}, ${s.id_servicio})">✖</button>
                </li>`;
        });

        // Disponibles
        data.todos.forEach(s => {
            if (!idsAsignados.includes(s.id_servicio)) {
                disponibles.innerHTML += `
                    <li class="item-servicio disponible">
                        <span>${s.nombre}</span>
                        <button onclick="agregarServicio(${id}, ${s.id_servicio})">＋</button>
                    </li>`;
            }
        });

    } catch (error) {
        console.error(error);
        alert('No se pudieron actualizar los servicios.');
    }
}
