<?php
if (isPost()) {
    verifyCsrf();

    if (!isLoggedIn() || isVerified()) {
        redirect('?page=landing');
    }

    // Maximaal één nieuwe mail per minuut
    if (time() - ($_SESSION['verify_sent_at'] ?? 0) < 60) {
        flash('error', 'We hebben net al een mail gestuurd. Wacht een minuut en probeer het opnieuw.');
    } elseif (sendVerificationMail(currentUser()['id'])) {
        $_SESSION['verify_sent_at'] = time();
        flash('success', 'We hebben een nieuwe bevestigingsmail gestuurd naar ' . currentUser()['email'] . '.');
    } else {
        flash('error', 'De mail kon niet worden verstuurd. Probeer het later opnieuw.');
    }
    redirect('?page=account');
}

$userId = useEmailToken($_GET['token'] ?? '', 'verify');

if (!$userId) {
    flash('error', 'Deze link is ongeldig of verlopen. Vraag via je account een nieuwe aan.');
    redirect(isLoggedIn() ? '?page=account' : '?page=login');
}

dbRun('UPDATE users SET email_verified_at = ? WHERE id = ? AND email_verified_at IS NULL', [now(), $userId]);
flash('success', 'Je e-mailadres is bevestigd. Bedankt!');
redirect(isLoggedIn() ? '?page=landing' : '?page=login');
