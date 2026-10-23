<?php
// Alleen voor lokaal ontwikkelen: laat de mails zien die de app anders had verstuurd
if (!config('debug') || config('mail')['driver'] !== 'log') {
    redirect('?page=landing');
}

if (isPost()) {
    verifyCsrf();
    foreach (glob(MAIL_PATH . '/*.json') as $file) {
        unlink($file);
    }
    flash('success', 'Mailbox geleegd.');
    redirect('?page=mailbox');
}

$files = glob(MAIL_PATH . '/*.json') ?: [];
rsort($files);

$mails = [];
foreach ($files as $file) {
    $mails[basename($file, '.json')] = json_decode(file_get_contents($file), true);
}

$selectedId = $_GET['mail'] ?? array_key_first($mails);
$selected = $mails[$selectedId] ?? null;
?>
<?php require VIEW_PATH . '/header.view.php'; ?>

<div class="bg-card shadow-md mx-7 mt-28 rounded-2xl text-text px-6 py-4 max-w-2xl md:mx-auto">
    <h1 class="text-4xl font-semibold text-center mb-2">Mailbox</h1>
    <p class="josefin-sans text-center mb-4">Lokaal worden mails niet echt verstuurd. Hier zie je wat de app zou versturen.</p>

    <?php if ($mails): ?>
        <div class="flex flex-col gap-2">
            <?php foreach ($mails as $id => $mail): ?>
                <a href="?page=mailbox&mail=<?= e($id) ?>"
                   class="block rounded-lg px-3 py-2 <?= $id === $selectedId ? 'bg-accent1' : 'bg-background' ?>">
                    <span class="block"><?= e($mail['subject']) ?></span>
                    <span class="block josefin-sans text-sm">aan <?= e($mail['to']) ?> · <?= formatDate($mail['created_at']) ?></span>
                </a>
            <?php endforeach; ?>
        </div>
        <form method="POST" action="?page=mailbox" class="mt-4 text-right" data-confirm="Alle mails verwijderen?">
            <?= csrfField() ?>
            <button type="submit" class="bg-accent2 text-text px-4 py-2 rounded-lg">Mailbox legen</button>
        </form>
    <?php else: ?>
        <p class="josefin-sans text-center">Er zijn nog geen mails. Registreer een account of meld je aan voor een werkplaats.</p>
    <?php endif; ?>
</div>

<?php if ($selected): ?>
    <div class="bg-card shadow-md mx-7 mt-6 mb-10 rounded-2xl text-text px-3 py-4 max-w-2xl md:mx-auto">
        <p class="px-3"><?= e($selected['subject']) ?></p>
        <p class="px-3 mb-3 josefin-sans text-sm">aan <?= e($selected['to']) ?></p>
        <iframe srcdoc="<?= e($selected['html']) ?>" sandbox="allow-same-origin allow-popups allow-popups-to-escape-sandbox allow-top-navigation-by-user-activation"
                title="Inhoud van de mail" class="w-full h-[70vh] rounded-lg bg-white"></iframe>
    </div>
<?php endif; ?>

<?php require VIEW_PATH . '/footer.view.php'; ?>
