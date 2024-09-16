<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$username = $_POST['username'];
$password = $_POST['password'];

if (!empty($password)) {
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $query = "UPDATE users SET username = ?, parola = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssi", $username, $hashed_password, $user_id);
} else {
    $query = "UPDATE users SET username = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $username, $user_id);
}

if ($stmt->execute()) {
    $_SESSION['username'] = $username;
    echo "<script>alert('Profile updated successfully!'); window.location.href = 'profile.php';</script>";
} else {
    echo "<script>alert('Error updating profile.'); window.location.href = 'profile.php';</script>";
}
?>
