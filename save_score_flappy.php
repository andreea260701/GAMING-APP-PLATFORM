<?php
session_start();
include 'connect.php';

$data = json_decode(file_get_contents('php://input'), true);
$score = isset($data['score']) ? (int)$data['score'] : 0;
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

if ($user_id && $score > 0) {
    $query = "UPDATE users SET score_flappy = GREATEST(score_flappy, ?) WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $score, $user_id);
    $stmt->execute();
}
?>
