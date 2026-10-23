<?php

function currentUser(): ?array
{
    static $user = false;

    if ($user === false) {
        $user = isset($_SESSION['user_id'])
            ? dbOne('SELECT id, username, email, is_admin FROM users WHERE id = ?', [$_SESSION['user_id']])
            : null;
    }

    return $user;
}

function isLoggedIn(): bool
{
    return currentUser() !== null;
}

function isAdmin(): bool
{
    return (bool) (currentUser()['is_admin'] ?? false);
}

function loginUser(int $userId): void
{
    session_regenerate_id(true);
    $_SESSION['user_id'] = $userId;
    unset($_SESSION['guest']);
}

function logoutUser(): void
{
    $_SESSION = [];
    session_regenerate_id(true);
}

// Geeft een lijst met foutmeldingen terug, leeg als alles klopt
function validateAccount(string $username, string $email, ?int $ignoreUserId = null): array
{
    $errors = [];

    if (mb_strlen($username) < 2 || mb_strlen($username) > 50) {
        $errors[] = 'Je naam moet tussen de 2 en 50 tekens lang zijn.';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Voer een geldig e-mailadres in.';
    } elseif (dbOne('SELECT id FROM users WHERE email = ? AND id != ?', [$email, $ignoreUserId ?? 0])) {
        $errors[] = 'Er bestaat al een account met dit e-mailadres.';
    }

    return $errors;
}

function validatePassword(string $password, string $confirmPassword): array
{
    $errors = [];

    if (mb_strlen($password) < 8) {
        $errors[] = 'Je wachtwoord moet minimaal 8 tekens lang zijn.';
    }
    if ($password !== $confirmPassword) {
        $errors[] = 'Wachtwoorden komen niet overeen.';
    }

    return $errors;
}
