const tracker = document.querySelector('#tracker');

if (tracker) {
    const field = (name) => tracker.querySelector(`[data-${name}]`);
    const toggleButton = field('toggle');
    const simulateButton = field('simulate');
    const errorBox = field('error');
    const statusText = field('status');
    const lastSentText = field('last-sent');
    const accuracyText = field('accuracy');
    const sentCountText = field('sent-count');
    const wakeLockText = field('wake-lock');

    const SEND_INTERVAL = 10000;
    const SIMULATE_INTERVAL = 2000;
    const SIMULATE_STEP_METERS = 20;
    const timeFormat = new Intl.DateTimeFormat('nl-NL', { hour: '2-digit', minute: '2-digit', second: '2-digit' });

    // mode is null (uit), 'gps' (echte telefoon) of 'simulate' (nep-rit voor testen)
    let mode = null;
    let watchId = null;
    let sendTimer = null;
    let simulateTimer = null;
    let pending = null;
    let lastSentAt = 0;
    let sentCount = 0;
    let wakeLock = null;

    function showError(message) {
        errorBox.textContent = message;
        errorBox.classList.toggle('hidden', !message);
    }

    function render() {
        const running = mode !== null;
        toggleButton.textContent = running ? 'Stop' : 'Start';
        toggleButton.classList.toggle('bg-accent1', !running);
        toggleButton.classList.toggle('bg-accent2', running);

        if (simulateButton) {
            simulateButton.disabled = running;
            simulateButton.classList.toggle('opacity-50', running);
        }
    }

    async function post(action, fields = {}) {
        const body = new FormData();
        body.append('csrf', tracker.dataset.csrf);
        body.append('action', action);
        Object.entries(fields).forEach(([name, value]) => body.append(name, value));

        const response = await fetch('?page=tracker', { method: 'POST', body, headers: { Accept: 'application/json' } });
        let data;
        try {
            data = await response.json();
        } catch {
            throw new Error('Onverwacht antwoord van de server. Ben je nog ingelogd als beheerder?');
        }
        if (!response.ok) {
            throw new Error(data.error || 'Er ging iets mis bij het versturen.');
        }
        return data;
    }

    async function sendPosition(coords) {
        try {
            await post('position', {
                latitude: coords.latitude,
                longitude: coords.longitude,
                accuracy: Math.round(coords.accuracy),
            });
            sentCount++;
            sentCountText.textContent = sentCount;
            lastSentText.textContent = timeFormat.format(new Date());
            showError('');
            if (mode === 'gps') {
                statusText.textContent = 'Live';
            }
        } catch (error) {
            if (mode) {
                showError(error.message);
            }
        }
    }

    // watchPosition kan vaak afgaan; we sturen hooguit elke 10 seconden de nieuwste positie
    function trySend() {
        if (!pending || Date.now() - lastSentAt < SEND_INTERVAL) {
            return;
        }
        lastSentAt = Date.now();
        sendPosition(pending);
        pending = null;
    }

    async function requestWakeLock() {
        if (!('wakeLock' in navigator)) {
            wakeLockText.textContent = 'Niet ondersteund';
            return;
        }
        try {
            wakeLock = await navigator.wakeLock.request('screen');
            wakeLockText.textContent = 'Ja';
            wakeLock.addEventListener('release', () => {
                wakeLock = null;
                if (mode) {
                    wakeLockText.textContent = 'Nee, open de pagina weer';
                }
            });
        } catch {
            wakeLockText.textContent = 'Niet gelukt';
        }
    }

    // De wake lock vervalt als je van tabblad wisselt, dus vragen we hem opnieuw aan
    document.addEventListener('visibilitychange', () => {
        if (mode && document.visibilityState === 'visible' && !wakeLock) {
            requestWakeLock();
        }
    });

    async function begin(newMode) {
        showError('');
        toggleButton.disabled = true;
        try {
            await post('start');
        } catch (error) {
            showError(error.message);
            return false;
        } finally {
            toggleButton.disabled = false;
        }

        mode = newMode;
        tracker.querySelector('[data-resume]')?.remove();
        render();
        requestWakeLock();
        return true;
    }

    async function startGps() {
        if (!window.isSecureContext) {
            showError('Geolocatie werkt alleen via HTTPS.');
            return;
        }
        if (!('geolocation' in navigator)) {
            showError('Deze browser ondersteunt geen GPS-locatie.');
            return;
        }
        if (!await begin('gps')) {
            return;
        }

        statusText.textContent = 'GPS zoeken...';
        watchId = navigator.geolocation.watchPosition(
            (position) => {
                pending = position.coords;
                accuracyText.textContent = `${Math.round(position.coords.accuracy)} m`;
                trySend();
            },
            (error) => {
                if (error.code === error.PERMISSION_DENIED) {
                    stop();
                    showError('Geen toestemming voor GPS. Sta locatietoegang toe voor deze site in je browserinstellingen en druk opnieuw op Start.');
                } else if (error.code === error.TIMEOUT) {
                    showError('Nog geen GPS-positie gevonden. We blijven het proberen.');
                } else {
                    showError('GPS-positie niet beschikbaar. We blijven het proberen.');
                }
            },
            { enableHighAccuracy: true, maximumAge: 0, timeout: 30000 }
        );
        sendTimer = setInterval(trySend, 1000);
    }

    // Verdeelt de route in stukjes van ongeveer 20 meter, zodat de nep-parade vloeiend loopt
    function simulateSteps(route) {
        const steps = [route[0]];
        for (let i = 1; i < route.length; i++) {
            const [fromLat, fromLng] = route[i - 1];
            const [toLat, toLng] = route[i];
            const meters = Math.hypot((toLat - fromLat) * 111320, (toLng - fromLng) * 111320 * Math.cos(fromLat * Math.PI / 180));
            const count = Math.max(1, Math.ceil(meters / SIMULATE_STEP_METERS));
            for (let step = 1; step <= count; step++) {
                steps.push([fromLat + (toLat - fromLat) * step / count, fromLng + (toLng - fromLng) * step / count]);
            }
        }
        return steps;
    }

    async function startSimulation() {
        let route = [];
        try {
            route = JSON.parse(simulateButton.dataset.route || '[]');
        } catch {
            route = [];
        }
        if (route.length < 2) {
            showError('Er is nog geen route om te simuleren.');
            return;
        }
        if (!await begin('simulate')) {
            return;
        }

        const steps = simulateSteps(route);
        let index = 0;

        const walk = () => {
            if (mode !== 'simulate') {
                return;
            }
            const [latitude, longitude] = steps[index];
            const accuracy = 5 + Math.random() * 10;
            accuracyText.textContent = `${Math.round(accuracy)} m`;
            statusText.textContent = `Simulatie: punt ${index + 1} van ${steps.length}`;
            sendPosition({ latitude, longitude, accuracy });

            index++;
            if (index < steps.length) {
                simulateTimer = setTimeout(walk, SIMULATE_INTERVAL);
            } else {
                statusText.textContent = 'Simulatie klaar, druk op Stop';
            }
        };
        walk();
    }

    async function stop() {
        mode = null;
        if (watchId !== null) {
            navigator.geolocation.clearWatch(watchId);
            watchId = null;
        }
        clearInterval(sendTimer);
        clearTimeout(simulateTimer);
        pending = null;
        lastSentAt = 0;
        wakeLock?.release();
        wakeLock = null;
        wakeLockText.textContent = '-';
        statusText.textContent = 'Uit';
        render();

        try {
            await post('stop');
        } catch (error) {
            showError(error.message);
        }
    }

    toggleButton.addEventListener('click', () => (mode ? stop() : startGps()));
    simulateButton?.addEventListener('click', startSimulation);

    if (!window.isSecureContext) {
        showError('Geolocatie werkt alleen via HTTPS.');
    }
}
