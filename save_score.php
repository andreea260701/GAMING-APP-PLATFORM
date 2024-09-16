<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "quizdb";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexiune eșuată: " . $conn->connect_error);
}

$newScore = $_POST['score'];

$username = $_SESSION['username'];

$sqlGetScore = "SELECT score FROM users WHERE username = '$username'";
$result = $conn->query($sqlGetScore);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $currentScore = $row['score'];

    if ($newScore > $currentScore) {
        $sqlUpdateScore = "UPDATE users SET score = $newScore WHERE username = '$username'";

        if ($conn->query($sqlUpdateScore) === TRUE) {
            echo "Scorul a fost actualizat cu succes.";
        } else {
            echo "Eroare la actualizarea scorului: " . $conn->error;
        }
    } else {
        echo "Scorul nu a fost actualizat. Noul scor este mai mic sau egal cu scorul curent.";
    }
} else {
    echo "Eroare la obținerea scorului existent.";
}

$conn->close();
?>
