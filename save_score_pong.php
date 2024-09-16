<?php
session_start();
include 'connect.php';

$data = json_decode(file_get_contents('php://input'), true);
$speed = isset($data['speed']) ? (float)$data['speed'] : 0;
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

if ($user_id && $speed > 0) {
    $query = "SELECT score_pong FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    
    if ($speed > $user['score_pong']) {
        $update_query = "UPDATE users SET score_pong = ? WHERE id = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param("di", $speed, $user_id);
        $update_stmt->execute();
    }
}
?>
