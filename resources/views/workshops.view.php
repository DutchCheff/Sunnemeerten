<?php
if (isPost()) {
    verifyCsrf();

    $action = $_POST['action'] ?? '';
    $user = currentUser();
    $workshop = dbOne('SELECT * FROM workshops WHERE id = ?', [(int) ($_POST['workshop_id'] ?? 0)]);
    $back = '?page=workshops' . ($workshop ? '#workshop-' . $workshop['id'] : '');

    if (!$user) {
        flash('error', 'Log in om je aan te melden voor een werkplaats.');
        redirect('?page=login');
    }
    if (!isVerified()) {
        flash('error', 'Bevestig eerst je e-mailadres om je aan te melden.');
        redirect($back);
    }
    if (!$workshop) {
        flash('error', 'Deze werkplaats bestaat niet meer.');
        redirect($back);
    }
    if ($workshop['starts_at'] <= now()) {
        flash('error', 'Deze werkplaats is al begonnen, aanmelden of afmelden kan niet meer.');
        redirect($back);
    }

    $mailData = [
        'name' => $user['username'],
        'title' => $workshop['title'],
        'when' => formatWorkshopTime($workshop),
        'location' => $workshop['location'],
        'description' => $workshop['description'],
    ];

    if ($action === 'signup') {
        if (dbOne('SELECT user_id FROM workshop_signups WHERE workshop_id = ? AND user_id = ?', [$workshop['id'], $user['id']])) {
            flash('error', 'Je bent al aangemeld voor deze werkplaats.');
            redirect($back);
        }

        try {
            // Tellen en inschrijven gebeurt in één query, zodat twee mensen niet tegelijk de laatste plek krijgen
            $added = dbRun(
                'INSERT INTO workshop_signups (workshop_id, user_id, created_at)
                SELECT w.id, ?, ? FROM workshops w
                CROSS JOIN (SELECT COUNT(*) AS taken FROM workshop_signups WHERE workshop_id = ?) s
                WHERE w.id = ? AND w.starts_at > ? AND s.taken < w.capacity',
                [$user['id'], now(), $workshop['id'], $workshop['id'], now()]
            );
        } catch (PDOException $exception) {
            // 23000: dubbele aanmelding, bijvoorbeeld door twee keer snel op de knop te drukken
            if ($exception->getCode() !== '23000') {
                throw $exception;
            }
            flash('error', 'Je bent al aangemeld voor deze werkplaats.');
            redirect($back);
        }

        if (!$added) {
            flash('error', 'Deze werkplaats zit helaas vol.');
            redirect($back);
        }

        $mailed = sendMail($user['email'], 'Aangemeld: ' . $workshop['title'], 'workshop_signup', $mailData);
        flash('success', 'Je bent aangemeld voor ' . $workshop['title'] . '.' . ($mailed ? ' Je krijgt een bevestiging per mail.' : ''));
        redirect($back);
    } elseif ($action === 'cancel') {
        if (!dbRun('DELETE FROM workshop_signups WHERE workshop_id = ? AND user_id = ?', [$workshop['id'], $user['id']])) {
            flash('error', 'Je was niet aangemeld voor deze werkplaats.');
            redirect($back);
        }

        sendMail($user['email'], 'Afgemeld: ' . $workshop['title'], 'workshop_cancel', $mailData);
        flash('success', 'Je bent afgemeld voor ' . $workshop['title'] . '.');
        redirect($back);
    }

    flash('error', 'Onbekende actie.');
    redirect($back);
}

$workshops = dbAll(
    'SELECT w.*, (SELECT COUNT(*) FROM workshop_signups s WHERE s.workshop_id = w.id) AS taken
    FROM workshops w
    WHERE w.ends_at > ?
    ORDER BY w.starts_at, w.id',
    [now()]
);
$mySignups = isLoggedIn()
    ? array_column(dbAll('SELECT workshop_id FROM workshop_signups WHERE user_id = ?', [currentUser()['id']]), 'workshop_id')
    : [];
?>
<?php require VIEW_PATH . '/header.view.php'; ?>

<div class="mt-28 mb-16 max-w-2xl md:mx-auto">
    <h1 class="text-4xl text-text text-center mb-6">Werkplaatsen</h1>

    <div class="flex flex-col gap-6">
        <div class="bg-card shadow-md mx-7 rounded-2xl text-text px-6 py-4 md:mx-0">
            <h2 class="text-2xl">Bouw mee aan de parade</h2>
            <p class="josefin-sans mt-2">
                In de werkplaatsen bouwen vrijwilligers samen de beelden voor de parade. Je hoeft geen ervaring te hebben:
                we laten je zien hoe het werkt. Kies hieronder een werkplaats en meld je aan, dan weten we op hoeveel handen we kunnen rekenen.
            </p>
        </div>

        <?php foreach ($workshops as $i => $workshop): ?>
            <?php
            $color = cardColor($i + 1);
            $primary = $color === 'card' ? 'accent1' : 'card';
            $danger = $color === 'accent2' ? 'background' : 'accent2';
            $placesLeft = max(0, $workshop['capacity'] - $workshop['taken']);
            $isSignedUp = in_array($workshop['id'], $mySignups);
            ?>
            <article id="workshop-<?= $workshop['id'] ?>" class="bg-<?= $color ?> shadow-md mx-7 rounded-2xl text-text px-6 py-4 md:mx-0 scroll-mt-28">
                <h2 class="text-2xl break-words"><?= e($workshop['title']) ?></h2>
                <p class="text-sm opacity-80"><?= e(formatWorkshopTime($workshop)) ?></p>
                <p class="text-sm opacity-80 mb-2 break-words"><?= e($workshop['location']) ?></p>
                <p class="josefin-sans break-words"><?= nl2br(e($workshop['description'])) ?></p>

                <p class="font-semibold mt-3">
                    <?= $placesLeft > 0 ? 'Nog ' . $placesLeft . ' van de ' . $workshop['capacity'] . ' plekken vrij' : 'Vol' ?>
                </p>

                <div class="mt-3">
                    <?php if (!isLoggedIn()): ?>
                        <a href="?page=login" class="inline-block bg-<?= $primary ?> text-text px-4 py-2 rounded-lg">Log in om je aan te melden</a>
                    <?php elseif ($isSignedUp): ?>
                        <div class="flex flex-wrap items-center gap-3">
                            <p class="font-semibold">Je bent aangemeld ✓</p>
                            <?php if ($workshop['starts_at'] > now()): ?>
                                <form method="POST" action="?page=workshops" data-confirm="Weet je zeker dat je je wilt afmelden voor <?= e($workshop['title']) ?>?">
                                    <?= csrfField() ?>
                                    <input type="hidden" name="workshop_id" value="<?= $workshop['id'] ?>">
                                    <button type="submit" name="action" value="cancel" class="bg-<?= $danger ?> text-text px-4 py-2 rounded-lg">Afmelden</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    <?php elseif ($workshop['starts_at'] <= now()): ?>
                        <p class="text-sm">Deze werkplaats is al begonnen.</p>
                    <?php elseif (!isVerified()): ?>
                        <p class="text-sm">
                            <a href="?page=account" class="underline">Bevestig eerst je e-mailadres</a> om je aan te melden.
                        </p>
                    <?php else: ?>
                        <form method="POST" action="?page=workshops">
                            <?= csrfField() ?>
                            <input type="hidden" name="workshop_id" value="<?= $workshop['id'] ?>">
                            <button type="submit" name="action" value="signup" <?= $placesLeft > 0 ? '' : 'disabled' ?>
                                    class="bg-<?= $primary ?> text-text px-4 py-2 rounded-lg <?= $placesLeft > 0 ? '' : 'opacity-50 cursor-not-allowed' ?>">
                                Aanmelden
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </article>
        <?php endforeach; ?>

        <?php if (!$workshops): ?>
            <div class="bg-accent1 shadow-md mx-7 rounded-2xl text-text px-6 py-4 text-center md:mx-0">
                <p>Er staan nog geen werkplaatsen gepland. Kom later nog eens terug!</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require VIEW_PATH . '/footer.view.php'; ?>
