<?php
include 'connect.php';
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    $sql = "INSERT INTO games (name, description, price) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssd", $name, $description, $price);

    if ($stmt->execute()) {
        echo "Jocul a fost adăugat cu succes!";
        header("Location: catalog.php");
        exit();
    } else {
        echo "Eroare la adăugarea jocului: " . $conn->error;
    }
}
?>
