<?php
$errors = [];
$editing = isset($_GET['edit'])
    ? dbOne('SELECT * FROM announcements WHERE id = ?', [(int) $_GET['edit']])
    : null;

if (isset($_GET['edit']) && !$editing) {
    flash('error', 'Dit bericht bestaat niet meer.');
    redirect('?page=admin_news');
}

if (isPost()) {
    verifyCsrf();

    $action = $_POST['action'] ?? '';
    $id = (int) ($_POST['id'] ?? 0);

    if ($action === 'save') {
        $title = trim($_POST['title'] ?? '');
        $body = trim($_POST['body'] ?? '');

        if (mb_strlen($title) < 1 || mb_strlen($title) > 100) {
            $errors[] = 'De titel moet tussen de 1 en 100 tekens lang zijn.';
        }
        if (mb_strlen($body) < 1 || mb_strlen($body) > 2000) {
            $errors[] = 'Het bericht moet tussen de 1 en 2000 tekens lang zijn.';
        }

        if (!$errors) {
            if ($editing) {
                dbRun('UPDATE announcements SET title = ?, body = ?, updated_at = ? WHERE id = ?', [$title, $body, now(), $editing['id']]);
                flash('success', 'Het bericht is bijgewerkt.');
            } else {
                dbInsert(
                    'INSERT INTO announcements (user_id, title, body, is_active, created_at, updated_at) VALUES (?, ?, ?, 1, ?, ?)',
                    [currentUser()['id'], $title, $body, now(), now()]
                );
                flash('success', 'Het bericht staat op het nieuwsbord.');
            }
            redirect('?page=admin_news');
        }
    } elseif ($action === 'toggle') {
        dbRun('UPDATE announcements SET is_active = 1 - is_active WHERE id = ?', [$id]);
        flash('success', 'De zichtbaarheid van het bericht is aangepast.');
        redirect('?page=admin_news');
    } elseif ($action === 'delete') {
        dbRun('DELETE FROM announcements WHERE id = ?', [$id]);
        flash('success', 'Het bericht is verwijderd.');
        redirect('?page=admin_news');
    } else {
        flash('error', 'Onbekende actie.');
        redirect('?page=admin_news');
    }
}

$announcements = dbAll(
    'SELECT a.*, u.username FROM announcements a
    LEFT JOIN users u ON u.id = a.user_id
    ORDER BY a.created_at DESC, a.id DESC'
);
$titleValue = $_POST['title'] ?? $editing['title'] ?? '';
$bodyValue = $_POST['body'] ?? $editing['body'] ?? '';
$formAction = $editing ? '?page=admin_news&edit=' . $editing['id'] : '?page=admin_news';
?>
<?php require VIEW_PATH . '/header.view.php'; ?>

<?php require VIEW_PATH . '/partials/admin-nav.view.php'; ?>

<div class="bg-card shadow-md mx-7 mt-6 rounded-2xl text-text px-6 py-4 max-w-2xl md:mx-auto" id="form">
    <h2 class="text-2xl font-semibold mb-4"><?= $editing ? 'Bericht bewerken' : 'Nieuw bericht' ?></h2>

    <?php require VIEW_PATH . '/partials/errors.view.php'; ?>

    <form method="POST" action="<?= $formAction ?>">
        <?= csrfField() ?>
        <input type="hidden" name="action" value="save">
        <div class="mb-4">
            <label for="title">Titel</label>
            <input type="text" name="title" id="title" required maxlength="100" value="<?= e($titleValue) ?>"
                   class="w-full mt-1 p-1 border-black rounded-lg bg-input text-text focus:ring-2 focus:ring-input">
        </div>

        <div class="mb-4">
            <label for="body">Bericht</label>
            <textarea name="body" id="body" rows="5" required maxlength="2000"
                      class="w-full mt-1 p-1 border-black rounded-lg bg-input text-text focus:ring-2 focus:ring-input"><?= e($bodyValue) ?></textarea>
        </div>

        <div class="flex gap-2">
            <button type="submit" class="bg-accent1 text-text px-4 py-2 rounded-lg flex-1">
                <?= $editing ? 'Opslaan' : 'Plaatsen' ?>
            </button>
            <?php if ($editing): ?>
                <a href="?page=admin_news" class="bg-background text-text px-4 py-2 rounded-lg flex-1 text-center">Annuleren</a>
            <?php endif; ?>
        </div>
    </form>
</div>

<?php if (!$announcements): ?>
    <div class="bg-card shadow-md mx-7 mt-6 rounded-2xl text-text px-6 py-4 max-w-2xl md:mx-auto">
        <p class="text-center">Er zijn nog geen berichten.</p>
    </div>
<?php endif; ?>

<?php foreach ($announcements as $i => $announcement): ?>
    <?php
    $color = cardColor($i + 1);
    $primary = $color === 'card' ? 'accent1' : 'card';
    $danger = $color === 'accent2' ? 'background' : 'accent2';
    ?>
    <div class="bg-<?= $color ?> shadow-md mx-7 mt-6 rounded-2xl text-text px-6 py-4 max-w-2xl md:mx-auto <?= $announcement['is_active'] ? '' : 'opacity-70' ?>">
        <div class="flex justify-between items-start gap-4">
            <h2 class="text-2xl font-semibold break-words"><?= e($announcement['title']) ?></h2>
            <span class="<?= $announcement['is_active'] ? 'bg-accent-light text-input' : 'bg-background' ?> text-xs rounded-full px-2 py-1 shrink-0">
                <?= $announcement['is_active'] ? 'Zichtbaar' : 'Verborgen' ?>
            </span>
        </div>
        <p class="text-sm">
            <?= formatDate($announcement['created_at']) ?>
            <?php if ($announcement['username']): ?>&middot; <?= e($announcement['username']) ?><?php endif; ?>
            <?php if ($announcement['updated_at'] !== $announcement['created_at']): ?>
                &middot; bewerkt op <?= formatDate($announcement['updated_at']) ?>
            <?php endif; ?>
        </p>
        <p class="mt-3 break-words"><?= nl2br(e($announcement['body'])) ?></p>

        <div class="flex flex-wrap gap-2 mt-4">
            <a href="?page=admin_news&edit=<?= $announcement['id'] ?>#form" class="bg-<?= $primary ?> text-text px-4 py-2 rounded-lg">Bewerken</a>

            <form method="POST" action="?page=admin_news">
                <?= csrfField() ?>
                <input type="hidden" name="id" value="<?= $announcement['id'] ?>">
                <button type="submit" name="action" value="toggle" class="bg-<?= $primary ?> text-text px-4 py-2 rounded-lg">
                    <?= $announcement['is_active'] ? 'Verbergen' : 'Tonen' ?>
                </button>
            </form>

            <form method="POST" action="?page=admin_news" data-confirm="Weet je zeker dat je dit bericht wilt verwijderen?">
                <?= csrfField() ?>
                <input type="hidden" name="id" value="<?= $announcement['id'] ?>">
                <button type="submit" name="action" value="delete" class="bg-<?= $danger ?> text-text px-4 py-2 rounded-lg">
                    Verwijderen
                </button>
            </form>
        </div>
    </div>
<?php endforeach; ?>

<div class="mb-10"></div>

<?php require VIEW_PATH . '/footer.view.php'; ?>
