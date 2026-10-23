<?php
$user = currentUser();
$userId = (int) $user['id'];
$formErrors = ['account' => [], 'password' => [], 'delete' => []];
$action = isPost() ? ($_POST['action'] ?? '') : '';

if (isPost()) {
    verifyCsrf();

    if ($action === 'account') {
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $formErrors['account'] = validateAccount($username, $email, $userId);

        if (!$formErrors['account']) {
            dbRun('UPDATE users SET username = ?, email = ? WHERE id = ?', [$username, $email, $userId]);
            flash('success', 'Je gegevens zijn opgeslagen.');
            redirect('?page=account');
        }
    }

    if ($action === 'password') {
        $hash = dbOne('SELECT password FROM users WHERE id = ?', [$userId])['password'];

        if (!password_verify($_POST['current_password'] ?? '', $hash)) {
            $formErrors['password'][] = 'Je huidige wachtwoord klopt niet.';
        } else {
            $formErrors['password'] = validatePassword($_POST['password'] ?? '', $_POST['confirm_password'] ?? '');
        }

        if (!$formErrors['password']) {
            dbRun('UPDATE users SET password = ? WHERE id = ?', [password_hash($_POST['password'], PASSWORD_DEFAULT), $userId]);
            flash('success', 'Je wachtwoord is gewijzigd.');
            redirect('?page=account');
        }
    }

    if ($action === 'delete_post') {
        $post = dbOne('SELECT id FROM posts WHERE id = ? AND user_id = ?', [(int) ($_POST['post_id'] ?? 0), $userId]);

        if ($post) {
            deletePost($post['id']);
            flash('success', 'Je foto is verwijderd.');
        } else {
            flash('error', 'Deze foto kun je niet verwijderen.');
        }
        redirect('?page=account');
    }

    if ($action === 'delete_account') {
        $hash = dbOne('SELECT password FROM users WHERE id = ?', [$userId])['password'];

        if (password_verify($_POST['delete_password'] ?? '', $hash)) {
            deleteUser($userId);
            logoutUser();
            flash('success', 'Je account is verwijderd.');
            redirect('?page=landing_ua');
        }

        $formErrors['delete'][] = 'Je wachtwoord klopt niet.';
    }
}

// Na een fout blijven de ingevulde waarden staan, anders de huidige gegevens
$username = $action === 'account' ? ($_POST['username'] ?? '') : $user['username'];
$email = $action === 'account' ? ($_POST['email'] ?? '') : $user['email'];
$posts = getPosts(null, 50, 0, $userId);
?>
<?php require VIEW_PATH . '/header.view.php'; ?>

<div class="bg-card shadow-md mx-7 mt-28 rounded-2xl text-text px-6 py-4 max-w-md md:mx-auto">
    <h1 class="text-4xl font-semibold text-center my-4">Account Wijzigen</h1>

    <?php $errors = $formErrors['account']; require VIEW_PATH . '/partials/errors.view.php'; ?>

    <form method="POST" action="?page=account">
        <?= csrfField() ?>
        <input type="hidden" name="action" value="account">
        <div class="mb-4">
            <label class="block" for="username">Naam</label>
            <input type="text" name="username" id="username" required maxlength="50" autocomplete="name" value="<?= e($username) ?>"
                class="w-full mt-1 p-1 border-black rounded-lg bg-input text-text focus:ring-2">
        </div>

        <div class="mb-4">
            <label class="block" for="email">Email</label>
            <input type="email" name="email" id="email" required autocomplete="email" value="<?= e($email) ?>"
                class="w-full mt-1 p-1 border-black rounded-lg bg-input text-text focus:ring-2">
        </div>

        <div class="flex justify-end">
            <button type="submit" class="bg-accent1 text-text px-4 py-2 rounded-lg">Opslaan</button>
        </div>
    </form>
</div>

<div class="bg-card shadow-md mx-7 mt-6 rounded-2xl text-text px-6 py-4 max-w-md md:mx-auto">
    <h2 class="text-2xl font-semibold text-center mb-4">Wachtwoord wijzigen</h2>

    <?php $errors = $formErrors['password']; require VIEW_PATH . '/partials/errors.view.php'; ?>

    <form method="POST" action="?page=account">
        <?= csrfField() ?>
        <input type="hidden" name="action" value="password">
        <div class="mb-4">
            <label class="block" for="current_password">Huidig wachtwoord</label>
            <input type="password" name="current_password" id="current_password" required autocomplete="current-password"
                class="w-full mt-1 p-1 border-black rounded-lg bg-input text-text focus:ring-2">
        </div>

        <div class="mb-4">
            <label class="block" for="password">Nieuw wachtwoord</label>
            <input type="password" name="password" id="password" required minlength="8" autocomplete="new-password"
                class="w-full mt-1 p-1 border-black rounded-lg bg-input text-text focus:ring-2">
        </div>

        <div class="mb-4">
            <label class="block" for="confirm_password">Herhaal wachtwoord</label>
            <input type="password" name="confirm_password" id="confirm_password" required minlength="8" autocomplete="new-password"
                class="w-full mt-1 p-1 border-black rounded-lg bg-input text-text focus:ring-2">
        </div>

        <div class="flex justify-end">
            <button type="submit" class="bg-accent1 text-text px-4 py-2 rounded-lg">Wijzigen</button>
        </div>
    </form>
</div>

<div class="bg-card shadow-md mx-7 mt-6 rounded-2xl text-text px-6 py-4 max-w-md md:mx-auto">
    <h2 class="text-2xl font-semibold text-center mb-4">Mijn foto's</h2>

    <?php if (!$posts): ?>
        <p class="mb-4 text-center">Je hebt nog geen foto's geplaatst.</p>
    <?php endif; ?>

    <ul class="flex flex-col gap-3 mb-4">
        <?php foreach ($posts as $post): ?>
            <li class="flex items-center gap-3">
                <?php if ($post['images']): ?>
                    <img src="/<?= e($post['images'][0]) ?>" alt="" class="w-16 h-16 object-cover rounded-lg shrink-0">
                <?php else: ?>
                    <div class="w-16 h-16 rounded-lg bg-input shrink-0"></div>
                <?php endif; ?>
                <div class="flex-1 min-w-0">
                    <p class="truncate"><?= e($post['title']) ?></p>
                    <p class="text-sm"><?= e(statusLabel($post['status'])) ?></p>
                    <p class="text-[12px] text-gray-200"><?= e(formatDate($post['created_at'], false)) ?></p>
                </div>
                <form method="POST" action="?page=account" data-confirm="Weet je zeker dat je deze foto wilt verwijderen?">
                    <?= csrfField() ?>
                    <input type="hidden" name="action" value="delete_post">
                    <input type="hidden" name="post_id" value="<?= (int) $post['id'] ?>">
                    <button type="submit" class="bg-accent2 text-text px-3 py-1 rounded-lg text-sm">Verwijderen</button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>

    <div class="flex justify-center">
        <a href="?page=upload" class="bg-accent1 text-text px-4 py-2 rounded-lg">Foto plaatsen</a>
    </div>
</div>

<div class="bg-card shadow-md mx-7 mt-6 mb-6 rounded-2xl text-text px-6 py-4 max-w-md md:mx-auto">
    <h2 class="text-2xl font-semibold text-center mb-4">Account verwijderen</h2>
    <p class="mb-4">Hiermee verwijder je je account en al je foto's. Dit kan niet ongedaan worden gemaakt.</p>

    <?php $errors = $formErrors['delete']; require VIEW_PATH . '/partials/errors.view.php'; ?>

    <form method="POST" action="?page=account" id="delete-account"
        data-confirm="Weet je zeker dat je je account wilt verwijderen?">
        <?= csrfField() ?>
        <input type="hidden" name="action" value="delete_account">
        <div class="mb-4">
            <label class="block" for="delete_password">Wachtwoord</label>
            <input type="password" name="delete_password" id="delete_password" required autocomplete="current-password"
                class="w-full mt-1 p-1 border-black rounded-lg bg-input text-text focus:ring-2">
        </div>
    </form>

    <form method="POST" action="?page=logout" id="logout"><?= csrfField() ?></form>

    <!-- De knoppen horen via het form-attribuut bij de twee formulieren hierboven -->
    <div class="flex justify-between">
        <button type="submit" form="delete-account" class="bg-accent2 text-text px-4 py-2 rounded-lg">Verwijderen</button>
        <button type="submit" form="logout" class="bg-accent1 text-text px-4 py-2 rounded-lg">Afmelden</button>
    </div>
</div>

<?php require VIEW_PATH . '/footer.view.php'; ?>
