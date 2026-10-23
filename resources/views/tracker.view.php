<?php
if (isPost()) {
    $action = $_POST['action'] ?? '';

    if ($action === 'clear') {
        verifyCsrf();
        dbRun('DELETE FROM parade_locations');
        flash('success', 'Alle oude punten zijn gewist.');
        redirect('?page=tracker');
    }

    // De andere acties komen van tracker.js en krijgen JSON terug in plaats van een redirect
    if (!hash_equals(csrfToken(), $_POST['csrf'] ?? '')) {
        json(['error' => 'Je sessie is verlopen. Herlaad de pagina.'], 419);
    }

    if ($action === 'start') {
        // Bij een herstart (bijvoorbeeld na herladen) loopt de rit door met hetzelfde spoor
        if (!getSetting('tracking_active', false)) {
            setSetting('tracking_started_at', now());
        }
        setSetting('tracking_active', true);
        json(['active' => true]);
    }

    if ($action === 'stop') {
        setSetting('tracking_active', false);
        json(['active' => false]);
    }

    if ($action === 'position') {
        $latitude = filter_var($_POST['latitude'] ?? '', FILTER_VALIDATE_FLOAT);
        $longitude = filter_var($_POST['longitude'] ?? '', FILTER_VALIDATE_FLOAT);
        $accuracy = filter_var($_POST['accuracy'] ?? '', FILTER_VALIDATE_INT, ['options' => ['min_range' => 0]]);

        if ($latitude === false || $latitude < -90 || $latitude > 90
            || $longitude === false || $longitude < -180 || $longitude > 180
            || $accuracy === false) {
            json(['error' => 'Ongeldige locatie ontvangen.'], 422);
        }

        if (!getSetting('tracking_active', false)) {
            json(['error' => 'Het volgen staat uit. Druk eerst op Start.'], 409);
        }

        dbInsert(
            'INSERT INTO parade_locations (user_id, latitude, longitude, accuracy, created_at) VALUES (?, ?, ?, ?, ?)',
            [currentUser()['id'], $latitude, $longitude, $accuracy, now()]
        );
        json(['ok' => true, 'created_at' => now()]);
    }

    json(['error' => 'Onbekende actie.'], 400);
}

$isActive = (bool) getSetting('tracking_active', false);
$pointCount = (int) dbOne('SELECT COUNT(*) AS total FROM parade_locations')['total'];
?>
<?php require VIEW_PATH . '/header.view.php'; ?>

<?php require VIEW_PATH . '/partials/admin-nav.view.php'; ?>

<div id="tracker" data-csrf="<?= e(csrfToken()) ?>" data-active="<?= $isActive ? '1' : '0' ?>"
     class="bg-card shadow-md mx-7 mt-6 rounded-2xl text-text px-6 py-4 max-w-2xl md:mx-auto">
    <h2 class="text-2xl font-semibold mb-2">Live GPS</h2>
    <p class="josefin-sans mb-4">
        Gebruik deze pagina op de telefoon die meerijdt in de parade. Druk op Start en laat de pagina open staan:
        bezoekers zien de parade dan live op de routekaart.
    </p>

    <div data-error class="hidden bg-red-100 border border-red-400 text-red-700 px-4 py-3 mb-4 rounded" role="alert"></div>

    <?php if ($isActive): ?>
        <p data-resume class="bg-accent-light text-input josefin-sans px-4 py-3 mb-4 rounded-lg">
            Het volgen staat nog aan, maar deze telefoon verstuurt nu geen locatie. Druk op Start om verder te gaan.
        </p>
    <?php endif; ?>

    <button type="button" data-toggle class="bg-accent1 text-text text-4xl w-full py-6 rounded-2xl shadow-md">Start</button>

    <dl class="grid grid-cols-2 gap-x-4 gap-y-2 mt-4 josefin-sans">
        <dt>Status</dt>
        <dd data-status class="josefin-sans-bold">Uit</dd>
        <dt>Laatst verstuurd</dt>
        <dd data-last-sent>-</dd>
        <dt>Nauwkeurigheid</dt>
        <dd data-accuracy>-</dd>
        <dt>Verstuurde punten</dt>
        <dd data-sent-count>0</dd>
        <dt>Scherm aan houden</dt>
        <dd data-wake-lock>-</dd>
    </dl>

    <?php if (config('debug')): ?>
        <div class="border-t border-background mt-4 pt-4">
            <p class="josefin-sans text-sm mb-2">Alleen zichtbaar in debug-modus: loopt de route af met een nep-locatie elke 2 seconden.</p>
            <button type="button" data-simulate data-route="<?= e(json_encode(getSetting('route', []))) ?>"
                    class="bg-background text-text px-4 py-2 rounded-lg w-full">Simuleer parade</button>
        </div>
    <?php endif; ?>
</div>

<div class="bg-card shadow-md mx-7 mt-6 mb-10 rounded-2xl text-text px-6 py-4 max-w-2xl md:mx-auto">
    <h2 class="text-2xl font-semibold mb-2">Opgeslagen punten</h2>
    <p class="josefin-sans mb-4">Er staan <?= $pointCount ?> punten in de database. Wis ze na de parade of na het testen.</p>
    <form method="POST" action="?page=tracker" data-confirm="Weet je zeker dat je alle opgeslagen punten wilt wissen?">
        <?= csrfField() ?>
        <button type="submit" name="action" value="clear" class="bg-accent2 text-text px-4 py-2 rounded-lg">Oude punten wissen</button>
    </form>
</div>

<script src="/js/tracker.js" defer></script>
<?php require VIEW_PATH . '/footer.view.php'; ?>
