<?php
function countSignups(int $workshopId): int
{
    return (int) dbOne('SELECT COUNT(*) AS total FROM workshop_signups WHERE workshop_id = ?', [$workshopId])['total'];
}

function isValidDateTime(string $format, string $value): bool
{
    $date = DateTime::createFromFormat('!' . $format, $value);
    return $date && $date->format($format) === $value;
}

$errors = [];
$editing = isset($_GET['edit'])
    ? dbOne('SELECT * FROM workshops WHERE id = ?', [(int) $_GET['edit']])
    : null;

if (isset($_GET['edit']) && !$editing) {
    flash('error', 'Deze werkplaats bestaat niet meer.');
    redirect('?page=admin_workshops');
}

if (isPost()) {
    verifyCsrf();

    $action = $_POST['action'] ?? '';
    $id = (int) ($_POST['id'] ?? 0);

    if ($action === 'save') {
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $location = trim($_POST['location'] ?? '');
        $date = $_POST['date'] ?? '';
        $startTime = $_POST['start_time'] ?? '';
        $endTime = $_POST['end_time'] ?? '';
        $capacity = filter_var($_POST['capacity'] ?? '', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1, 'max_range' => 500]]);

        if (mb_strlen($title) < 1 || mb_strlen($title) > 100) {
            $errors[] = 'De titel moet tussen de 1 en 100 tekens lang zijn.';
        }
        if (mb_strlen($description) < 1 || mb_strlen($description) > 2000) {
            $errors[] = 'De omschrijving moet tussen de 1 en 2000 tekens lang zijn.';
        }
        if (mb_strlen($location) < 1 || mb_strlen($location) > 255) {
            $errors[] = 'De locatie moet tussen de 1 en 255 tekens lang zijn.';
        }
        if (!isValidDateTime('Y-m-d', $date)) {
            $errors[] = 'Kies een geldige datum.';
        }
        if (!isValidDateTime('H:i', $startTime) || !isValidDateTime('H:i', $endTime)) {
            $errors[] = 'Kies een geldige begin- en eindtijd.';
        } elseif ($endTime <= $startTime) {
            $errors[] = 'De eindtijd moet na de begintijd liggen.';
        }
        if ($capacity === false) {
            $errors[] = 'Het aantal plekken moet tussen de 1 en 500 liggen.';
        } elseif ($editing && $capacity < ($taken = countSignups($editing['id']))) {
            $errors[] = 'Er zijn al ' . $taken . ' aanmeldingen, dus er moeten minstens zoveel plekken zijn.';
        }

        if (!$errors) {
            $values = [$title, $description, $location, "$date $startTime:00", "$date $endTime:00", $capacity];

            if ($editing) {
                dbRun(
                    'UPDATE workshops SET title = ?, description = ?, location = ?, starts_at = ?, ends_at = ?, capacity = ? WHERE id = ?',
                    [...$values, $editing['id']]
                );
                flash('success', 'De werkplaats is bijgewerkt.');
                redirect('?page=admin_workshops#workshop-' . $editing['id']);
            }

            $newId = dbInsert(
                'INSERT INTO workshops (title, description, location, starts_at, ends_at, capacity, created_at) VALUES (?, ?, ?, ?, ?, ?, ?)',
                [...$values, now()]
            );
            flash('success', 'De werkplaats staat online.');
            redirect('?page=admin_workshops#workshop-' . $newId);
        }
    } elseif ($action === 'delete') {
        $workshop = dbOne('SELECT * FROM workshops WHERE id = ?', [$id]);
        if (!$workshop) {
            flash('error', 'Deze werkplaats bestaat niet meer.');
            redirect('?page=admin_workshops');
        }

        $participants = dbAll(
            'SELECT u.username, u.email FROM workshop_signups s JOIN users u ON u.id = s.user_id WHERE s.workshop_id = ?',
            [$id]
        );
        dbRun('DELETE FROM workshop_signups WHERE workshop_id = ?', [$id]);
        dbRun('DELETE FROM workshops WHERE id = ?', [$id]);

        $message = 'De werkplaats is verwijderd.';
        // Alleen deelnemers van een werkplaats die nog moet komen hebben iets aan een afzegging
        if ($workshop['ends_at'] > now() && $participants) {
            $notified = 0;
            foreach ($participants as $participant) {
                $notified += (int) sendMail($participant['email'], 'Gaat niet door: ' . $workshop['title'], 'workshop_deleted', [
                    'name' => $participant['username'],
                    'title' => $workshop['title'],
                    'when' => formatWorkshopTime($workshop),
                    'location' => $workshop['location'],
                ]);
            }
            $message .= ' ' . $notified . ' van de ' . count($participants) . ' deelnemers hebben een mail gekregen.';
        }

        flash('success', $message);
        redirect('?page=admin_workshops');
    } elseif ($action === 'remove_participant') {
        $participant = dbOne('SELECT username FROM users WHERE id = ?', [(int) ($_POST['user_id'] ?? 0)]);
        $removed = $participant
            ? dbRun('DELETE FROM workshop_signups WHERE workshop_id = ? AND user_id = ?', [$id, (int) $_POST['user_id']])
            : 0;

        if ($removed) {
            flash('success', $participant['username'] . ' is afgemeld voor de werkplaats.');
        } else {
            flash('error', 'Deze deelnemer was niet (meer) aangemeld.');
        }
        redirect('?page=admin_workshops&open=' . $id . '#workshop-' . $id);
    } else {
        flash('error', 'Onbekende actie.');
        redirect('?page=admin_workshops');
    }
}

$allWorkshops = dbAll(
    'SELECT w.*, (SELECT COUNT(*) FROM workshop_signups s WHERE s.workshop_id = w.id) AS taken
    FROM workshops w
    ORDER BY w.starts_at, w.id'
);
// Eerst de komende werkplaatsen (eerstvolgende bovenaan), daarna de afgelopen (meest recente bovenaan)
$upcoming = array_filter($allWorkshops, fn($workshop) => $workshop['ends_at'] > now());
$past = array_reverse(array_diff_key($allWorkshops, $upcoming));
$workshops = [...$upcoming, ...$past];

$participants = [];
foreach (dbAll(
    'SELECT s.workshop_id, u.id, u.username, u.email FROM workshop_signups s
    JOIN users u ON u.id = s.user_id
    ORDER BY s.created_at, u.username'
) as $row) {
    $participants[$row['workshop_id']][] = $row;
}

$openId = (int) ($_GET['open'] ?? 0);
$value = fn(string $field, ?string $fallback) => $_POST[$field] ?? $fallback ?? '';
$titleValue = $value('title', $editing['title'] ?? null);
$descriptionValue = $value('description', $editing['description'] ?? null);
$locationValue = $value('location', $editing['location'] ?? null);
$dateValue = $value('date', $editing ? substr($editing['starts_at'], 0, 10) : null);
$startValue = $value('start_time', $editing ? substr($editing['starts_at'], 11, 5) : null);
$endValue = $value('end_time', $editing ? substr($editing['ends_at'], 11, 5) : null);
$capacityValue = $value('capacity', $editing ? (string) $editing['capacity'] : null);
$formAction = $editing ? '?page=admin_workshops&edit=' . $editing['id'] : '?page=admin_workshops';
$inputClass = 'w-full mt-1 p-1 border-black rounded-lg bg-input text-text focus:ring-2 focus:ring-input';
?>
<?php require VIEW_PATH . '/header.view.php'; ?>

<?php require VIEW_PATH . '/partials/admin-nav.view.php'; ?>

<div class="bg-card shadow-md mx-7 mt-6 rounded-2xl text-text px-6 py-4 max-w-2xl md:mx-auto scroll-mt-28" id="form">
    <h2 class="text-2xl font-semibold mb-4"><?= $editing ? 'Werkplaats bewerken' : 'Nieuwe werkplaats' ?></h2>

    <?php require VIEW_PATH . '/partials/errors.view.php'; ?>

    <form method="POST" action="<?= $formAction ?>#form">
        <?= csrfField() ?>
        <input type="hidden" name="action" value="save">
        <div class="mb-4">
            <label for="title">Titel</label>
            <input type="text" name="title" id="title" required maxlength="100" value="<?= e($titleValue) ?>" class="<?= $inputClass ?>">
        </div>

        <div class="mb-4">
            <label for="description">Omschrijving</label>
            <textarea name="description" id="description" rows="4" required maxlength="2000" class="<?= $inputClass ?>"><?= e($descriptionValue) ?></textarea>
        </div>

        <div class="mb-4">
            <label for="location">Locatie</label>
            <input type="text" name="location" id="location" required maxlength="255" value="<?= e($locationValue) ?>" class="<?= $inputClass ?>">
        </div>

        <div class="mb-4">
            <label for="date">Datum</label>
            <input type="date" name="date" id="date" required value="<?= e($dateValue) ?>" class="<?= $inputClass ?>">
        </div>

        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label for="start_time">Begintijd</label>
                <input type="time" name="start_time" id="start_time" required value="<?= e($startValue) ?>" class="<?= $inputClass ?>">
            </div>
            <div>
                <label for="end_time">Eindtijd</label>
                <input type="time" name="end_time" id="end_time" required value="<?= e($endValue) ?>" class="<?= $inputClass ?>">
            </div>
        </div>

        <div class="mb-4">
            <label for="capacity">Aantal plekken</label>
            <input type="number" name="capacity" id="capacity" required min="1" max="500" value="<?= e($capacityValue) ?>" class="<?= $inputClass ?>">
        </div>

        <div class="flex gap-2">
            <button type="submit" class="bg-accent1 text-text px-4 py-2 rounded-lg flex-1">
                <?= $editing ? 'Opslaan' : 'Toevoegen' ?>
            </button>
            <?php if ($editing): ?>
                <a href="?page=admin_workshops" class="bg-background text-text px-4 py-2 rounded-lg flex-1 text-center">Annuleren</a>
            <?php endif; ?>
        </div>
    </form>
</div>

<?php if (!$workshops): ?>
    <div class="bg-card shadow-md mx-7 mt-6 rounded-2xl text-text px-6 py-4 max-w-2xl md:mx-auto">
        <p class="text-center">Er zijn nog geen werkplaatsen.</p>
    </div>
<?php endif; ?>

<?php foreach ($workshops as $i => $workshop): ?>
    <?php
    $color = cardColor($i + 1);
    $primary = $color === 'card' ? 'accent1' : 'card';
    $danger = $color === 'accent2' ? 'background' : 'accent2';
    $isPast = $workshop['ends_at'] <= now();
    $workshopParticipants = $participants[$workshop['id']] ?? [];
    $deleteQuestion = 'Weet je zeker dat je de werkplaats ' . $workshop['title'] . ' wilt verwijderen?'
        . (!$isPast && $workshopParticipants ? ' De ' . count($workshopParticipants) . ' deelnemers krijgen hierover een mail.' : '');
    ?>
    <div id="workshop-<?= $workshop['id'] ?>" class="bg-<?= $color ?> shadow-md mx-7 mt-6 rounded-2xl text-text px-6 py-4 max-w-2xl md:mx-auto scroll-mt-28 <?= $isPast ? 'opacity-70' : '' ?>">
        <div class="flex justify-between items-start gap-4">
            <h2 class="text-2xl font-semibold break-words"><?= e($workshop['title']) ?></h2>
            <span class="<?= !$isPast && $workshop['taken'] >= $workshop['capacity'] ? 'bg-accent-light text-input' : 'bg-background' ?> text-xs rounded-full px-2 py-1 shrink-0">
                <?= $isPast ? 'Afgelopen' : $workshop['taken'] . ' / ' . $workshop['capacity'] ?>
            </span>
        </div>
        <p class="text-sm"><?= e(formatWorkshopTime($workshop)) ?></p>
        <p class="text-sm break-words"><?= e($workshop['location']) ?></p>
        <p class="mt-3 break-words"><?= nl2br(e($workshop['description'])) ?></p>
        <p class="mt-3 font-semibold"><?= $workshop['taken'] ?> van de <?= $workshop['capacity'] ?> plekken bezet</p>

        <?php if ($workshopParticipants): ?>
            <details class="mt-3" <?= $openId === (int) $workshop['id'] ? 'open' : '' ?>>
                <summary class="cursor-pointer underline">Deelnemers (<?= count($workshopParticipants) ?>)</summary>
                <ul class="mt-2 flex flex-col gap-2">
                    <?php foreach ($workshopParticipants as $participant): ?>
                        <li class="flex justify-between items-center gap-2 bg-background rounded-lg px-3 py-2">
                            <span class="min-w-0 break-words">
                                <?= e($participant['username']) ?><br>
                                <a href="mailto:<?= e($participant['email']) ?>" class="text-sm underline break-all"><?= e($participant['email']) ?></a>
                            </span>
                            <form method="POST" action="?page=admin_workshops" class="shrink-0"
                                  data-confirm="Weet je zeker dat je <?= e($participant['username']) ?> wilt afmelden voor deze werkplaats?">
                                <?= csrfField() ?>
                                <input type="hidden" name="id" value="<?= $workshop['id'] ?>">
                                <input type="hidden" name="user_id" value="<?= $participant['id'] ?>">
                                <button type="submit" name="action" value="remove_participant" class="bg-accent2 text-text text-sm px-3 py-1 rounded-lg">
                                    Afmelden
                                </button>
                            </form>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <label for="emails-<?= $workshop['id'] ?>" class="block mt-3 text-sm">E-mailadressen om te kopiëren</label>
                <textarea id="emails-<?= $workshop['id'] ?>" readonly rows="2"
                          class="<?= $inputClass ?> text-sm"><?= e(implode(', ', array_column($workshopParticipants, 'email'))) ?></textarea>
            </details>
        <?php else: ?>
            <p class="mt-3 text-sm">Nog geen aanmeldingen.</p>
        <?php endif; ?>

        <div class="flex flex-wrap gap-2 mt-4">
            <a href="?page=admin_workshops&edit=<?= $workshop['id'] ?>#form" class="bg-<?= $primary ?> text-text px-4 py-2 rounded-lg">Bewerken</a>

            <form method="POST" action="?page=admin_workshops" data-confirm="<?= e($deleteQuestion) ?>">
                <?= csrfField() ?>
                <input type="hidden" name="id" value="<?= $workshop['id'] ?>">
                <button type="submit" name="action" value="delete" class="bg-<?= $danger ?> text-text px-4 py-2 rounded-lg">
                    Verwijderen
                </button>
            </form>
        </div>
    </div>
<?php endforeach; ?>

<div class="mb-10"></div>

<?php require VIEW_PATH . '/footer.view.php'; ?>
