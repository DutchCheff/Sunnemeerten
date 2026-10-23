<?php
$myId = (int) currentUser()['id'];

if (isPost()) {
    verifyCsrf();

    $userId = (int) ($_POST['user_id'] ?? 0);
    $user = dbOne('SELECT id, username, is_admin FROM users WHERE id = ?', [$userId]);

    if (!$user) {
        flash('error', 'Deze gebruiker bestaat niet meer.');
        redirect('?page=admin_users');
    }

    // Een beheerder kan zichzelf niet buitensluiten of verwijderen
    if ((int) $user['id'] === $myId) {
        flash('error', 'Je kunt je eigen account hier niet aanpassen.');
        redirect('?page=admin_users');
    }

    switch ($_POST['action'] ?? '') {
        case 'toggle_admin':
            dbRun('UPDATE users SET is_admin = ? WHERE id = ?', [$user['is_admin'] ? 0 : 1, $userId]);
            flash('success', $user['is_admin']
                ? $user['username'] . ' is geen beheerder meer.'
                : $user['username'] . ' is nu beheerder.');
            break;
        case 'delete':
            deleteUser($userId);
            flash('success', 'Het account van ' . $user['username'] . ' is verwijderd.');
            break;
        default:
            flash('error', 'Onbekende actie.');
    }

    redirect('?page=admin_users');
}

$users = dbAll(
    'SELECT u.id, u.username, u.email, u.is_admin, u.created_at,
        (SELECT COUNT(*) FROM posts p WHERE p.user_id = u.id) AS post_count
    FROM users u
    ORDER BY u.created_at DESC, u.id DESC'
);
?>
<?php require VIEW_PATH . '/header.view.php'; ?>

<?php require VIEW_PATH . '/partials/admin-nav.view.php'; ?>

<div class="bg-card shadow-md mx-7 mt-6 rounded-2xl text-text px-6 py-4 max-w-2xl md:mx-auto">
    <h2 class="text-2xl font-semibold mb-2">Gebruikers (<?= count($users) ?>)</h2>

    <ul class="divide-y divide-background">
        <?php foreach ($users as $user): ?>
            <li class="py-4">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="text-xl break-all"><?= e($user['username']) ?></span>
                    <?php if ($user['is_admin']): ?>
                        <span class="bg-accent1 text-xs rounded-full px-2 py-1">Beheerder</span>
                    <?php endif; ?>
                    <?php if ((int) $user['id'] === $myId): ?>
                        <span class="bg-background text-xs rounded-full px-2 py-1">Jij</span>
                    <?php endif; ?>
                </div>
                <a href="mailto:<?= e($user['email']) ?>" class="underline break-all"><?= e($user['email']) ?></a>
                <p class="text-sm">
                    Lid sinds <?= formatDate($user['created_at'], false) ?>
                    &middot; <?= $user['post_count'] ?> <?= $user['post_count'] == 1 ? 'foto' : "foto's" ?>
                </p>

                <?php if ((int) $user['id'] !== $myId): ?>
                    <div class="flex flex-wrap gap-2 mt-3">
                        <form method="POST" action="?page=admin_users"
                              data-confirm="<?= e($user['is_admin'] ? 'Beheerrechten van ' . $user['username'] . ' intrekken?' : $user['username'] . ' beheerder maken?') ?>">
                            <?= csrfField() ?>
                            <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                            <button type="submit" name="action" value="toggle_admin" class="bg-accent1 text-text px-4 py-2 rounded-lg">
                                <?= $user['is_admin'] ? 'Geen beheerder meer' : 'Maak beheerder' ?>
                            </button>
                        </form>

                        <form method="POST" action="?page=admin_users"
                              data-confirm="<?= e('Weet je zeker dat je het account van ' . $user['username'] . ' wilt verwijderen? Al zijn of haar foto\'s worden ook verwijderd.') ?>">
                            <?= csrfField() ?>
                            <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                            <button type="submit" name="action" value="delete" class="bg-accent2 text-text px-4 py-2 rounded-lg">
                                Verwijderen
                            </button>
                        </form>
                    </div>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

<div class="mb-10"></div>

<?php require VIEW_PATH . '/footer.view.php'; ?>
