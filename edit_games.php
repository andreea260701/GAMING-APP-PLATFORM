<?php
session_start();
include 'connect.php';
global $conn;

if (!isset($_SESSION['status']) || $_SESSION['status'] !== 'admin') {
    header('Location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    $query_update = "UPDATE games SET name = ?, description = ?, price = ? WHERE id = ?";
    $stmt_update = $conn->prepare($query_update);
    $stmt_update->bind_param('ssdi', $name, $description, $price, $id);
    $stmt_update->execute();
}

$query_games = "SELECT id, name, description, price FROM games";
$result_games = $conn->query($query_games);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editare Jocuri</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body class="container mt-5">
    <h2>Editare Jocuri</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nume</th>
                <th>Descriere</th>
                <th>Preț</th>
                <th>Acțiuni</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result_games->fetch_assoc()): ?>
            <tr>
                <form method="POST" action="edit_games.php">
                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                    <td>
                        <input type="text" name="name" value="<?php echo htmlspecialchars($row['name']); ?>" class="form-control">
                    </td>
                    <td>
                        <input type="text" name="description" value="<?php echo htmlspecialchars($row['description']); ?>" class="form-control">
                    </td>
                    <td>
                        <input type="number" step="0.01" name="price" value="<?php echo htmlspecialchars($row['price']); ?>" class="form-control">
                    </td>
                    <td>
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>">
                        <button type="submit" class="btn btn-primary">Salvează</button>
                    </td>
                </form>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <a href="index.php" class="btn btn-secondary">Înapoi la index</a>
</body>
</html>
