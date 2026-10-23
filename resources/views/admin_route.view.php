<?php
$errors = [];

if (isPost()) {
    verifyCsrf();

    $points = json_decode($_POST['route'] ?? '', true);
    $route = [];

    if (is_array($points) && array_is_list($points) && count($points) >= 2 && count($points) <= 500) {
        foreach ($points as $point) {
            $isPair = is_array($point) && array_is_list($point) && count($point) === 2;
            [$latitude, $longitude] = $isPair ? $point : [null, null];

            if (!(is_int($latitude) || is_float($latitude)) || !(is_int($longitude) || is_float($longitude))
                || $latitude < -90 || $latitude > 90 || $longitude < -180 || $longitude > 180) {
                $route = [];
                break;
            }
            $route[] = [(float) $latitude, (float) $longitude];
        }
    }

    if ($route) {
        setSetting('route', $route);
        flash('success', 'De route is opgeslagen met ' . count($route) . ' punten.');
        redirect('?page=admin_route');
    }

    $errors[] = 'De route moet uit 2 tot 500 geldige punten bestaan.';
}

$currentRoute = getSetting('route', []);
?>
<?php require VIEW_PATH . '/header.view.php'; ?>

<?php require VIEW_PATH . '/partials/admin-nav.view.php'; ?>

<div class="bg-card shadow-md mx-7 mt-6 mb-10 rounded-2xl text-text px-6 py-4 max-w-2xl md:mx-auto">
    <h2 class="text-2xl font-semibold mb-2">Route bewerken</h2>
    <p class="josefin-sans mb-4">
        Tik op de kaart om een punt aan het einde van de route toe te voegen. Sleep een punt om het te verplaatsen
        en tik op een punt om het te selecteren. Tik daarna ergens op de kaart om de selectie op te heffen.
    </p>

    <?php require VIEW_PATH . '/partials/errors.view.php'; ?>

    <!-- isolate houdt de lagen van Leaflet onder de vaste navigatiebalk -->
    <div id="route-editor" <?php require VIEW_PATH . '/partials/map-data.view.php'; ?> class="h-[60vh] rounded-lg isolate"></div>

    <div class="flex flex-wrap items-center justify-between gap-2 mt-3 josefin-sans">
        <p><span data-count><?= count($currentRoute) ?></span> punten</p>
        <div data-selection class="hidden flex items-center justify-between gap-2 w-full sm:w-auto">
            <span data-selection-label></span>
            <button type="button" data-delete class="bg-accent2 text-text px-4 py-2 rounded-lg whitespace-nowrap">Verwijder punt</button>
        </div>
    </div>

    <form method="POST" action="?page=admin_route" data-route-form class="flex flex-wrap gap-2 mt-4">
        <?= csrfField() ?>
        <input type="hidden" name="route" value="<?= e(json_encode($currentRoute)) ?>">
        <button type="button" data-undo class="bg-background text-text px-4 py-2 rounded-lg flex-1">Ongedaan maken</button>
        <button type="button" data-clear class="bg-accent2 text-text px-4 py-2 rounded-lg flex-1">Alles wissen</button>
        <button type="submit" data-save class="bg-accent1 text-text px-4 py-2 rounded-lg w-full">Opslaan</button>
    </form>
</div>

<script src="/js/kaart.js" defer></script>
<script src="/js/route-editor.js" defer></script>
<?php require VIEW_PATH . '/footer.view.php'; ?>
