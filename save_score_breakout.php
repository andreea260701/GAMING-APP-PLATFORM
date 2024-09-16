<?php
session_start();
include 'connect.php';

$data = json_decode(file_get_contents('php://input'), true);
$score = isset($data['score']) ? (int)$data['score'] : 0;
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

if ($user_id && $score > 0) {
    $query = "SELECT score_breakout FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    
    if ($score > $user['score_breakout']) {
        $update_query = "UPDATE users SET score_breakout = ? WHERE id = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param("ii", $score, $user_id);
        $update_stmt->execute();
    }
}
?>
