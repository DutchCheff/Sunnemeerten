<?php
$user = currentUser();
$errors = [];

$alreadySignedUp = $user
    && dbOne('SELECT id FROM volunteers WHERE user_id = ?', [$user['id']]) !== null;

if (isPost() && !$alreadySignedUp) {
    verifyCsrf();

    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $motivation = trim($_POST['motivation'] ?? '');

    if (mb_strlen($name) < 2 || mb_strlen($name) > 100) {
        $errors[] = 'Je naam moet tussen de 2 en 100 tekens lang zijn.';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Voer een geldig e-mailadres in.';
    }
    if ($motivation === '' || mb_strlen($motivation) > 1000) {
        $errors[] = 'Vertel in maximaal 1000 tekens waarom je wilt helpen.';
    }

    if (!$errors) {
        dbInsert(
            'INSERT INTO volunteers (user_id, name, email, motivation, created_at) VALUES (?, ?, ?, ?, ?)',
            [$user['id'] ?? null, $name, $email, $motivation, now()]
        );

        flash('success', 'Bedankt voor je aanmelding!');
        redirect('?page=volunteer');
    }
}

$name = isPost() ? ($_POST['name'] ?? '') : ($user['username'] ?? '');
$email = isPost() ? ($_POST['email'] ?? '') : ($user['email'] ?? '');
?>
<?php require VIEW_PATH . '/header.view.php'; ?>

<div class="bg-card shadow-md mx-7 mt-28 mb-6 rounded-2xl text-text flex flex-col items-center px-6 py-4 max-w-md md:mx-auto">
    <h1 class="text-4xl font-semibold text-center mb-4 mt-10">Kom helpen!</h1>

    <?php if ($alreadySignedUp): ?>
        <p class="my-5">Je hebt je al aangemeld als vrijwilliger. Bedankt! We nemen contact met je op over de parade.</p>
    <?php else: ?>
        <p class="my-5">Ben jij geïnteresseerd om ons team te versterken tijdens de parade? Meld je dan nu aan als
            vrijwilliger!</p>

        <div class="w-full">
            <?php require VIEW_PATH . '/partials/errors.view.php'; ?>
        </div>

        <form method="POST" action="?page=volunteer" class="w-full">
            <?= csrfField() ?>
            <div class="mb-4">
                <label class="block" for="name">Naam</label>
                <input type="text" name="name" id="name" required maxlength="100" autocomplete="name" value="<?= e($name) ?>"
                    class="w-full mt-1 p-1 border-black rounded-lg bg-input text-text focus:ring-2">
            </div>

            <div class="mb-4">
                <label class="block" for="email">Email</label>
                <input type="email" name="email" id="email" required autocomplete="email" value="<?= e($email) ?>"
                    class="w-full mt-1 p-1 border-black rounded-lg bg-input text-text focus:ring-2">
            </div>

            <div class="mb-4">
                <label class="block" for="motivation">Waarom wil je helpen?</label>
                <textarea name="motivation" id="motivation" rows="4" required maxlength="1000" autocomplete="off"
                    class="w-full mt-1 p-1 border-black rounded-lg bg-input text-text focus:ring-2"><?= old('motivation') ?></textarea>
            </div>

            <p class="text-sm mb-4">We gebruiken je gegevens alleen om contact met je op te nemen over de parade. Na de parade
                verwijderen we ze.</p>

            <div class="w-full flex justify-center">
                <button type="submit"
                    class="bg-accent2 shadow-md text-text px-4 py-1 leading-[1.3] rounded-lg">Aanmelden</button>
            </div>
        </form>
    <?php endif; ?>
</div>

<?php require VIEW_PATH . '/footer.view.php'; ?>
