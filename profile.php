<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$query = "SELECT username, email FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$query_invoices = "
    SELECT invoices.id as invoice_id, invoices.invoice_date, games.name as game_name, games.price 
    FROM invoices 
    JOIN purchases ON invoices.purchase_id = purchases.id 
    JOIN games ON purchases.game_id = games.id 
    WHERE invoices.user_id = ?";
$stmt_invoices = $conn->prepare($query_invoices);
$stmt_invoices->bind_param("i", $user_id);
$stmt_invoices->execute();
$result_invoices = $stmt_invoices->get_result();
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
    <title>User Profile</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h2>User Profile</h2>
    <button id="goHome" onclick="window.location.href='index.php'">Home</button>
    <form action="update_profile.php" method="post">
        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" class="form-control" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required readonly>
        </div>
        <div class="form-group">
            <label for="password">New Password:</label>
            <input type="password" class="form-control" name="password">
        </div>
        <button type="submit" class="btn btn-primary">Update Profile</button>
    </form>
    <h3 class="mt-5">Invoices</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Invoice ID</th>
                <th>Game Name</th>
                <th>Price</th>
                <th>Invoice Date</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($invoice = $result_invoices->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $invoice['invoice_id']; ?></td>
                    <td><?php echo htmlspecialchars($invoice['game_name']); ?></td>
                    <td><?php echo htmlspecialchars($invoice['price']); ?></td>
                    <td><?php echo $invoice['invoice_date']; ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>
