const botonTema = document.getElementById('botonTema')
const documento = document.documentElement
const hamburger = document.getElementById('hamburger')
const navLinks = document.querySelector('.nav-links')
const enlaces = document.querySelectorAll('.nav-links a')

/* Tema */
const temaGuardado = localStorage.getItem('tema')

function aplicarTema(tema) {
    if (tema === 'oscuro') {
        documento.classList.add('dark')
        botonTema.textContent = '🌙'
    } else {
        documento.classList.remove('dark')
        botonTema.textContent = '☀️'
    }
}

aplicarTema(temaGuardado || 'claro')

botonTema.addEventListener('click', () => {
    const temaActual = documento.classList.contains('dark') ? 'claro' : 'oscuro'
    localStorage.setItem('tema', temaActual)
    aplicarTema(temaActual)
})

/* Hamburguesa  */
hamburger.addEventListener('click', () => {
    navLinks.classList.toggle('activo')
})

/* Marcar */
const rutaActual = window.location.pathname

enlaces.forEach(enlace => {
    if (rutaActual === enlace.getAttribute('href')) {
        enlace.classList.add('activo')
    }
})
 /* Cerrar menu */
 document.addEventListener('click', (e) => {
    const clickDentroMenu = navLinks.contains(e.target)
    const clickHamburger = hamburger.contains(e.target)

    if (!clickDentroMenu && !clickHamburger) {
        navLinks.classList.remove('activo')
    }
})

enlaces.forEach(enlace => {
    enlace.addEventListener('click', () => {
        navLinks.classList.remove('activo')
    })
})
