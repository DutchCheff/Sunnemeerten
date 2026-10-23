<?php
$uploadConfig = config('upload');
$errors = [];

if (isPost()) {
    // Bij een te groot verzoek gooit PHP $_POST en $_FILES leeg, dus ook de csrf-token
    if (empty($_POST) && (int) ($_SERVER['CONTENT_LENGTH'] ?? 0) > 0) {
        flash('error', "De foto's zijn samen te groot. Probeer minder foto's tegelijk.");
        redirect('?page=upload');
    }
    verifyCsrf();

    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $files = uploadedFiles('images');

    if ($title === '' || mb_strlen($title) > 100) {
        $errors[] = 'Geef je post een titel van maximaal 100 tekens.';
    }
    if (mb_strlen($content) > 2000) {
        $errors[] = 'De tekst mag maximaal 2000 tekens lang zijn.';
    }
    if (!$files) {
        $errors[] = 'Kies minimaal één foto.';
    } elseif (count($files) > $uploadConfig['max_files']) {
        $errors[] = "Je kunt maximaal {$uploadConfig['max_files']} foto's tegelijk plaatsen.";
    }
    foreach ($files as $file) {
        if ($error = validateImage($file)) {
            $errors[] = $error;
        }
    }

    if (!$errors) {
        $status = isAdmin() ? 'approved' : 'pending';
        $postId = dbInsert(
            'INSERT INTO posts (user_id, title, content, status, created_at) VALUES (?, ?, ?, ?, ?)',
            [currentUser()['id'], $title, $content, $status, now()]
        );
        foreach ($files as $order => $file) {
            dbRun('INSERT INTO post_images (post_id, path, sort_order) VALUES (?, ?, ?)', [$postId, storeImage($file['tmp_name']), $order]);
        }

        if ($status === 'approved') {
            flash('success', 'Je post staat online!');
            redirect('?page=feed#post-' . $postId);
        }
        flash('success', 'Bedankt! Je foto is zichtbaar zodra een beheerder hem heeft goedgekeurd.');
        redirect('?page=account');
    }
}
?>
<?php require VIEW_PATH . '/header.view.php'; ?>

<div class="bg-card shadow-md mx-7 mt-28 rounded-2xl text-text px-3 py-4 mb-5 max-w-2xl md:mx-auto">
    <h2 class="text-4xl font-semibold text-center mb-4">Foto plaatsen</h2>
    <p class="josefin-sans mb-4">Laat zien waar jullie aan werken! Je foto komt in de feed nadat een beheerder hem heeft goedgekeurd.</p>

    <?php require VIEW_PATH . '/partials/errors.view.php'; ?>

    <form method="POST" action="?page=upload" enctype="multipart/form-data">
        <?= csrfField() ?>
        <div class="mb-4">
            <label class="block" for="title">Titel</label>
            <input type="text" name="title" id="title" maxlength="100" required value="<?= old('title') ?>"
                class="w-full mt-1 p-2 border-black rounded-lg bg-input text-text focus:ring-2">
        </div>

        <div class="mb-4">
            <label class="block" for="imageUpload">Foto's (maximaal <?= $uploadConfig['max_files'] ?>, elk tot <?= $uploadConfig['max_mb'] ?> MB)</label>
            <input type="file" name="images[]" multiple required accept="image/jpeg,image/png,image/gif,image/webp" id="imageUpload"
                class="w-full mt-1 file:bg-accent1 file:text-text file:border-0 file:rounded-lg file:px-4 file:py-1 file:me-3">
            <div id="preview" class="mt-4 flex flex-wrap gap-4"></div>
        </div>

        <div class="mb-6">
            <label class="block" for="text">Tekst</label>
            <textarea name="content" id="text" rows="4" maxlength="2000"
                class="w-full mt-1 p-2 border-black rounded-lg bg-input text-text focus:ring-2"><?= old('content') ?></textarea>
        </div>

        <div class="flex justify-between">
            <a href="?page=feed" class="bg-accent2 text-text px-4 py-2 rounded-lg">Terug</a>
            <button type="submit" class="bg-accent1 text-text px-4 py-2 rounded-lg">Plaatsen</button>
        </div>
    </form>
</div>
<script src="/js/uploadpreview.js" defer></script>
<?php require VIEW_PATH . '/footer.view.php'; ?>
