<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['user_id']) || !isset($_SESSION['status'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$status = $_SESSION['status'];

$query = "SELECT email FROM users WHERE id = ? AND status = 'admin'";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($result->num_rows == 0 || $user['email'] != 'andreea@gmail.com') {
    echo "<script>
            alert('You do not have access to this page.');
            window.location.href = 'index.php';
          </script>";
    exit();
}

$query = "SELECT id, username, status FROM users";
$result = $conn->query($query);

?>
<style>
 #goHome {
            margin-top: 10px;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
</style>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Report</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>User Report</h2>
        <?php
        if ($result->num_rows > 0) {
            echo "<table class='table'>";
            echo "<thead><tr><th>ID</th><th>Username</th><th>Status</th><th>Actions</th></tr></thead>";
            echo "<tbody>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td>" . htmlspecialchars($row['username']) . "</td>";
                echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                echo "<td>";
                echo "<a href='edit_user.php?id=" . $row['id'] . "' class='btn btn-primary'>Edit</a> ";
                echo "<a href='delete_user.php?id=" . $row['id'] . "' class='btn btn-danger' onclick='return confirm(\"Are you sure you want to delete this user?\");'>Delete</a>";
                echo "</td>";
                echo "</tr>";
            }
            echo "</tbody>";
            echo "</table>";
        } else {
            echo "No users found.";
        }

        $conn->close();
        ?>
        <button id="goHome" onclick="window.location.href='index.php'">Home</button>
    </div>
</body>
</html>
