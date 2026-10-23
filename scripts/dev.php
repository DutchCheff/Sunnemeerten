<?php

// Start de lokale ontwikkelserver op http://localhost:6969
// Gebruik: php scripts/dev.php [--fresh] [--seed]
//   --fresh  maak de database opnieuw aan met nepdata
//   --seed   alleen de database vullen, geen server starten

const PORT = 6969;

$root = dirname(__DIR__);
$php = escapeshellarg(PHP_BINARY);

$flags = [];
foreach (['gd', 'exif', 'fileinfo', 'mbstring', 'pdo_sqlite'] as $extension) {
    if (!extension_loaded($extension)) {
        $flags[] = '-d extension=' . $extension;
    }
}
$flags[] = '-d upload_max_filesize=20M';
$flags[] = '-d post_max_size=120M';
$flags[] = '-d max_file_uploads=20';
$flags = implode(' ', $flags);

$databaseMissing = !file_exists("$root/storage/database.sqlite");
if ($databaseMissing || in_array('--fresh', $argv) || in_array('--seed', $argv)) {
    passthru("$php $flags " . escapeshellarg("$root/database/seed.php"), $exitCode);
    if ($exitCode !== 0) {
        exit($exitCode);
    }
}

if (in_array('--seed', $argv)) {
    exit(0);
}

if (!file_exists("$root/public/css/style.css")) {
    echo "Let op: public/css/style.css bestaat nog niet. Draai eerst 'npm run build'.\n";
}

echo "Sunnemeerten draait op http://localhost:" . PORT . " (stoppen met Ctrl+C)\n";
passthru("$php $flags -S localhost:" . PORT . ' -t ' . escapeshellarg("$root/public"));
