<?php
if (isPost()) {
    verifyCsrf();

    if (($_POST['action'] ?? '') === 'delete') {
        $deleted = dbRun('DELETE FROM volunteers WHERE id = ?', [(int) ($_POST['volunteer_id'] ?? 0)]);
        flash($deleted ? 'success' : 'error', $deleted ? 'De aanmelding is verwijderd.' : 'Deze aanmelding bestaat niet meer.');
    } else {
        flash('error', 'Onbekende actie.');
    }

    redirect('?page=admin_volunteers');
}

$volunteers = dbAll('SELECT * FROM volunteers ORDER BY created_at DESC, id DESC');
$emails = implode(', ', array_unique(array_column($volunteers, 'email')));
?>
<?php require VIEW_PATH . '/header.view.php'; ?>

<?php require VIEW_PATH . '/partials/admin-nav.view.php'; ?>

<?php if ($volunteers): ?>
    <div class="bg-card shadow-md mx-7 mt-6 rounded-2xl text-text px-6 py-4 max-w-2xl md:mx-auto">
        <label for="emails" class="text-2xl font-semibold block mb-2">Kopieer e-mailadressen</label>
        <p class="text-sm mb-2">Plak deze lijst in het BCC-veld van je e-mail om alle vrijwilligers tegelijk te mailen.</p>
        <textarea id="emails" rows="3" readonly onclick="this.select()"
                  class="w-full mt-1 p-1 border-black rounded-lg bg-input text-text focus:ring-2 focus:ring-input"><?= e($emails) ?></textarea>
    </div>
<?php endif; ?>

<div class="bg-card shadow-md mx-7 mt-6 rounded-2xl text-text px-6 py-4 max-w-2xl md:mx-auto">
    <h2 class="text-2xl font-semibold mb-2">Aanmeldingen (<?= count($volunteers) ?>)</h2>

    <?php if (!$volunteers): ?>
        <p>Er hebben zich nog geen vrijwilligers aangemeld.</p>
    <?php endif; ?>

    <ul class="divide-y divide-background">
        <?php foreach ($volunteers as $volunteer): ?>
            <li class="py-4">
                <div class="flex justify-between items-start gap-4">
                    <div class="min-w-0">
                        <p class="text-xl break-words"><?= e($volunteer['name']) ?></p>
                        <a href="mailto:<?= e($volunteer['email']) ?>" class="underline break-all"><?= e($volunteer['email']) ?></a>
                        <p class="text-sm"><?= formatDate($volunteer['created_at']) ?></p>
                    </div>
                    <form method="POST" action="?page=admin_volunteers" class="shrink-0"
                          data-confirm="<?= e('Weet je zeker dat je de aanmelding van ' . $volunteer['name'] . ' wilt verwijderen?') ?>">
                        <?= csrfField() ?>
                        <input type="hidden" name="volunteer_id" value="<?= $volunteer['id'] ?>">
                        <button type="submit" name="action" value="delete" class="bg-accent2 text-text px-4 py-2 rounded-lg">
                            Verwijderen
                        </button>
                    </form>
                </div>
                <p class="mt-2 break-words"><?= nl2br(e($volunteer['motivation'])) ?></p>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

<div class="mb-10"></div>

<?php require VIEW_PATH . '/footer.view.php'; ?>
