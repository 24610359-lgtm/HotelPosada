
// Activar subnav
document.querySelectorAll('.admin-subnav a').forEach(link => {
    if (link.href === window.location.href) {
        link.classList.add('activo');
    }
});
