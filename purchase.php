<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $game_id = $_POST['game_id'];

    $sql = "SELECT price FROM games WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $game_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $game = $result->fetch_assoc();
    $price = $game['price'];

    $sql = "INSERT INTO purchases (user_id, game_id, amount) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iid", $user_id, $game_id, $price);
    $stmt->execute();
    $purchase_id = $stmt->insert_id;

    $sql = "INSERT INTO invoices (purchase_id, user_id) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $purchase_id, $user_id);
    $stmt->execute();

    header("Location: invoice.php?purchase_id=" . $purchase_id);
    exit();
}
?>
