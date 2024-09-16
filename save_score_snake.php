<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$new_score = $_POST['score'];

$query = "SELECT score_snake FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$current_score_snake = $user['score_snake'];

if ($new_score > $current_score_snake) {
    $update_query = "UPDATE users SET score_snake = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param("ii", $new_score, $user_id);
    $update_stmt->execute();
}
?>
