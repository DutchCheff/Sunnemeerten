<?php
$announcements = dbAll('SELECT title, body, created_at FROM announcements WHERE is_active = 1 ORDER BY created_at DESC, id DESC');
?>
<?php require VIEW_PATH . '/header.view.php'; ?>

<div class="mt-28 mb-16 max-w-2xl md:mx-auto">
    <h1 class="text-4xl text-text text-center mb-6">Nieuws</h1>

    <div class="flex flex-col gap-6">
        <?php foreach ($announcements as $i => $announcement): ?>
            <article class="bg-<?= cardColor($i) ?> shadow-md mx-7 rounded-2xl text-text px-6 py-4 md:mx-0">
                <h2 class="text-2xl"><?= e($announcement['title']) ?></h2>
                <p class="text-sm opacity-80 mb-2"><?= e(formatDate($announcement['created_at'])) ?></p>
                <p class="josefin-sans"><?= nl2br(e($announcement['body'])) ?></p>
            </article>
        <?php endforeach; ?>

        <?php if (!$announcements): ?>
            <div class="bg-card shadow-md mx-7 rounded-2xl text-text px-6 py-4 text-center md:mx-0">
                <p>Er is nog geen nieuws. Kom later nog eens terug!</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require VIEW_PATH . '/footer.view.php'; ?>
