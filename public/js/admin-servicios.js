document.addEventListener('DOMContentLoaded', () => {

    document.querySelectorAll('.btn-eliminar').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();

            abrirModal('modal-eliminar');

            document.getElementById('form-eliminar').action =
                btn.closest('form').action;
        })
    });

    document.querySelectorAll('.btn-editar').forEach(btn => {

        btn.addEventListener('click', () => {

            document.getElementById('edit-nombre').value = btn.dataset.nombre;
            document.getElementById('edit-icono').value = btn.dataset.icono ?? '';

            const form = document.getElementById('form-editar');
            form.action = `${form.dataset.action}/${btn.dataset.id}`;

            abrirModal('modal-editar');
        });

    });

});

function abrirModal(id) {
    document.getElementById(id).style.display = 'flex';
}

function cerrarModal() {
    document.querySelectorAll('.modal').forEach(m => {
        m.style.display = 'none';
    });
}

document.addEventListener('click', e => {
    if (e.target.classList.contains('modal')) {
        cerrarModal();
    }
});
