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
    <title>Formular de Înregistrare</title>

    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
<h2>Formular de Înregistrare</h2>
<form action="procesare_register.php" method="post">
    <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" class="form-control" name="email" required>
    </div>

    <div class="form-group">
        <label for="password">Parola:</label>
        <input type="password" class="form-control" name="password" required>
    </div>

    <div class="form-group">
        <label for="confirm_password">Confirmă Parola:</label>
        <input type="password" class="form-control" name="confirm_password" required>
    </div>

    <div class="form-group">
        <label for="name">Nume:</label>
        <input type="text" class="form-control" name="name" required>
    </div>

    <div class="form-group">
        <label for="username">Username:</label>
        <input type="text" class="form-control" name="username" required>
    </div>

    <div class="form-group">
        <label for="status">Status:</label>
        <select class="form-control" name="status">
            <option value="user">Utilizator</option>
            <option value="admin">Administrator</option>
        </select>
    </div>

    <button type="submit" class="btn btn-primary">Înregistrare</button>
    
 
</form>
<button id="goHome" onclick="window.location.href='index.php'">Home</button>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
