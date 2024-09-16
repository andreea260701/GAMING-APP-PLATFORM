<?php
session_start();
include 'connect.php';
global $conn;

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$purchased_games = [];

if ($user_id) {
    $query_games = "
        SELECT games.id, games.name 
        FROM games 
        JOIN purchases ON games.id = purchases.game_id 
        WHERE purchases.user_id = ?";
    $stmt_games = $conn->prepare($query_games);
    $stmt_games->bind_param("i", $user_id);
    $stmt_games->execute();
    $result_games = $stmt_games->get_result();

    while ($row = $result_games->fetch_assoc()) {
        $purchased_games[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Game Website</title>
    <meta content="" name="description">
    <meta content="" name="keywords">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

    <link href="assets/vendor/aos/aos.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
    <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

    <link href="assets/css/style.css" rel="stylesheet">
    <style>
        #navbar {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            flex-wrap: wrap;
            max-height: 100vh;
            overflow-y: auto;
        }
        .nav-item {
            margin-bottom: 10px;
        }
        .nav-column {
            display: flex;
            flex-direction: column;
        }
        .container {
            display: flex;
            justify-content: space-between;
        }
        .column {
            flex: 1;
            margin: 10px;
        }
    </style>
</head>
<body>
    <i class="bi bi-list mobile-nav-toggle d-xl-none"></i>
    <header id="header">
        <div class="d-flex flex-column">
            <div class="profile">
            <h1 class="text-light" style="margin-top: 20px;"><a href="index.php">Vechiu Andreea</a></h1>

                <p class="text-center text-light">Founder of this GameWebsite</p>
            </div>
            <style>
                body {
                    background-color: #f8f9fa;
                }
                h1 {
                    text-align: center;
                    margin-bottom: 20px;
                }
                #questionContainer {
                    display: none;
                    background-color: #ffffff;
                    border-radius: 10px;
                    padding: 20px;
                    margin: 20px auto;
                    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                    max-width: 600px;
                }
                form {
                    margin-top: 20px;
                }
                label {
                    display: block;
                    margin-bottom: 10px;
                }
                button {
                    background-color: #007bff;
                    color: #fff;
                    padding: 10px 20px;
                    border: none;
                    border-radius: 5px;
                    cursor: pointer;
                }
                #result {
                    margin-top: 10px;
                    font-weight: bold;
                }
                #resultContainer {
                    text-align: center;
                    margin-top: 20px;
                }
                .instructions {
                    display: none;
                    margin-top: 20px;
                    padding: 20px;
                    background-color: #f8f9fa;
                    border-radius: 10px;
                    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                    max-width: 600px;
                    margin: 0 auto;
                }
                .start-button {
                    margin-top: 10px;
                    padding: 10px 20px;
                    background-color: #007bff;
                    color: #fff;
                    border: none;
                    border-radius: 5px;
                    cursor: pointer;
                }
            </style>
            <nav id="navbar" class="nav-menu navbar">
                <div class="container">
                    <div class="column">
                        <div class="nav-column">
                            <?php
                            if (!isset($_SESSION['username'])) {
                                echo "<a class='nav-link nav-item active' href='register.php'>Register</a>";
                                echo "<a class='nav-link nav-item active' href='login.php'>Welcome Guest</a>";
                                echo "<a class='nav-link nav-item active' href='login.php'>Login</a>";
                            } else {
                                echo "<a class='nav-link nav-item active' href='profile.php'>Welcome " . $_SESSION['username'] . "</a>";
                                echo "<a class='nav-link nav-item active' href='logout.php'>Logout</a>";
                                if (isset($_SESSION['status']) && $_SESSION['status'] === "admin") {
                                    echo "<a class='nav-link nav-item active' href='quizform.php'>Quiz Form</a>";
                                    echo "<a class='nav-link nav-item active' href='register_admin.php'>Register Admin</a>";
                                    echo "<a class='nav-link nav-item active' href='add_game.php'>Add Game</a>";
                                    echo "<a class='nav-link nav-item active' href='edit_games.php'>Edit Games</a>";
                                    echo "<a class='nav-link nav-item active' href='report.php'>User Report</a>";
                                }
                            }
                            ?>
                            <a class='nav-link nav-item active' href='#' id='quizButton'>Quiz</a>
                            <a class='nav-link nav-item active' href='#' id='tictactoeButton'>Tic-Tac-Toe</a>
                            <a class='nav-link nav-item active' href='#' id='memorygameButton'>Memory Game</a>
                            <a class='nav-link nav-item active' href='#' id='snakeButton'>Snake</a>
                            <a class='nav-link nav-item active' href='catalog.php'>Catalog</a>
                        </div>
                    </div>
                    <div class="column">
                        <div class="nav-column">
                            <?php
                            $tetris_purchased = false;
                            $flappybird_purchased = false;
                            $pong_purchased = false;
                            $breakout_purchased = false;
                            foreach ($purchased_games as $game) {
                                if (strtolower($game['name']) == 'tetris') {
                                    $tetris_purchased = true;
                                }
                                if (strtolower($game['name']) == 'flappy bird') {
                                    $flappybird_purchased = true;
                                }
                                if (strtolower($game['name']) == 'pong') {
                                    $pong_purchased = true;
                                }
                                if (strtolower($game['name']) == 'breakout') {
                                    $breakout_purchased = true;
                                }
                            }
                            ?>
                            <div class="purchased-games">
                                <?php if ($tetris_purchased): ?>
                                    <div class="game">
                                        <a href="game_tetris.php" class="nav-link nav-item active">Tetris</a>
                                    </div>
                                <?php endif; ?>
                                <?php if ($flappybird_purchased): ?>
                                    <div class="game">
                                        <a href="game_flappybird.php" class="nav-link nav-item active">Flappy Bird</a>
                                    </div>
                                <?php endif; ?>
                                <?php if ($pong_purchased): ?>
                                    <div class="game">
                                        <a href="game_pong.php" class="nav-link nav-item active">Pong</a>
                                    </div>
                                <?php endif; ?>
                                <?php if ($breakout_purchased): ?>
                                    <div class="game">
                                        <a href="game_breakout.php" class="nav-link nav-item active">Breakout</a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>
        </div>
    </header>

    <section id="hero" class="d-flex flex-column justify-content-center align-items-center">
        <div class="hero-container" data-aos="fade-in">
            <h1>Vechiu Andreea</h1>
            <p>"I'm a <span class="typed" data-typed-items="Student, Developer "></span></p>
            <p>Scroll down for Games</p>
        </div>
    </section>

    <main id="main">
        <div id="quizContainer" style="display: none;">
            <?php include 'quiz.php'; ?>
        </div>
        <div id="tictactoeContainer" style="display: none;">
            <div class="instructions" id="tictactoeInstructions">
                <h2>Tic-Tac-Toe Instructions</h2>
                <p>The game is played on a grid that's 3 squares by 3 squares. Player X always goes first. Players take turns putting their marks in empty squares. The first player to get 3 of their marks in a row (up, down, across, or diagonally) is the winner. When all 9 squares are full, the game is over. If no player has 3 marks in a row, the game ends in a draw.</p>
                <button class="start-button" onclick="startTicTacToe()">Start Game</button>
            </div>
            <div id="tictactoeGame" style="display: none;">
                <?php include 'tictactoe.php'; ?>
            </div>
        </div>
        <div id="memorygameContainer" style="display: none;">
            <div class="instructions" id="memorygameInstructions">
                <h2>Memory Game Instructions</h2>
                <p>Match pairs of cards by flipping them over. Each turn, a player flips two cards and tries to find a matching pair. If the cards match, they remain face up. If they don't match, they are flipped back face down. The game continues until all pairs are matched.</p>
                <button class="start-button" onclick="startMemoryGame()">Start Game</button>
            </div>
            <div id="memorygameGame" style="display: none;">
                <?php include 'memorygame.php'; ?>
            </div>
        </div>
        <div id="snakeContainer" style="display: none;">
            <div class="instructions" id="snakeInstructions">
                <h2>Snake Game Instructions</h2>
                <p>Control the snake to collect food. Use the arrow keys to move the snake. Each time the snake eats food, it grows longer. The game ends if the snake runs into the walls or itself.</p>
                <button class="start-button" onclick="startSnake()">Start Game</button>
            </div>
            <div id="snakeGame" style="display: none;">
                <?php include 'snake.php'; ?>
            </div>
        </div>
    </main>

    <footer id="footer">
        <div class="container">
            <div class="copyright">
                &copy; Copyright <strong><span>AC</span></strong>
            </div>
            <div class="credits">
                Designed by <a href="#">Vechiu Florina</a>
            </div>
        </div>
    </footer>

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

    <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
    <script src="assets/vendor/aos/aos.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
    <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
    <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
    <script src="assets/vendor/typed.js/typed.umd.js"></script>
    <script src="assets/vendor/waypoints/noframework.waypoints.js"></script>
    <script src="assets/vendor/php-email-form/validate.js"></script>
    <script src="assets/js/main.js"></script>
    <script>
        document.getElementById('quizButton').addEventListener('click', function () {
            document.getElementById('quizContainer').style.display = 'block';
            document.getElementById('tictactoeContainer').style.display = 'none';
            document.getElementById('memorygameContainer').style.display = 'none';
            document.getElementById('snakeContainer').style.display = 'none';
        });
        document.getElementById('tictactoeButton').addEventListener('click', function () {
            document.getElementById('quizContainer').style.display = 'none';
            document.getElementById('tictactoeContainer').style.display = 'block';
            document.getElementById('memorygameContainer').style.display = 'none';
            document.getElementById('snakeContainer').style.display = 'none';
            document.getElementById('tictactoeInstructions').style.display = 'block';
            document.getElementById('tictactoeGame').style.display = 'none';
        });
        document.getElementById('memorygameButton').addEventListener('click', function () {
            document.getElementById('quizContainer').style.display = 'none';
            document.getElementById('tictactoeContainer').style.display = 'none';
            document.getElementById('memorygameContainer').style.display = 'block';
            document.getElementById('snakeContainer').style.display = 'none';
            document.getElementById('memorygameInstructions').style.display = 'block';
            document.getElementById('memorygameGame').style.display = 'none';
        });
        document.getElementById('snakeButton').addEventListener('click', function () {
            document.getElementById('quizContainer').style.display = 'none';
            document.getElementById('tictactoeContainer').style.display = 'none';
            document.getElementById('memorygameContainer').style.display = 'none';
            document.getElementById('snakeContainer').style.display = 'block';
            document.getElementById('snakeInstructions').style.display = 'block';
            document.getElementById('snakeGame').style.display = 'none';
        });

        function startTicTacToe() {
            document.getElementById('tictactoeInstructions').style.display = 'none';
            document.getElementById('tictactoeGame').style.display = 'block';
        }

        function startMemoryGame() {
            document.getElementById('memorygameInstructions').style.display = 'none';
            document.getElementById('memorygameGame').style.display = 'block';
        }

        function startSnake() {
            document.getElementById('snakeInstructions').style.display = 'none';
            document.getElementById('snakeGame').style.display = 'block';
        }
    </script>
</body>
</html>
