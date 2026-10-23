<?php
$donationUrl = config('donation_url');
?>
<?php require VIEW_PATH . '/header.view.php'; ?>

<div class="h-screen flex items-center">
    <div class="bg-background shadow-md mx-7 mt-28 rounded-2xl text-text px-3 py-16 flex flex-col max-w-md md:mx-auto">
        <div>
            <h1 class="text-4xl font-semibold text-center mb-4">Doneer nu!</h1>
            <p class="p-5 my-5 text-text">
                Word jij net zo enthousiast als wij over dit project? Draag dan nu je steentje bij!
            </p>
        </div>
        <div class="mb-2 flex flex-col items-center mt-auto">
            <?php if ($donationUrl): ?>
                <a class="bg-accent2 shadow-md text-text px-4 py-2 rounded-lg text-lg" href="<?= e($donationUrl) ?>"
                    target="_blank" rel="noopener">Doneer</a>
            <?php else: ?>
                <span class="bg-accent2 shadow-md text-text px-4 py-2 rounded-lg text-lg opacity-50 cursor-not-allowed"
                    aria-disabled="true">Doneer</span>
                <p class="text-sm mt-2">Doneren is binnenkort mogelijk.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require VIEW_PATH . '/footer.view.php'; ?>
