<?php
session_start();
include 'connect.php';



if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    $query = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
        echo "<script>alert('Utilizatorul a fost șters cu succes.'); window.location.href = 'report.php';</script>";
    } else {
        echo "<script>alert('A apărut o eroare la ștergerea utilizatorului.'); window.location.href = 'report.php';</script>";
    }
} else {
    echo "<script>alert('ID-ul utilizatorului nu a fost specificat.'); window.location.href = 'report.php';</script>";
}
?>
