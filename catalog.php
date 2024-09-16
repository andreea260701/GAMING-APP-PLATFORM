<?php
include 'connect.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$sql_games = "SELECT * FROM games";
$result_games = $conn->query($sql_games);

$sql_purchases = "SELECT game_id FROM purchases WHERE user_id = ?";
$stmt_purchases = $conn->prepare($sql_purchases);
$stmt_purchases->bind_param("i", $user_id);
$stmt_purchases->execute();
$result_purchases = $stmt_purchases->get_result();

$purchased_games = [];
while ($row = $result_purchases->fetch_assoc()) {
    $purchased_games[] = $row['game_id'];
}
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
    <title>Game Catalog</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
<h1>Game Catalog</h1>
<button id="goHome" onclick="window.location.href='index.php'">Home</button>
<ul class="list-group">
    <?php if ($result_games->num_rows > 0): ?>
        <?php while ($row = $result_games->fetch_assoc()): ?>
            <li class="list-group-item">
                <h2><?php echo htmlspecialchars($row['name']); ?></h2>
                <p><?php echo htmlspecialchars($row['description']); ?></p>
                <p>Price: $<?php echo htmlspecialchars($row['price']); ?></p>
                <?php if (in_array($row['id'], $purchased_games)): ?>
                    <button class="btn btn-secondary" disabled>Already Purchased</button>
                <?php else: ?>
                    <form action="purchase.php" method="post">
                        <input type="hidden" name="game_id" value="<?php echo $row['id']; ?>">
                        <button type="submit" class="btn btn-success">Buy</button>
                    </form>
                <?php endif; ?>
            </li>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No games available.</p>
    <?php endif; ?>
</ul>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
