<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    exit();
}

$user_id = $_SESSION['user_id'];
$data = json_decode(file_get_contents('php://input'), true);
$new_score = isset($data['score']) ? intval($data['score']) : 0;

$query = "SELECT score_tetris FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$current_score_tetris = $user['score_tetris'];

if ($new_score > $current_score_tetris) {
    $update_query = "UPDATE users SET score_tetris = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param("ii", $new_score, $user_id);
    $update_stmt->execute();
}
?>
