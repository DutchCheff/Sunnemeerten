<?php
// Alleen punten van de huidige rit tellen mee, zodat een oude rit niet als live wordt getoond
$startedAt = getSetting('tracking_started_at', '1970-01-01 00:00:00');
$latest = getSetting('tracking_active', false)
    ? dbOne('SELECT latitude, longitude, accuracy, created_at FROM parade_locations WHERE created_at >= ? ORDER BY created_at DESC, id DESC LIMIT 1', [$startedAt])
    : null;
$secondsAgo = $latest ? max(0, time() - strtotime($latest['created_at'])) : null;

header('Cache-Control: no-store');

if (!$latest || $secondsAgo >= 15 * 60) {
    json([
        'active' => false,
        'latitude' => null,
        'longitude' => null,
        'accuracy' => null,
        'updated_at' => null,
        'seconds_ago' => null,
        'trail' => [],
    ]);
}

$trail = dbAll(
    'SELECT latitude, longitude FROM parade_locations WHERE created_at >= ? ORDER BY created_at DESC, id DESC LIMIT 30',
    [$startedAt]
);

json([
    'active' => true,
    'latitude' => (float) $latest['latitude'],
    'longitude' => (float) $latest['longitude'],
    'accuracy' => $latest['accuracy'] === null ? null : (int) $latest['accuracy'],
    'updated_at' => $latest['created_at'],
    'seconds_ago' => $secondsAgo,
    'trail' => array_reverse(array_map(fn($point) => [(float) $point['latitude'], (float) $point['longitude']], $trail)),
]);
