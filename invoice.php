
<?php
include 'connect.php'; 
session_start();
$purchase_id = $_GET['purchase_id'];


$sql = "SELECT p.purchase_date, p.amount, g.name AS game_name, u.username, i.invoice_date 
        FROM purchases p
        JOIN games g ON p.game_id = g.id
        JOIN users u ON p.user_id = u.id
        JOIN invoices i ON p.id = i.purchase_id
        WHERE p.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $purchase_id);
$stmt->execute();
$result = $stmt->get_result();
$invoice = $result->fetch_assoc();
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
<html>
<head>
    <title>Invoice</title>
</head>
<body>
    <h1>Invoice</h1>
    <p>Invoice Date: <?php echo $invoice['invoice_date']; ?></p>
    <p>Username: <?php echo $invoice['username']; ?></p>
    <p>Game: <?php echo $invoice['game_name']; ?></p>
    <p>Purchase Date: <?php echo $invoice['purchase_date']; ?></p>
    <p>Amount: $<?php echo $invoice['amount']; ?></p>
    <button id="goHome" onclick="window.location.href='index.php'">Home</button>
</body>
</html>
