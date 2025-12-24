function initMapBlock() {
  const mapElement = document.getElementById("map");
  if (!mapElement) return;

  if (typeof google === "undefined" || typeof google.maps === "undefined") {
    console.warn(
      "Google Maps API non chargée. Vérifiez votre clé API ou les conflits."
    );
    return;
  }

  const lat = parseFloat(mapElement.dataset.lat);
  const lng = parseFloat(mapElement.dataset.lng);
  const zoom = parseInt(mapElement.dataset.zoom) || 14;
  const label = mapElement.dataset.label || "Nous trouver";

  if (isNaN(lat) || isNaN(lng)) {
    console.warn("Coordonnées invalides");
    return;
  }

  const position = { lat: lat, lng: lng };

  const map = new google.maps.Map(mapElement, {
    center: position,
    zoom: zoom,
    disableDefaultUI: false,
  });

  const marker = new google.maps.Marker({
    position: position,
    map: map,
    title: label,
    animation: google.maps.Animation.DROP,
  });

  if (label) {
    const infoWindow = new google.maps.InfoWindow({
      content: `<div style="color:black; padding:5px;"><strong>${label}</strong></div>`,
    });

    marker.addListener("click", () => {
      infoWindow.open(map, marker);
    });
  }
}

document.addEventListener("DOMContentLoaded", initMapBlock);
