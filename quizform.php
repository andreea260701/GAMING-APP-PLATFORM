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
    <title>Formular Adăugare Întrebare</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
<h2>Formular Adăugare Întrebare</h2>
<form action="procesare_formular.php" method="post">
    <div class="form-group">
        <label for="question_text">Textul Întrebării:</label>
        <input type="text" class="form-control" name="question_text" required>
    </div>

    <div class="form-group">
        <label for="choice1">Răspunsul 1:</label>
        <input type="text" class="form-control" name="choice1" required>
    </div>

    <div class="form-group">
        <label for="choice2">Răspunsul 2:</label>
        <input type="text" class="form-control" name="choice2" required>
    </div>

    <div class="form-group">
        <label for="choice3">Răspunsul 3:</label>
        <input type="text" class="form-control" name="choice3" required>
    </div>

    <div class="form-group">
        <label for="choice4">Răspunsul 4:</label>
        <input type="text" class="form-control" name="choice4" required>
    </div>

    <div class="form-group">
        <label for="correct_answer">Răspunsul Corect:</label>
        <input type="text" class="form-control" name="correct_answer" required>
    </div>

    <button type="submit" class="btn btn-primary">Adăugare Întrebare</button>
   
</form>
<button id="goHome" onclick="window.location.href='index.php'">Home</button>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
