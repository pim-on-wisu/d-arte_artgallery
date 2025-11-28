<?php
session_start();
include '../connect.php';

header('Content-Type: application/json');

if (!isset($_SESSION['audience_id']) || $_SESSION['user_type'] !== 'audience') {
    echo json_encode(['success' => false, 'message' => 'User not logged in or not an audience. Session data: ' . print_r($_SESSION, true)]);
    exit;

    error_log("Session data: " . print_r($_SESSION, true));
}

$user_id = $_SESSION['audience_id'];
$data = json_decode(file_get_contents('php://input'), true);
$art_id = $data['artId'];
$liked = $data['liked'];

try {
    if ($liked) {
        $sql = "INSERT INTO Likes (audience_id, art_id) VALUES (:user_id, :art_id)
                ON DUPLICATE KEY UPDATE audience_id = audience_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['user_id' => $user_id, 'art_id' => $art_id]);
    } else {
        // Remove like
        $sql = "DELETE FROM Likes WHERE audience_id = :user_id AND art_id = :art_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['user_id' => $user_id, 'art_id' => $art_id]);
    }

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

?>
