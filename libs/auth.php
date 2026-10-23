<?php

function currentUser(): ?array
{
    static $user = false;

    if ($user === false) {
        $user = isset($_SESSION['user_id'])
            ? dbOne('SELECT id, username, email, is_admin, email_verified_at FROM users WHERE id = ?', [$_SESSION['user_id']])
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

function isVerified(): bool
{
    return !empty(currentUser()['email_verified_at']);
}

// Maakt een eenmalige code voor een link in een mail. In de database staat alleen de hash, net als bij wachtwoorden.
function createEmailToken(int $userId, string $type, int $validHours = 48): string
{
    $token = bin2hex(random_bytes(32));
    dbRun('DELETE FROM email_tokens WHERE user_id = ? AND type = ?', [$userId, $type]);
    dbRun(
        'INSERT INTO email_tokens (user_id, token_hash, type, expires_at, created_at) VALUES (?, ?, ?, ?, ?)',
        [$userId, hash('sha256', $token), $type, date('Y-m-d H:i:s', time() + $validHours * 3600), now()]
    );

    return $token;
}

// Geeft het gebruikers-id terug als de code klopt en nog geldig is. Een code werkt maar één keer.
function useEmailToken(string $token, string $type): ?int
{
    $row = dbOne(
        'SELECT id, user_id FROM email_tokens WHERE token_hash = ? AND type = ? AND expires_at > ?',
        [hash('sha256', $token), $type, now()]
    );
    if (!$row) {
        return null;
    }

    dbRun('DELETE FROM email_tokens WHERE id = ?', [$row['id']]);
    return (int) $row['user_id'];
}

function sendVerificationMail(int $userId): bool
{
    $user = dbOne('SELECT username, email FROM users WHERE id = ?', [$userId]);
    $token = createEmailToken($userId, 'verify');

    return sendMail($user['email'], 'Bevestig je e-mailadres', 'verify', [
        'name' => $user['username'],
        'verifyUrl' => url('?page=verify&token=' . $token),
    ]);
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
