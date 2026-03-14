document.addEventListener('DOMContentLoaded', async () => {
    try {
        const response = await fetch('../public/ubicacion')
        const data = await response.json()

        const mapa = L.map('mapa').setView([data.latitud, data.longitud], 15)

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap'
        }).addTo(mapa)

        L.marker([data.latitud, data.longitud])
            .addTo(mapa)
            .bindPopup(data.hotel)

        setTimeout(() => mapa.invalidateSize(), 300)

    } catch (error) {
        console.error('Error al cargar la ubicación', error)
    }
})

/*
document.addEventListener('DOMContentLoaded', () => {
    const mapa = L.map('mapa').setView([20.47766, -97.00765], 15);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap'
    }).addTo(mapa);

    L.marker([20.47766, -97.00765])
        .addTo(mapa)
        .bindPopup('Ubicación en Aldama s/n, Centro, Tecolutla')
        .openPopup();
});
*/