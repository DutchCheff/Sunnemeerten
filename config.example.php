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
];
