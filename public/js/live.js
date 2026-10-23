const liveMap = window.paradeMap;
const paradeStatus = document.querySelector('#parade-status');
const locateButton = document.querySelector('#locate-button');
const locateMessage = document.querySelector('#locate-message');

const POLL_INTERVAL = 10000;

function timeAgo(seconds) {
    if (seconds < 60) {
        return `${seconds} sec geleden`;
    }
    return `${Math.floor(seconds / 60)} min geleden`;
}

if (liveMap && paradeStatus) {
    const statusDot = paradeStatus.querySelector('[data-dot]');
    const statusText = paradeStatus.querySelector('[data-text]');

    const paradeIcon = L.divIcon({
        className: '',
        iconSize: [24, 24],
        iconAnchor: [12, 12],
        html: `<span class="relative flex w-6 h-6">
            <span class="animate-ping absolute inline-flex w-full h-full rounded-full bg-accent2 opacity-75"></span>
            <span class="relative inline-flex w-6 h-6 rounded-full bg-accent2 border-2 border-text shadow-md"></span>
        </span>`,
    });

    const trail = L.polyline([], { color: '#154a58', weight: 3, opacity: 0.9, interactive: false }).addTo(liveMap);
    const paradeMarker = L.marker(GRONINGEN, { icon: paradeIcon, zIndexOffset: 1000 })
        .bindPopup(markerPopup('De parade'));

    let live = null;
    let fetchedAt = 0;
    let pollTimer = null;

    function renderStatus() {
        const isLive = live && live.active;
        statusDot.classList.toggle('bg-accent2', isLive);
        statusDot.classList.toggle('animate-pulse', isLive);
        statusDot.classList.toggle('bg-background', !isLive);

        if (isLive) {
            const seconds = live.seconds_ago + Math.round((Date.now() - fetchedAt) / 1000);
            statusText.textContent = `De parade is live · bijgewerkt ${timeAgo(seconds)}`;
        } else if (live) {
            statusText.textContent = 'De parade is nog niet onderweg';
        }
    }

    function showParade(data) {
        live = data;
        fetchedAt = Date.now();

        if (data.active) {
            paradeMarker.setLatLng([data.latitude, data.longitude]);
            if (!liveMap.hasLayer(paradeMarker)) {
                paradeMarker.addTo(liveMap);
                liveMap.layerControl?.addOverlay(paradeMarker, 'Parade');
            }
            trail.setLatLngs(data.trail);
        } else {
            paradeMarker.remove();
            liveMap.layerControl?.removeLayer(paradeMarker);
            trail.setLatLngs([]);
        }

        renderStatus();
    }

    async function poll() {
        clearTimeout(pollTimer);
        try {
            const response = await fetch('?page=parade_location', { headers: { Accept: 'application/json' } });
            showParade(await response.json());
        } catch {
            statusText.textContent = 'De live locatie kan nu niet worden opgehaald';
        }
        if (!document.hidden) {
            pollTimer = setTimeout(poll, POLL_INTERVAL);
        }
    }

    // Alleen ophalen als het tabblad zichtbaar is, dat spaart batterij en de server
    document.addEventListener('visibilitychange', () => {
        if (document.hidden) {
            clearTimeout(pollTimer);
        } else {
            poll();
        }
    });

    setInterval(() => {
        if (live && live.active && !document.hidden) {
            renderStatus();
        }
    }, 1000);

    poll();
}

// Je eigen locatie blijft in de browser en wordt nooit naar de server gestuurd
if (liveMap && locateButton) {
    let watchId = null;
    let ownDot = null;
    let ownAccuracy = null;

    const geoErrors = {
        1: 'Je hebt geen toestemming gegeven om je locatie te gebruiken. Dit kun je aanzetten in je browserinstellingen.',
        2: 'Je locatie kon niet worden bepaald.',
        3: 'Het duurde te lang om je locatie te bepalen. Probeer het opnieuw.',
    };

    function stopLocating(message = '') {
        if (watchId !== null) {
            navigator.geolocation.clearWatch(watchId);
            watchId = null;
        }
        ownDot?.remove();
        ownAccuracy?.remove();
        ownDot = null;
        ownAccuracy = null;
        locateButton.textContent = 'Waar ben ik?';
        locateMessage.textContent = message;
    }

    function showOwnPosition(position) {
        const latLng = [position.coords.latitude, position.coords.longitude];
        const isFirst = !ownDot;

        if (isFirst) {
            ownAccuracy = L.circle(latLng, { radius: position.coords.accuracy, color: '#2563eb', weight: 1, fillOpacity: 0.15, interactive: false }).addTo(liveMap);
            ownDot = L.circleMarker(latLng, { radius: 8, color: '#ffffff', weight: 2, fillColor: '#2563eb', fillOpacity: 1 })
                .bindPopup(markerPopup('Jij bent hier'))
                .addTo(liveMap);
            liveMap.setView(latLng, Math.max(liveMap.getZoom(), 16));
        } else {
            ownDot.setLatLng(latLng);
            ownAccuracy.setLatLng(latLng).setRadius(position.coords.accuracy);
        }

        locateMessage.textContent = `Nauwkeurig tot op ${Math.round(position.coords.accuracy)} meter`;
    }

    locateButton.addEventListener('click', () => {
        if (watchId !== null) {
            stopLocating();
            return;
        }
        if (!('geolocation' in navigator)) {
            locateMessage.textContent = 'Je browser kan je locatie niet bepalen.';
            return;
        }
        if (!window.isSecureContext) {
            locateMessage.textContent = 'Je locatie werkt alleen via een beveiligde verbinding (HTTPS).';
            return;
        }

        locateButton.textContent = 'Stop met volgen';
        locateMessage.textContent = 'Je locatie wordt gezocht...';
        watchId = navigator.geolocation.watchPosition(
            showOwnPosition,
            (error) => {
                // Bij een time-out blijft watchPosition het gewoon opnieuw proberen
                if (error.code === error.TIMEOUT) {
                    locateMessage.textContent = geoErrors[3];
                    return;
                }
                stopLocating(geoErrors[error.code] || geoErrors[2]);
            },
            { enableHighAccuracy: true, maximumAge: 10000, timeout: 20000 }
        );
    });
}
