let maCarte;
const coordsSPA = [45.0715, 3.8615];

function afficherCarte() {
    if (maCarte) return;

    maCarte = L.map('mapSPA_XX', {
        scrollWheelZoom: false
    }).setView(coordsSPA, 15);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap'
    }).addTo(maCarte);

    L.circleMarker(coordsSPA, {
        radius: 12,
        fillColor: "#496537",
        color: "#ffffff",
        weight: 3,
        fillOpacity: 1
    }).addTo(maCarte)
        .bindPopup('<b>SPA Haute-Loire</b><br>7 Impasse du Refuge')
        .openPopup();
}

function initCarte() {
    const mapElement = document.getElementById("mapSPA_XX");

    if (mapElement) {
        afficherCarte();
    }
}

window.addEventListener("load", initCarte);