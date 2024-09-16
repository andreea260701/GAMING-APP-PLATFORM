<?php session_start(); ?>
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
    <title>Adaugă Joc</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
<h2>Adaugă Joc</h2>
<form action="process_add_game.php" method="post">
    <div class="form-group">
        <label for="name">Nume:</label>
        <input type="text" class="form-control" name="name" required>
    </div>

    <div class="form-group">
        <label for="description">Descriere:</label>
        <textarea class="form-control" name="description" required></textarea>
    </div>

    <div class="form-group">
        <label for="price">Preț:</label>
        <input type="number" step="0.01" class="form-control" name="price" required>
    </div>

    <button type="submit" class="btn btn-primary">Adaugă Joc</button>
</form>
<button id="goHome" onclick="window.location.href='index.php'">Home</button>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
