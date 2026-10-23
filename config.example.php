<?php

// Kopieer dit bestand naar config.php en vul de waarden in voor de server.
// Zonder config.php gebruikt de app deze instellingen (lokale SQLite database).
return [
    // Laat foutmeldingen zien. Op de live server altijd false.
    'debug' => true,

    'db' => [
        // 'sqlite' voor lokaal ontwikkelen, 'mysql' voor de live server
        'driver' => 'sqlite',
        'sqlite_path' => __DIR__ . '/storage/database.sqlite',

        'host' => 'localhost',
        'port' => 3306,
        'name' => 'sunnemeerten',
        'user' => 'root',
        'password' => '',
    ],

    'upload' => [
        'max_mb' => 10,
        'max_files' => 6,
        'max_width' => 1600,
    ],

    // Link voor de doneerknop, bijvoorbeeld een betaalverzoek
    'donation_url' => '',

    // Het adres van de site, nodig voor links in e-mails
    'app_url' => getenv('APP_URL') ?: 'http://localhost:6969',

    'mail' => [
        // 'log' slaat mails op in storage/mails (te bekijken op ?page=mailbox), 'smtp' verstuurt ze echt
        'driver' => 'log',
        'from_email' => 'noreply@sunnemeerten.nl',
        'from_name' => 'Sunnemeerten',

        // Gegevens van de mailserver van je host (alleen nodig bij 'smtp')
        'host' => '',
        'port' => 587,
        'encryption' => 'tls', // 'tls' (poort 587), 'ssl' (poort 465) of '' (geen)
        'username' => '',
        'password' => '',
    ],

    'map' => [
        // Standaard OpenStreetMap. Voor Mapbox: https://api.mapbox.com/styles/v1/mapbox/streets-v12/tiles/256/{z}/{x}/{y}?access_token=JOUW_TOKEN
        'tile_url' => 'https://tile.openstreetmap.org/{z}/{x}/{y}.png',
        'attribution' => '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
    ],
];
