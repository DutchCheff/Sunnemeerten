<?php require VIEW_PATH . '/header.view.php'; ?>

<div class="mt-28 mb-16 max-w-2xl md:mx-auto">
    <h1 class="text-4xl text-text text-center mb-6">Route</h1>

    <div class="bg-card shadow-md mx-7 rounded-2xl text-text px-6 py-4 md:mx-0">
        <!-- isolate houdt de lagen van Leaflet onder de vaste navigatiebalk -->
        <div id="kaart" class="h-[60vh] rounded-lg isolate"></div>

        <div class="flex flex-col gap-2 mt-4 josefin-sans">
            <p class="flex items-center gap-3">
                <span class="w-8 border-t-4 border-dashed border-red-600"></span>
                De route van de optocht
            </p>
            <p>De optocht begint bij de <span class="josefin-sans-bold">Start</span>-markering en eindigt bij de <span class="josefin-sans-bold">Einde</span>-markering.</p>
        </div>
    </div>
</div>

<script src="/js/kaart.js" defer></script>
<?php require VIEW_PATH . '/footer.view.php'; ?>
