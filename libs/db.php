<?php

function db(): PDO
{
    static $pdo = null;

    if ($pdo === null) {
        $config = config('db');
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        if ($config['driver'] === 'sqlite') {
            $pdo = new PDO('sqlite:' . $config['sqlite_path'], null, null, $options);
            $pdo->exec('PRAGMA foreign_keys = ON');
        } else {
            $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['name']};charset=utf8mb4";
            $pdo = new PDO($dsn, $config['user'], $config['password'], $options);
        }
    }

    return $pdo;
}

function dbAll(string $sql, array $params = []): array
{
    $stmt = db()->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

function dbOne(string $sql, array $params = []): ?array
{
    $stmt = db()->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetch() ?: null;
}

function dbRun(string $sql, array $params = []): int
{
    $stmt = db()->prepare($sql);
    $stmt->execute($params);
    return $stmt->rowCount();
}

function dbInsert(string $sql, array $params = []): int
{
    dbRun($sql, $params);
    return (int) db()->lastInsertId();
}

function getSetting(string $name, mixed $default = null): mixed
{
    $row = dbOne('SELECT value FROM settings WHERE name = ?', [$name]);
    return $row ? json_decode($row['value'], true) : $default;
}

function setSetting(string $name, mixed $value): void
{
    if (dbOne('SELECT name FROM settings WHERE name = ?', [$name])) {
        dbRun('UPDATE settings SET value = ? WHERE name = ?', [json_encode($value), $name]);
    } else {
        dbRun('INSERT INTO settings (name, value) VALUES (?, ?)', [$name, json_encode($value)]);
    }
}

function now(): string
{
    return date('Y-m-d H:i:s');
}
