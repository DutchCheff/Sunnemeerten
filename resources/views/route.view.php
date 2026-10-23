<?php require VIEW_PATH . '/header.view.php'; ?>

<div class="mt-28 mb-16 max-w-2xl md:mx-auto">
    <h1 class="text-4xl text-text text-center mb-6">Route</h1>

    <div class="bg-card shadow-md mx-7 rounded-2xl text-text px-6 py-4 md:mx-0">
        <p id="parade-status" class="flex items-center gap-2 mb-3 josefin-sans" aria-live="polite">
            <span data-dot class="w-3 h-3 rounded-full bg-background shrink-0"></span>
            <span data-text>De parade is nog niet onderweg</span>
        </p>

        <!-- isolate houdt de lagen van Leaflet onder de vaste navigatiebalk -->
        <div id="kaart" <?php require VIEW_PATH . '/partials/map-data.view.php'; ?> class="h-[60vh] rounded-lg isolate"></div>

        <div class="flex flex-wrap items-center gap-3 mt-4">
            <button type="button" id="locate-button" class="bg-accent1 text-text px-4 py-2 rounded-lg">Waar ben ik?</button>
            <p id="locate-message" class="josefin-sans text-sm" aria-live="polite"></p>
        </div>

        <div class="flex flex-col gap-2 mt-4 josefin-sans">
            <p class="flex items-center gap-3">
                <span class="w-8 border-t-4 border-dashed border-red-600"></span>
                De route van de optocht
            </p>
            <p class="flex items-center gap-3">
                <span class="w-8 flex justify-center"><span class="w-4 h-4 rounded-full bg-accent2 border-2 border-text"></span></span>
                De parade nu, met het afgelegde stuk
            </p>
            <p class="flex items-center gap-3">
                <span class="w-8 flex justify-center"><span class="w-4 h-4 rounded-full bg-blue-600 border-2 border-text"></span></span>
                Jouw locatie (blijft op je telefoon)
            </p>
            <p>De optocht begint bij de <span class="josefin-sans-bold">Start</span>-markering en eindigt bij de <span class="josefin-sans-bold">Einde</span>-markering.</p>
        </div>
    </div>
</div>

<script src="/js/kaart.js" defer></script>
<script src="/js/live.js" defer></script>
<?php require VIEW_PATH . '/footer.view.php'; ?>
