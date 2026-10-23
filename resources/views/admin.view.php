<?php
$statuses = ['pending' => 'Te beoordelen', 'approved' => 'Goedgekeurd', 'rejected' => 'Afgekeurd'];
$status = array_key_exists($_GET['status'] ?? '', $statuses) ? $_GET['status'] : 'pending';
$currentPageNumber = max(1, (int) ($_GET['p'] ?? 1));
$returnUrl = '?page=admin&status=' . $status . '&p=' . $currentPageNumber;

if (isPost()) {
    verifyCsrf();

    $postId = (int) ($_POST['post_id'] ?? 0);
    $post = dbOne('SELECT id, status, is_featured FROM posts WHERE id = ?', [$postId]);

    if (!$post) {
        flash('error', 'Deze foto bestaat niet meer.');
        redirect($returnUrl);
    }

    switch ($_POST['action'] ?? '') {
        case 'approve':
            dbRun("UPDATE posts SET status = 'approved' WHERE id = ?", [$postId]);
            flash('success', 'De foto is goedgekeurd.');
            break;
        case 'reject':
            // Een afgekeurde foto hoort ook niet meer op de homepage
            dbRun("UPDATE posts SET status = 'rejected', is_featured = 0 WHERE id = ?", [$postId]);
            flash('success', 'De foto is afgekeurd.');
            break;
        case 'feature':
            if ($post['status'] !== 'approved') {
                flash('error', 'Alleen goedgekeurde foto\'s kunnen op de homepage.');
                break;
            }
            dbRun('UPDATE posts SET is_featured = ? WHERE id = ?', [$post['is_featured'] ? 0 : 1, $postId]);
            flash('success', $post['is_featured'] ? 'De foto staat niet meer op de homepage.' : 'De foto staat nu op de homepage.');
            break;
        case 'delete':
            deletePost($postId);
            flash('success', 'De foto is verwijderd.');
            break;
        default:
            flash('error', 'Onbekende actie.');
    }

    redirect($returnUrl);
}

$counts = [];
foreach (array_keys($statuses) as $key) {
    $counts[$key] = countPosts($key);
}
$totalPages = max(1, (int) ceil($counts[$status] / POSTS_PER_PAGE));
$currentPageNumber = min($currentPageNumber, $totalPages);
$posts = getPosts($status, POSTS_PER_PAGE, ($currentPageNumber - 1) * POSTS_PER_PAGE);
$formAction = '?page=admin&status=' . $status . '&p=' . $currentPageNumber;
?>
<?php require VIEW_PATH . '/header.view.php'; ?>

<?php require VIEW_PATH . '/partials/admin-nav.view.php'; ?>

<div class="mx-7 mt-6 max-w-2xl md:mx-auto grid grid-cols-3 gap-2">
    <?php foreach ($statuses as $key => $label): ?>
        <a href="?page=admin&status=<?= $key ?>"
           class="text-center px-2 py-2 rounded-lg text-text text-sm sm:text-base shadow-md <?= $status === $key ? 'bg-accent1' : 'bg-card' ?>"
            <?= $status === $key ? 'aria-current="page"' : '' ?>>
            <span class="block"><?= e($label) ?></span>
            <span class="block">(<?= $counts[$key] ?>)</span>
        </a>
    <?php endforeach; ?>
</div>

<?php if (!$posts): ?>
    <div class="bg-card shadow-md mx-7 mt-6 rounded-2xl text-text px-6 py-4 max-w-2xl md:mx-auto">
        <p class="text-center">Er zijn geen foto's in deze lijst.</p>
    </div>
<?php endif; ?>

<?php foreach ($posts as $i => $post): ?>
    <?php
    $color = cardColor($i);
    $primary = $color === 'card' ? 'accent1' : 'card';
    $danger = $color === 'accent2' ? 'background' : 'accent2';
    ?>
    <div class="bg-<?= $color ?> shadow-md mx-7 mt-6 rounded-2xl text-text px-6 py-4 max-w-2xl md:mx-auto">
        <h2 class="text-2xl font-semibold break-words"><?= e($post['title']) ?></h2>
        <p class="text-sm">door <?= e($post['username']) ?> &middot; <?= formatDate($post['created_at']) ?></p>
        <div class="flex flex-wrap gap-1 mt-2">
            <span class="bg-background text-xs rounded-full px-2 py-1"><?= e(statusLabel($post['status'])) ?></span>
            <?php if ($post['is_featured']): ?>
                <span class="bg-accent-light text-input text-xs rounded-full px-2 py-1">Op homepage</span>
            <?php endif; ?>
        </div>

        <?php if ($post['images']): ?>
            <div class="grid <?= count($post['images']) > 1 ? 'grid-cols-2' : 'grid-cols-1' ?> gap-2 mt-3">
                <?php foreach ($post['images'] as $path): ?>
                    <a href="/<?= e($path) ?>" target="_blank" rel="noopener">
                        <img src="/<?= e($path) ?>" alt="Foto van <?= e($post['username']) ?>" loading="lazy"
                             class="w-full h-auto rounded-lg object-cover">
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <p class="mt-3 break-words"><?= nl2br(e($post['content'])) ?></p>

        <div class="flex flex-wrap gap-2 mt-4">
            <?php if ($post['status'] !== 'approved'): ?>
                <form method="POST" action="<?= $formAction ?>">
                    <?= csrfField() ?>
                    <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                    <button type="submit" name="action" value="approve"
                            class="bg-<?= $primary ?> text-text px-4 py-2 rounded-lg flex items-center gap-2">
                        <img src="/icon/check.svg" alt="" class="h-4 w-4 object-contain">
                        Goedkeuren
                    </button>
                </form>
            <?php endif; ?>

            <?php if ($post['status'] !== 'rejected'): ?>
                <form method="POST" action="<?= $formAction ?>">
                    <?= csrfField() ?>
                    <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                    <button type="submit" name="action" value="reject"
                            class="bg-<?= $primary ?> text-text px-4 py-2 rounded-lg flex items-center gap-2">
                        <img src="/icon/cross.svg" alt="" class="h-4 w-4 object-contain">
                        Afkeuren
                    </button>
                </form>
            <?php endif; ?>

            <?php if ($post['status'] === 'approved'): ?>
                <form method="POST" action="<?= $formAction ?>">
                    <?= csrfField() ?>
                    <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                    <button type="submit" name="action" value="feature"
                            class="bg-<?= $primary ?> text-text px-4 py-2 rounded-lg">
                        <?= $post['is_featured'] ? 'Van homepage halen' : 'Op homepage' ?>
                    </button>
                </form>
            <?php endif; ?>

            <form method="POST" action="<?= $formAction ?>" data-confirm="Weet je zeker dat je deze foto wilt verwijderen?">
                <?= csrfField() ?>
                <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                <button type="submit" name="action" value="delete"
                        class="bg-<?= $danger ?> text-text px-4 py-2 rounded-lg">
                    Verwijderen
                </button>
            </form>
        </div>
    </div>
<?php endforeach; ?>

<?php if ($totalPages > 1): ?>
    <div class="mx-7 mt-6 mb-10 max-w-2xl md:mx-auto flex justify-between items-center text-text">
        <?php if ($currentPageNumber > 1): ?>
            <a href="?page=admin&status=<?= $status ?>&p=<?= $currentPageNumber - 1 ?>" class="bg-accent1 text-text px-4 py-2 rounded-lg">Vorige</a>
        <?php else: ?>
            <span></span>
        <?php endif; ?>
        <span>Pagina <?= $currentPageNumber ?> van <?= $totalPages ?></span>
        <?php if ($currentPageNumber < $totalPages): ?>
            <a href="?page=admin&status=<?= $status ?>&p=<?= $currentPageNumber + 1 ?>" class="bg-accent1 text-text px-4 py-2 rounded-lg">Volgende</a>
        <?php else: ?>
            <span></span>
        <?php endif; ?>
    </div>
<?php else: ?>
    <div class="mb-10"></div>
<?php endif; ?>

<?php require VIEW_PATH . '/footer.view.php'; ?>
