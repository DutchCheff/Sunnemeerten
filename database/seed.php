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
foreach ([...glob(UPLOAD_PATH . '/*'), ...glob(MAIL_PATH . '/*')] as $file) {
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
    // Heeft zijn e-mailadres nog niet bevestigd
    ['Nieuw Lid', 'nieuw@sunnemeerten.test', 'wachtwoord', 0],
];

$userIds = [];
foreach ($users as $index => [$username, $email, $password, $isAdmin]) {
    $userIds[] = dbInsert(
        'INSERT INTO users (username, email, password, is_admin, email_verified_at, created_at) VALUES (?, ?, ?, ?, ?, ?)',
        [$username, $email, password_hash($password, PASSWORD_DEFAULT), $isAdmin, $email === 'nieuw@sunnemeerten.test' ? null : daysAgo(29 - $index), daysAgo(30 - $index)]
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

function daysFromNow(float $days, string $time): string
{
    return date('Y-m-d', time() + (int) ($days * 86400)) . ' ' . $time . ':00';
}

// [titel, omschrijving, locatie, over hoeveel dagen, begin, eind, plekken]
$workshops = [
    ['Lassen voor beginners', 'Leer de basis van lassen en help mee aan het frame van de draak. Veiligheidskleding is aanwezig.', 'Werkplaats Noord, Industrieweg 12', 2, '10:00', '13:00', 8],
    ['Papier-maché en schilderen', 'Samen de huid van de reus maken en beschilderen. Kleren die vies mogen worden aanbevolen!', 'Werkplaats Zuid, Helperpark 3', 3, '13:00', '16:00', 15],
    ['Lampjes solderen', 'We verwerken honderden lampjes in de vleugels. Geschikt voor iedereen met een vaste hand.', 'Werkplaats Noord, Industrieweg 12', 5, '19:00', '21:30', 3],
    ['Kostuums naaien', 'Help de kostuums voor de lopers af te maken. Naaimachines zijn aanwezig.', 'Buurthuis De Wijert, Van Lenneplaan 2', 7, '10:00', '15:00', 10],
    ['Opbouw tribunes', 'De laatste klus voor de parade: tribunes en dranghekken opbouwen.', 'Grote Markt', -3, '18:00', '22:00', 20],
];

$workshopIds = [];
foreach ($workshops as [$title, $description, $location, $days, $start, $end, $capacity]) {
    $workshopIds[] = dbInsert(
        'INSERT INTO workshops (title, description, location, starts_at, ends_at, capacity, created_at) VALUES (?, ?, ?, ?, ?, ?, ?)',
        [$title, $description, $location, daysFromNow($days, $start), daysFromNow($days, $end), $capacity, daysAgo(10)]
    );
}

// De workshop "Lampjes solderen" zit vol, zodat je dat ook kunt testen
$signups = [[0, 1], [0, 2], [1, 3], [1, 4], [2, 2], [2, 3], [2, 4], [3, 5], [4, 1], [4, 2]];
foreach ($signups as [$workshop, $user]) {
    dbRun('INSERT INTO workshop_signups (workshop_id, user_id, created_at) VALUES (?, ?, ?)', [$workshopIds[$workshop], $userIds[$user], daysAgo(2)]);
}

setSetting('route', [
    [53.22144972390063, 6.562589424460544], [53.22095946990001, 6.560718781620076], [53.22058996579885, 6.5609710001823105],
    [53.22021100962282, 6.559780097838771], [53.219581974636526, 6.558159578233106], [53.219325223007644, 6.557504931240092],
    [53.219216103099335, 6.55746736952739], [53.21880850568918, 6.557875182408255], [53.21850688724483, 6.558401790655146],
    [53.21833999467226, 6.5587344801106084], [53.2180832355986, 6.5591315610736], [53.21774302745531, 6.559437420734238],
    [53.21803509310096, 6.560660859376953], [53.21818593941278, 6.56140672767224], [53.21826296711179, 6.561970153362983],
    [53.218067188105394, 6.562340404531147], [53.21799336955921, 6.5623833322028045], [53.218128168547594, 6.562995051524161],
    [53.218494049377725, 6.56265699610974], [53.218847089321265, 6.562340404531147], [53.219038377146624, 6.563149279038482],
    [53.21915391634384, 6.563610751508958], [53.219404250201904, 6.5644532070655535], [53.21890044323922, 6.564935403725794],
    [53.21858591727724, 6.565300288935002], [53.21823608466779, 6.565751029487563], [53.21791513480664, 6.566083718943029],
    [53.217895877738485, 6.566158842368469], [53.21790229676218, 6.566271527506609], [53.218069191040115, 6.566690072305428],
    [53.21818794234192, 6.567038859637765], [53.218300274351435, 6.5673554512163586], [53.21839013974695, 6.5676022853284675],
    [53.21868541043332, 6.567655944918078],
]);

echo 'Database gevuld: ' . count($users) . ' gebruikers, ' . count($posts) . ' posts, ' . count($announcements) . " nieuwsberichten.\n";
echo 'Foto\'s verkleind: ' . (extension_loaded('gd') ? 'ja' : 'nee (GD extensie niet geladen)') . "\n";
