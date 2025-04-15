<?php
session_start();
require_once '../public/database/db.php';

$email = 'Guest'; // Or however you identify your guest

$stmt = $conn->prepare("SELECT id, username FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($user = $result->fetch_assoc()) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];

    header('Location: ?page=landing'); // or wherever you want to redirect
    exit;
} else {
    echo "Gastgebruiker niet gevonden.";
}

$stmt->close();
?>