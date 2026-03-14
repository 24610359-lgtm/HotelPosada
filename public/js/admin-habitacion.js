const BASE_URL = document.querySelector('meta[name="base-url"]').content;

function abrirModal(id) {
    document.getElementById(id).style.display = 'flex';
}

function cerrarModal() {
    document.querySelectorAll('.modal').forEach(m => {
        m.style.display = 'none';
    })
}

// EDITAR
document.querySelectorAll('.btn-editar').forEach(btn => {
    btn.addEventListener('click', () => {

        abrirModal('modal-editar');

        document.getElementById('edit-numero').value = btn.dataset.numero;
        document.getElementById('edit-tipo').value = btn.dataset.tipo;
        document.getElementById('edit-estado').value = btn.dataset.estado;

        document.getElementById('form-editar').action =
            BASE_URL + '/Admin/Habitaciones/' + btn.dataset.id;
    })
});

// ELIMINAR
document.querySelectorAll('.btn-eliminar').forEach(btn => {
    btn.addEventListener('click', (e) => {
        e.preventDefault();

        abrirModal('modal-eliminar');

        document.getElementById('form-eliminar').action =
            btn.closest('form').action;
    })
});
