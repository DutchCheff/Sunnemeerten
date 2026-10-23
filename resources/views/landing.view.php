<?php
$announcements = dbAll('SELECT title, body, created_at FROM announcements WHERE is_active = 1 ORDER BY created_at DESC, id DESC LIMIT 3');

$photoPosts = getPosts('approved', 4, 0, null, true) ?: getPosts('approved', 4);
$photos = [];
foreach ($photoPosts as $post) {
    if ($post['images']) {
        $photos[] = ['postId' => $post['id'], 'path' => $post['images'][0], 'alt' => $post['title'] ?: "Foto van {$post['username']}"];
    }
}
?>
<?php require VIEW_PATH . '/header.view.php'; ?>

<div class="max-w-2xl md:mx-auto mb-16">
    <div class="mt-16">
        <img src="/images/logo1.png" alt="logo sunnemeerten" class="mx-auto max-w-md w-full">
    </div>

    <?php if ($announcements): ?>
        <section class="flex flex-col gap-4 mt-8 mx-7">
            <?php foreach ($announcements as $i => $announcement): ?>
                <article class="bg-<?= cardColor($i) ?> shadow-md rounded-2xl text-text px-6 py-4">
                    <h2 class="text-2xl"><?= e($announcement['title']) ?></h2>
                    <p class="text-sm opacity-80 mb-2"><?= e(formatDate($announcement['created_at'])) ?></p>
                    <p class="josefin-sans"><?= nl2br(e($announcement['body'])) ?></p>
                </article>
            <?php endforeach; ?>
            <a href="?page=news" class="self-end text-accent-light underline">Al het nieuws</a>
        </section>
    <?php endif; ?>

    <div class="flex flex-col gap-8 items-center mt-16 mx-7">
        <a href="?page=route" class="bg-card shadow-md px-8 py-1 leading-[1.3] text-4xl text-text rounded-lg">Route</a>
        <div class="bg-card shadow-md rounded-lg p-1 w-full">
            <div class="p-2 relative">
                <!-- isolate houdt de lagen van Leaflet onder de vaste navigatiebalk -->
                <div id="kaart" data-static class="h-56 rounded-lg isolate"></div>
                <a href="?page=route" class="absolute inset-2 z-10" aria-label="Bekijk de route"></a>
            </div>
        </div>
    </div>

    <div class="flex flex-col gap-8 items-center mt-24 mx-7">
        <a href="?page=feed" class="bg-accent1 shadow-md px-8 py-1 leading-[1.3] text-4xl text-text rounded-lg">Foto's</a>
        <div class="bg-card shadow-md rounded-lg flex flex-col gap-2 p-4 w-full">
            <?php foreach ($photos as $photo): ?>
                <a href="?page=feed#post-<?= e($photo['postId']) ?>">
                    <img src="/<?= e($photo['path']) ?>" alt="<?= e($photo['alt']) ?>" class="rounded-lg w-full" loading="lazy">
                </a>
            <?php endforeach; ?>

            <?php if (!$photos): ?>
                <?php foreach (['11.jpg', '12.jpg', '13.jpg', '14.jpg'] as $image): ?>
                    <img src="/images/<?= $image ?>" alt="Foto van de optocht" class="rounded-lg w-full" loading="lazy">
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <?php if (!isLoggedIn()): ?>
            <p class="text-accent-light text-center josefin-sans">
                <a href="?page=login" class="underline">Log in</a> of <a href="?page=register" class="underline">registreer</a> om foto's te plaatsen
            </p>
        <?php endif; ?>
    </div>

    <div class="flex flex-col gap-3 items-center mt-16 mx-7">
        <a href="?page=news" class="bg-accent1 w-full text-center text-text text-4xl rounded-lg py-1 leading-[1.3]">Nieuws</a>
        <a href="?page=volunteer" class="bg-accent2 w-full text-center text-text text-4xl rounded-lg py-1 leading-[1.3]">Vrijwilliger</a>
        <a href="?page=donation" class="bg-card w-full text-center text-text text-4xl rounded-lg py-1 leading-[1.3]">Doneer</a>
    </div>
</div>

<script src="/js/kaart.js" defer></script>
<?php require VIEW_PATH . '/footer.view.php'; ?>
