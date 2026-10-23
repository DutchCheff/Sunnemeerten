<?php
if (isLoggedIn()) {
    redirect('?page=landing');
}

$errors = [];

if (isPost()) {
    verifyCsrf();

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        $errors[] = 'Vul alle velden in.';
    } else {
        $user = dbOne('SELECT id, username, password FROM users WHERE email = ?', [$email]);

        if ($user && password_verify($password, $user['password'])) {
            loginUser($user['id']);
            flash('success', 'Welkom terug, ' . $user['username'] . '!');
            redirect('?page=landing');
        }

        $errors[] = 'E-mailadres of wachtwoord klopt niet.';
    }
}
?>
<?php require VIEW_PATH . '/header.view.php'; ?>

<div class="bg-inherit">
    <div class="mt-16 mb-10">
        <img src="/images/logo1.png" alt="Logo" class="scale-90 mx-auto max-w-md w-full">
    </div>
    <div class="bg-card shadow-md mx-7 mb-4 rounded-2xl text-text px-6 py-4 max-w-md md:mx-auto">
        <div>
            <h1 class="text-4xl text-center my-4">Login</h1>
        </div>

        <?php require VIEW_PATH . '/partials/errors.view.php'; ?>

        <form method="POST" action="?page=login">
            <?= csrfField() ?>
            <div class="mb-4 mt-3">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" required autocomplete="email" value="<?= old('email') ?>"
                       class="w-full mt-1 p-1 border-black rounded-lg bg-input text-text focus:ring-2 focus:ring-input">
            </div>

            <div class="mb-4">
                <label class="block" for="password">Wachtwoord</label>
                <input type="password" name="password" id="password" required autocomplete="current-password"
                       class="w-full mt-1 p-1 border-black rounded-lg bg-input text-text focus:ring-2 focus:ring-input">
            </div>

            <div>
                <button type="submit"
                        class="bg-accent1 text-text px-4 py-1 rounded-lg w-full mb-4 mt-4 text-base">
                    Inloggen
                </button>
            </div>

            <div class="flex gap-2 w-full">
                <div class="w-1/2">
                    <a href="?page=landing_ua&guest=1" class="bg-accent2 text-text px-4 py-1 rounded-lg w-full mb-4 mt-2 text-base text-center block">
                        Gast
                    </a>
                </div>
                <div class="w-1/2">
                    <a href="?page=register" class="bg-background text-text px-4 py-1 rounded-lg w-full mb-4 mt-2 text-base text-center block">
                        Registreren
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<?php require VIEW_PATH . '/footer.view.php'; ?>
