<?php

// Start de lokale ontwikkelserver op http://localhost:6969
// Gebruik: php scripts/dev.php [--fresh] [--seed] [--lan]
//   --fresh  maak de database opnieuw aan met nepdata
//   --seed   alleen de database vullen, geen server starten
//   --lan    ook bereikbaar voor andere apparaten op hetzelfde netwerk, zoals je telefoon

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

// Het IP-adres van de netwerkverbinding die naar internet gaat (er wordt niets verstuurd)
function lanAddress(): ?string
{
    $socket = @stream_socket_client('udp://8.8.8.8:53');
    if (!$socket) {
        return null;
    }
    $address = explode(':', stream_socket_get_name($socket, false))[0];
    fclose($socket);

    return $address;
}

$host = 'localhost';
if (in_array('--lan', $argv)) {
    $host = '0.0.0.0';
    $lanUrl = 'http://' . (lanAddress() ?? 'JOUW-IP') . ':' . PORT;
    // Links in mails wijzen dan ook naar dit adres, zodat ze op je telefoon werken
    putenv("APP_URL=$lanUrl");
    echo "Op je telefoon (zelfde wifi): $lanUrl\n";
}

echo "Sunnemeerten draait op http://localhost:" . PORT . " (stoppen met Ctrl+C)\n";
passthru("$php $flags -S $host:" . PORT . ' -t ' . escapeshellarg("$root/public"));
