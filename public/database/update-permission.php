<?php
header('Content-Type: application/json');
require 'db.php'; // connect using MySQLi

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['post_id'], $data['is_allowed'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing post_id or is_allowed']);
    exit;
}

$post_id = (int)$data['post_id'];
$is_allowed = (int)$data['is_allowed'];

$stmt = $conn->prepare("UPDATE userPosts SET is_allowed = ? WHERE id = ?");
$stmt->bind_param("ii", $is_allowed, $post_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    http_response_code(500);
    echo json_encode(['error' => $stmt->error]);
}

$stmt->close();
$conn->close();
?>
