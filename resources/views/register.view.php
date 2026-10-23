<?php
if (isLoggedIn()) {
    redirect('?page=landing');
}

$errors = [];

if (isPost()) {
    verifyCsrf();

    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    $errors = array_merge(
        validateAccount($username, $email),
        validatePassword($password, $confirmPassword)
    );

    if (!$errors) {
        $userId = dbInsert(
            'INSERT INTO users (username, email, password, is_admin, created_at) VALUES (?, ?, ?, 0, ?)',
            [$username, $email, password_hash($password, PASSWORD_DEFAULT), now()]
        );

        loginUser($userId);
        flash('success', 'Welkom bij Sunnemeerten, ' . $username . '!');
        redirect('?page=landing');
    }
}
?>
<?php require VIEW_PATH . '/header.view.php'; ?>

<div class="bg-card shadow-md mx-7 mt-28 mb-4 rounded-2xl text-text px-6 py-4 max-w-md md:mx-auto">
    <h1 class="text-4xl font-semibold text-center mb-4">Registreren</h1>

    <?php require VIEW_PATH . '/partials/errors.view.php'; ?>

    <form method="POST" action="?page=register">
        <?= csrfField() ?>
        <div class="mb-4">
            <label class="block" for="username">Naam</label>
            <input type="text" name="username" id="username" required maxlength="50" autocomplete="name" value="<?= old('username') ?>"
                   class="w-full mt-1 p-1 border-black rounded-lg bg-input text-text focus:ring-2">
        </div>

        <div class="mb-4">
            <label class="block" for="email">Email</label>
            <input type="email" name="email" id="email" required autocomplete="email" value="<?= old('email') ?>"
                   class="w-full mt-1 p-1 border-black rounded-lg bg-input text-text focus:ring-2">
        </div>

        <div class="mb-4">
            <label class="block" for="password">Wachtwoord</label>
            <input type="password" name="password" id="password" required minlength="8" autocomplete="new-password"
                   class="w-full mt-1 p-1 border-black rounded-lg bg-input text-text focus:ring-2">
        </div>

        <div class="mb-6">
            <label class="block" for="confirm_password">Herhaal wachtwoord</label>
            <input type="password" name="confirm_password" id="confirm_password" required minlength="8" autocomplete="new-password"
                   class="w-full mt-1 p-1 border-black rounded-lg bg-input text-text focus:ring-2">
        </div>

        <div class="flex justify-between">
            <a href="?page=login" class="bg-accent2 text-text px-4 py-2 rounded-lg">Inloggen</a>
            <button type="submit" class="bg-accent1 text-text px-4 py-2 rounded-lg">Registreren</button>
        </div>
    </form>
</div>

<?php require VIEW_PATH . '/footer.view.php'; ?>
