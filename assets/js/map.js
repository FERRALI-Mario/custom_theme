function initMapBlock() {
  const mapElement = document.getElementById("map");
  if (!mapElement || typeof L === "undefined") {
    console.warn("Leaflet ou élément #map introuvable");
    return;
  }

  const lat = parseFloat(mapElement.dataset.lat);
  const lng = parseFloat(mapElement.dataset.lng);
  const zoom = parseInt(mapElement.dataset.zoom) || 14;

  if (isNaN(lat) || isNaN(lng)) {
    console.warn("Coordonnées invalides pour la carte");
    return;
  }

  const map = L.map(mapElement).setView([lat, lng], zoom);

  L.tileLayer(
    "https://tiles.stadiamaps.com/tiles/alidade_smooth/{z}/{x}/{y}{r}.png",
    {
      attribution:
        "&copy; Stadia Maps, © OpenMapTiles, © OpenStreetMap contributors",
    }
  ).addTo(map);

  const customIcon = L.icon({
    iconUrl: "https://cdn-icons-png.flaticon.com/512/684/684908.png",
    iconSize: [38, 38],
    iconAnchor: [19, 38], // centre X, base Y
    popupAnchor: [0, -30],
  });

  const label = mapElement.dataset.label || "Localisation";

  L.marker([lat, lng], { icon: customIcon })
    .addTo(map)
    .bindPopup(label)
    .openPopup();

  setTimeout(() => {
    map.invalidateSize();
  }, 300);
}

document.addEventListener("DOMContentLoaded", initMapBlock);
