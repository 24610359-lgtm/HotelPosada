document.addEventListener("DOMContentLoaded", () => {

    const input = document.getElementById("searchInput");
    const btnBuscar = document.getElementById("btnBuscar");
    const container = document.getElementById("videos");
    const loader = document.getElementById("loader");
    const btnMore = document.getElementById("btnMore");

    const modal = document.getElementById("modal");
    const closeBtn = document.getElementById("close");
    const openYT = document.getElementById("openYT");
    const youtubeSearchUrl = window.youtubeSearchUrl || "/youtube-search";

    let nextPageToken = "";
    let player;

    // 🔥 aseguramos modal oculto al iniciar
    modal.classList.add("hidden");

    // cargar api player
    var tag = document.createElement("script");
    tag.src = "https://www.youtube.com/iframe_api";
    document.body.appendChild(tag);

    window.onYouTubeIframeAPIReady = function () {
        player = new YT.Player("player", {
            height: "100%",
            width: "100%",
            videoId: "",
            playerVars: { playsinline: 1 }
        });
    };

    // =================
    // EVENTOS
    // =================
    btnBuscar.onclick = buscarVideos;

    input.addEventListener("keypress", e => {
        if (e.key === "Enter") buscarVideos();
    });

    btnMore.onclick = () => cargarVideos(input.value);

    closeBtn.onclick = cerrarModal;

    modal.addEventListener("click", e => {
        if (e.target === modal) cerrarModal();
    });

    document.addEventListener("keydown", e => {
        if (e.key === "Escape") cerrarModal();
    });

    // =================
    // BUSCAR
    // =================
    async function buscarVideos() {
        const query = input.value.trim();
        if (!query) return;

        container.innerHTML = "";
        nextPageToken = "";
        await cargarVideos(query);
    }

    // =================
    // CARGAR
    // =================
    async function cargarVideos(query) {

        loader.classList.remove("hidden");

        try {
            const res = await fetch(`${youtubeSearchUrl}?q=${encodeURIComponent(query)}&pageToken=${nextPageToken}`);
            if (!res.ok) {
                throw new Error(`HTTP ${res.status}`);
            }
            const data = await res.json();

            nextPageToken = data.nextPageToken || "";

            if (nextPageToken) btnMore.classList.remove("hidden");
            else btnMore.classList.add("hidden");

            mostrarVideos(data.items || []);
        }
        catch (e) {
            console.error(e);
            alert("Error cargando los videos");
        }

        loader.classList.add("hidden");
    }

    // =================
    // RENDER
    // =================
    function mostrarVideos(videos) {

        videos.forEach(video => {

            const videoId = video.id.videoId;
            const title = video.snippet.title;
            const thumb = video.snippet.thumbnails.medium.url;
            const channel = video.snippet.channelTitle;

            const card = document.createElement("div");
            card.className = "card";

            card.innerHTML = `
            <img src="${thumb}">
            <h4>${title}</h4>
            <p>${channel}</p>
        `;

            card.onclick = () => abrirVideo(videoId);

            container.appendChild(card);
        });
    }

    // =================
    // ABRIR
    // =================
    function abrirVideo(videoId) {

        modal.classList.remove("hidden");

        openYT.href = `https://www.youtube.com/watch?v=${videoId}`;

        if (player && player.loadVideoById) {
            player.loadVideoById(videoId);
        }
    }

    // =================
    // CERRAR
    // =================
    function cerrarModal() {

        modal.classList.add("hidden");

        if (player && player.stopVideo) {
            player.stopVideo();
        }
    }

});
