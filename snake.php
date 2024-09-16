<?php

include 'connect.php';

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest';

if ($user_id) {
    $query = "SELECT score_snake FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $current_score_snake = $user['score_snake'];
}

$query_leaderboard = "SELECT username, score_snake FROM users ORDER BY score_snake DESC LIMIT 10";
$result_leaderboard = $conn->query($query_leaderboard);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Snake Game</title>
    <style>
        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            background-color: #f8f9fa;
            color: #333;
            font-family: Arial, sans-serif;
        }
        canvas {
            background-color: #000;
            margin-top: 20px;
        }
        #snake-game {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 20px;
        }
        #resetButton {
            margin-top: 10px;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        #scoreDisplay {
            margin-top: 10px;
            font-size: 20px;
            font-weight: bold;
        }
        #gameOverDisplay {
            margin-top: 10px;
            font-size: 24px;
            font-weight: bold;
            color: red;
            display: none;
        }
        #leaderboard {
            margin-top: 20px;
            width: 80%;
        }
        #leaderboard h3 {
            margin-bottom: 10px;
            text-align: center;
        }
        #leaderboard table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        #leaderboard th, #leaderboard td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        #leaderboard th {
            background-color: #007bff;
            color: white;
        }
        #leaderboard tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        #leaderboard tr:hover {
            background-color: #ddd;
        }
        #leaderboard .top-score {
            background-color: #ffd700;
            color: #000;
        }
    </style>
</head>
<body>
    <h1>Snake Game</h1>
    <div id="snake-game">
        <canvas id="snakeCanvas" width="400" height="400" style="border:1px solid #000000;"></canvas>
        <button id="resetButton" onclick="resetGame()">Reset</button>
        <div id="scoreDisplay">Score: 0</div>
        <div id="gameOverDisplay">Game Over</div>
    </div>
    <div id="leaderboard">
        <h3>Leaderboard</h3>
        <table>
            <thead>
                <tr>
                    <th>Rank</th>
                    <th>Username</th>
                    <th>Score</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $rank = 1;
                while ($row = $result_leaderboard->fetch_assoc()): 
                    $row_class = $rank == 1 ? 'top-score' : '';
                ?>
                    <tr class="<?php echo $row_class; ?>">
                        <td><?php echo $rank; ?></td>
                        <td><?php echo htmlspecialchars($row['username']); ?></td>
                        <td><?php echo htmlspecialchars($row['score_snake']); ?></td>
                    </tr>
                <?php 
                    $rank++;
                endwhile; 
                ?>
            </tbody>
        </table>
    </div>
    <div class="btn-button">
        <a href="index.php">Home</a>
    </div>
    <script>
        let canvas = document.getElementById("snakeCanvas");
        let ctx = canvas.getContext("2d");
        let box = 20;
        let snake = [];
        snake[0] = {x: 9 * box, y: 10 * box};
        let food = {
            x: Math.floor(Math.random() * 19 + 1) * box,
            y: Math.floor(Math.random() * 19 + 1) * box
        };
        let direction;
        let score = 0;

        document.addEventListener("keydown", directionHandler);

        function directionHandler(event) {
            event.preventDefault();
            if (event.keyCode == 37 && direction != "RIGHT") {
                direction = "LEFT";
            } else if (event.keyCode == 38 && direction != "DOWN") {
                direction = "UP";
            } else if (event.keyCode == 39 && direction != "LEFT") {
                direction = "RIGHT";
            } else if (event.keyCode == 40 && direction != "UP") {
                direction = "DOWN";
            }
        }

        function draw() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            for (let i = 0; i < snake.length; i++) {
                ctx.fillStyle = (i == 0) ? "green" : "white";
                ctx.fillRect(snake[i].x, snake[i].y, box, box);
                ctx.strokeStyle = "red";
                ctx.strokeRect(snake[i].x, snake[i].y, box, box);
            }
            ctx.fillStyle = "red";
            ctx.fillRect(food.x, food.y, box, box);

            let snakeX = snake[0].x;
            let snakeY = snake[0].y;

            if (direction == "LEFT") snakeX -= box;
            if (direction == "UP") snakeY -= box;
            if (direction == "RIGHT") snakeX += box;
            if (direction == "DOWN") snakeY += box;

            if (snakeX == food.x && snakeY == food.y) {
                score++;
                updateScore();
                food = {
                    x: Math.floor(Math.random() * 19 + 1) * box,
                    y: Math.floor(Math.random() * 19 + 1) * box
                };
            } else {
                snake.pop();
            }

            let newHead = {
                x: snakeX,
                y: snakeY
            };

            if (snakeX < 0 || snakeY < 0 || snakeX >= canvas.width || snakeY >= canvas.height || collision(newHead, snake)) {
                clearInterval(game);
                document.getElementById('gameOverDisplay').style.display = 'block';
                if (<?php echo json_encode($user_id); ?>) {
                    saveScore();
                }
            } else {
                snake.unshift(newHead);
            }
        }

        function collision(head, array) {
            for (let i = 0; i < array.length; i++) {
                if (head.x == array[i].x && head.y == array[i].y) {
                    return true;
                }
            }
            return false;
        }

        function resetGame() {
            clearInterval(game);
            snake = [];
            snake[0] = {x: 9 * box, y: 10 * box};
            direction = null;
            score = 0;
            updateScore();
            food = {
                x: Math.floor(Math.random() * 19 + 1) * box,
                y: Math.floor(Math.random() * 19 + 1) * box
            };
            document.getElementById('gameOverDisplay').style.display = 'none';
            game = setInterval(draw, 100);
        }

        function updateScore() {
            document.getElementById('scoreDisplay').innerText = 'Score: ' + score;
        }

        function saveScore() {
            let xhr = new XMLHttpRequest();
            xhr.open("POST", "save_score_snake.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.send("score=" + score);
        }

        let game = setInterval(draw, 100);
    </script>
</body>
</html>
