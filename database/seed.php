<?php

// Maakt de lokale SQLite database opnieuw aan en vult hem met nepdata.
// Draai dit via: npm run seed

require_once __DIR__ . '/../libs/core.php';

$dbConfig = config('db');
if ($dbConfig['driver'] !== 'sqlite') {
    exit("De seed werkt alleen met de lokale SQLite database, nooit op de live database.\n");
}

if (file_exists($dbConfig['sqlite_path'])) {
    unlink($dbConfig['sqlite_path']);
}
if (!is_dir(dirname($dbConfig['sqlite_path']))) {
    mkdir(dirname($dbConfig['sqlite_path']), 0755, true);
}
foreach (glob(UPLOAD_PATH . '/*') as $file) {
    unlink($file);
}

db()->exec(file_get_contents(__DIR__ . '/sqlite.sql'));

function daysAgo(float $days): string
{
    return date('Y-m-d H:i:s', time() - (int) ($days * 86400));
}

$users = [
    ['Beheerder', 'admin@sunnemeerten.test', 'admin1234', 1],
    ['Anna de Vries', 'anna@sunnemeerten.test', 'wachtwoord', 0],
    ['Jeroen Bakker', 'jeroen@sunnemeerten.test', 'wachtwoord', 0],
    ['Fatima El Amrani', 'fatima@sunnemeerten.test', 'wachtwoord', 0],
    ['Sven Hoekstra', 'sven@sunnemeerten.test', 'wachtwoord', 0],
    ['Lotte Visser', 'lotte@sunnemeerten.test', 'wachtwoord', 0],
];

$userIds = [];
foreach ($users as $index => [$username, $email, $password, $isAdmin]) {
    $userIds[] = dbInsert(
        'INSERT INTO users (username, email, password, is_admin, created_at) VALUES (?, ?, ?, ?, ?)',
        [$username, $email, password_hash($password, PASSWORD_DEFAULT), $isAdmin, daysAgo(30 - $index)]
    );
}

// [auteur, titel, tekst, status, op homepage, dagen geleden, foto's uit public/images]
$posts = [
    [1, 'De eerste lasnaden van de draak', "Vandaag zijn we begonnen met het frame van de draak. Het staal is binnen en de eerste stukken zitten vast!", 'approved', 1, 13, [11, 12]],
    [2, 'Papier-maché laag 3', "Derde laag papier-maché erop. Nog twee te gaan voordat we kunnen schilderen.", 'approved', 1, 11.5, [13]],
    [3, 'Kleuren testen', "We hebben verschillende kleuren geprobeerd op een proefstuk. Welke vinden jullie het mooist?", 'approved', 1, 10, [14, 15, 16]],
    [0, 'Werkplaats open op zaterdag', "Iedereen is welkom om zaterdag mee te helpen in de werkplaats. Koffie staat klaar!", 'approved', 0, 9, [17]],
    [4, 'Ogen van de reus', "De ogen zijn klaar en ze lichten op in het donker. Eng en mooi tegelijk.", 'approved', 1, 7.2, [18, 19]],
    [5, 'Stoffen naaien voor de kostuums', "Met z'n vijven aan de naaimachines. Al 12 kostuums af!", 'approved', 0, 6, [20]],
    [1, 'Wielen onder de wagen', "De wagen kan eindelijk rijden. Eerste proefrit door de straat ging goed.", 'approved', 0, 4.5, [21, 22]],
    [2, 'Lampjes, lampjes, lampjes', "Meer dan 300 lampjes verwerkt in de vleugels. Ons geduld is op, maar het resultaat is prachtig.", 'approved', 0, 3, [23]],
    [3, 'Bijna klaar!', "Nog een paar details en dan is de draak klaar voor de parade.", 'approved', 0, 1.5, [24, 25]],
    [4, 'Nieuwe vleugel', "Vanavond de tweede vleugel bevestigd. Wie komt er morgen helpen?", 'pending', 0, 0.4, [26]],
    [5, 'Groepsfoto werkplaats', "Het hele team bij elkaar na een lange avond werken.", 'pending', 0, 0.2, [27, 28]],
    [2, 'Mislukte test', "Deze verf bleef niet zitten.", 'rejected', 0, 5, [29]],
];

$postIds = [];
foreach ($posts as [$author, $title, $content, $status, $featured, $days, $images]) {
    $postId = dbInsert(
        'INSERT INTO posts (user_id, title, content, status, is_featured, created_at) VALUES (?, ?, ?, ?, ?, ?)',
        [$userIds[$author], $title, $content, $status, $featured, daysAgo($days)]
    );
    foreach ($images as $order => $image) {
        $path = storeImage(ROOT_PATH . "/public/images/$image.jpg");
        dbRun('INSERT INTO post_images (post_id, path, sort_order) VALUES (?, ?, ?)', [$postId, $path, $order]);
    }
    if ($status === 'approved') {
        $postIds[] = [$postId, $days];
    }
}

$comments = [
    'Wat gaaf! 🔥',
    'Ik kom zaterdag zeker helpen.',
    'Hoe lang zijn jullie hier al mee bezig?',
    'Prachtig gedaan, team!',
    'Kan niet wachten op de parade.',
    'Die kleuren zijn echt mooi.',
    'Respect voor al dat werk.',
];

foreach ($postIds as $index => [$postId, $days]) {
    foreach ($userIds as $userIndex => $userId) {
        if (($index + $userIndex) % 3 !== 0) {
            dbRun('INSERT INTO likes (user_id, post_id, created_at) VALUES (?, ?, ?)', [$userId, $postId, daysAgo($days / 2)]);
        }
    }
    for ($i = 0; $i < $index % 4; $i++) {
        dbRun(
            'INSERT INTO comments (post_id, user_id, body, created_at) VALUES (?, ?, ?, ?)',
            [$postId, $userIds[($index + $i + 1) % count($userIds)], $comments[($index + $i) % count($comments)], daysAgo($days / 2 - $i * 0.05)]
        );
    }
}

$announcements = [
    ['De route is bekend!', "De parade start om 14:00 bij de Grote Markt en eindigt rond 16:30 bij het Zuiderdiep. Bekijk de route in de app.", 1, 2],
    ['Vrijwilligers gezocht voor de opbouw', "Op vrijdagavond bouwen we de tribunes op. Meld je aan via de vrijwilligerspagina!", 1, 4],
    ['Werkplaats elke zaterdag open', "Van 10:00 tot 16:00 kun je meehelpen met bouwen, schilderen en naaien.", 1, 8],
    ['Oud bericht', "Dit bericht staat uit en is niet zichtbaar op de homepage.", 0, 20],
];

foreach ($announcements as [$title, $body, $active, $days]) {
    dbRun(
        'INSERT INTO announcements (user_id, title, body, is_active, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?)',
        [$userIds[0], $title, $body, $active, daysAgo($days), daysAgo($days)]
    );
}

$volunteers = [
    [$userIds[2], 'Jeroen Bakker', 'jeroen@sunnemeerten.test', 'Ik heb een rijbewijs en kan helpen met vervoer.', 6],
    [null, 'Mira Jansen', 'mira@example.com', 'Ik wil graag helpen met de kostuums.', 3],
    [null, 'Daan Mulder', 'daan@example.com', 'Ik ben handig met hout en kan helpen bij de opbouw.', 1],
];

foreach ($volunteers as [$userId, $name, $email, $motivation, $days]) {
    dbRun(
        'INSERT INTO volunteers (user_id, name, email, motivation, created_at) VALUES (?, ?, ?, ?, ?)',
        [$userId, $name, $email, $motivation, daysAgo($days)]
    );
}

echo 'Database gevuld: ' . count($users) . ' gebruikers, ' . count($posts) . ' posts, ' . count($announcements) . " nieuwsberichten.\n";
echo 'Foto\'s verkleind: ' . (extension_loaded('gd') ? 'ja' : 'nee (GD extensie niet geladen)') . "\n";
