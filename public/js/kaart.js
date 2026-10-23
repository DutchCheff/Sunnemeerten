const kaart = document.querySelector('#kaart');

const GRONINGEN = [53.2194, 6.5665];

const markerPopup = (label) => `<div class='text-center text-xl josefin-sans'><b>${label}</b></div>`;

// Leest de route en tegels uit de data-attributen van partials/map-data.view.php
function readMapData(element) {
    let route = [];
    try {
        route = JSON.parse(element.dataset.route || '[]');
    } catch {
        route = [];
    }

    return {
        route: Array.isArray(route) ? route : [],
        tiles: element.dataset.tiles || 'https://tile.openstreetmap.org/{z}/{x}/{y}.png',
        attribution: element.dataset.attribution || '',
    };
}

function addTiles(map, data) {
    L.tileLayer(data.tiles, {
        maxZoom: 19,
        attribution: data.attribution,
    }).addTo(map);
}

// Met data-static wordt de kaart een vaste preview zonder slepen, zoomen of knoppen
function drawRoute(element) {
    const isStatic = element.hasAttribute('data-static');
    const data = readMapData(element);

    const map = L.map(element, {
        zoomControl: !isStatic,
        dragging: !isStatic,
        touchZoom: !isStatic,
        scrollWheelZoom: !isStatic,
        doubleClickZoom: !isStatic,
        boxZoom: !isStatic,
        keyboard: !isStatic,
    });

    addTiles(map, data);

    if (!data.route.length) {
        map.setView(GRONINGEN, 15);
        return map;
    }

    const polyline = L.polyline(data.route, {
        color: 'red',
        weight: 5,
        opacity: 1.0,
        smoothFactor: 1.0,
        dashArray: '5,8',
        lineJoin: 'round',
        interactive: !isStatic,
    }).addTo(map);

    const startMarker = L.marker(data.route[0], { interactive: !isStatic }).addTo(map);
    const eindeMarker = L.marker(data.route[data.route.length - 1], { interactive: !isStatic }).addTo(map);

    map.fitBounds(polyline.getBounds(), { maxZoom: 17 });

    if (isStatic) {
        return map;
    }

    startMarker.bindPopup(markerPopup('Start'));
    eindeMarker.bindPopup(markerPopup('Einde'));

    map.layerControl = L.control.layers({}, {
        'Route': polyline,
        'Start': startMarker,
        'Einde': eindeMarker,
    }).addTo(map);

    return map;
}

if (kaart && typeof L !== 'undefined') {
    window.paradeMap = drawRoute(kaart);
}
