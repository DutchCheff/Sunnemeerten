<?php

const ROOT_PATH = __DIR__ . '/..';
const VIEW_PATH = ROOT_PATH . '/resources/views';
const UPLOAD_PATH = ROOT_PATH . '/public/uploads';

// Pagina's die bestaan en wie ze mag zien: guest, user of admin
const PAGES = [
    'landing_ua' => 'guest',
    'landing' => 'guest',
    'login' => 'guest',
    'register' => 'guest',
    'logout' => 'guest',
    'feed' => 'guest',
    'route' => 'guest',
    'news' => 'guest',
    'donation' => 'guest',
    'volunteer' => 'guest',
    'upload' => 'user',
    'account' => 'user',
    'admin' => 'admin',
    'admin_news' => 'admin',
    'admin_users' => 'admin',
    'admin_volunteers' => 'admin',
];

$config = file_exists(ROOT_PATH . '/config.php')
    ? require ROOT_PATH . '/config.php'
    : require ROOT_PATH . '/config.example.php';

date_default_timezone_set('Europe/Amsterdam');
ini_set('display_errors', $config['debug'] ? '1' : '0');
error_reporting(E_ALL);

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/posts.php';
require_once __DIR__ . '/upload.php';

if (session_status() === PHP_SESSION_NONE && PHP_SAPI !== 'cli') {
    session_start([
        'cookie_httponly' => true,
        'cookie_samesite' => 'Lax',
        'use_strict_mode' => true,
    ]);
}

function config(string $key, mixed $default = null): mixed
{
    global $config;
    return $config[$key] ?? $default;
}

function getPage(): string
{
    $page = $_GET['page'] ?? (isLoggedIn() || !empty($_SESSION['guest']) ? 'landing' : 'landing_ua');

    if (!array_key_exists($page, PAGES)) {
        $page = 'landing';
    }

    if (PAGES[$page] === 'user' && !isLoggedIn()) {
        flash('error', 'Log in om deze pagina te bekijken.');
        redirect('?page=login');
    }

    if (PAGES[$page] === 'admin' && !isAdmin()) {
        flash('error', 'Alleen beheerders kunnen deze pagina bekijken.');
        redirect(isLoggedIn() ? '?page=landing' : '?page=login');
    }

    return VIEW_PATH . '/' . $page . '.view.php';
}

function currentPage(): string
{
    $page = $_GET['page'] ?? 'landing';
    return array_key_exists($page, PAGES) ? $page : 'landing';
}

function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

function redirect(string $url): never
{
    header('Location: ' . $url);
    exit;
}

function isPost(): bool
{
    return $_SERVER['REQUEST_METHOD'] === 'POST';
}

function flash(string $type, string $message): void
{
    $_SESSION['flash'][] = ['type' => $type, 'message' => $message];
}

function takeFlashes(): array
{
    $flashes = $_SESSION['flash'] ?? [];
    unset($_SESSION['flash']);
    return $flashes;
}

function csrfToken(): string
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function csrfField(): string
{
    return '<input type="hidden" name="csrf" value="' . csrfToken() . '">';
}

// Stopt elke POST zonder geldige token, zodat andere sites geen formulieren namens de gebruiker kunnen versturen
function verifyCsrf(): void
{
    if (!hash_equals(csrfToken(), $_POST['csrf'] ?? '')) {
        flash('error', 'Je sessie is verlopen. Probeer het opnieuw.');
        redirect('?page=' . currentPage());
    }
}

function old(string $key): string
{
    return e($_POST[$key] ?? '');
}

function formatDate(string $date, bool $withTime = true): string
{
    $months = ['januari', 'februari', 'maart', 'april', 'mei', 'juni', 'juli', 'augustus', 'september', 'oktober', 'november', 'december'];
    $time = strtotime($date);
    $formatted = date('j', $time) . ' ' . $months[date('n', $time) - 1] . ' ' . date('Y', $time);

    return $withTime ? $formatted . ', ' . date('H:i', $time) : $formatted;
}

// Wisselt de kaartkleuren af zoals in het ontwerp: groen, geel, rood
function cardColor(int $index): string
{
    return ['card', 'accent1', 'accent2'][$index % 3];
}
