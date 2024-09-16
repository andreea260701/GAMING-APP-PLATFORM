<?php
session_start();
include 'connect.php';

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest';

if ($user_id) {
    $query = "SELECT score_pong FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $current_score_pong = $user['score_pong'];
}

$query_leaderboard = "SELECT username, score_pong FROM users ORDER BY score_pong DESC LIMIT 10";
$result_leaderboard = $conn->query($query_leaderboard);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pong</title>
    <style>
        body {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background: #000;
            color: #fff;
            font-family: Arial, sans-serif;
        }
        canvas {
            background: #000;
            display: block;
            border: 1px solid #fff;
        }
        .btn-button a, .btn-button button {
            display: inline-block;
            padding: 10px 20px;
            font-size: 16px;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            margin-top: 20px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .btn-button a:hover, .btn-button button:hover {
            background-color: #0056b3;
        }
        #game-over {
            display: none;
            text-align: center;
        }
        #game-over h2 {
            margin-bottom: 20px;
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
            background-color: black;
        }
        #leaderboard .top-score {
            background-color: #ffd700;
            color: #000;
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
    </style>
</head>
<body>
    <h1>Pong</h1>
    <canvas id="pongCanvas" width="600" height="400"></canvas>
    <div id="game-over">
        <h2>Game Over</h2>
        <p>Speed: <span id="final-speed">0</span></p>
        <button id="resetButton" onclick="resetGame()">Reset</button>
    </div>
    <div class="btn-button">
        <a href="index.php">Home</a>
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
                        <td><?php echo htmlspecialchars($row['score_pong']); ?></td>
                    </tr>
                <?php 
                    $rank++;
                endwhile; 
                ?>
            </tbody>
        </table>
    </div>
    <script>
        const canvas = document.getElementById('pongCanvas');
        const ctx = canvas.getContext('2d');
        const gameOverDiv = document.getElementById('game-over');
        const finalSpeedSpan = document.getElementById('final-speed');

        let paddleHeight = 75, paddleWidth = 10, ballRadius = 10;
        let playerY = (canvas.height - paddleHeight) / 2;
        let aiY = (canvas.height - paddleHeight) / 2;
        let upPressed = false, downPressed = false;
        let ballX, ballY, dx, dy, speed;
        let speedIncreaseInterval = 5000; 
        let speedMultiplier = 1.05; 
        let gameOver = false;

        function initGame() {
            ballX = canvas.width / 2;
            ballY = canvas.height / 2;
            dx = 2;
            dy = -2;
            speed = 1;
            gameOver = false;
            playerY = (canvas.height - paddleHeight) / 2;
            aiY = (canvas.height - paddleHeight) / 2;
            gameOverDiv.style.display = 'none';
            draw();
        }

        function drawPaddle(x, y) {
            ctx.beginPath();
            ctx.rect(x, y, paddleWidth, paddleHeight);
            ctx.fillStyle = "#fff";
            ctx.fill();
            ctx.closePath();
        }

        function drawBall() {
            ctx.beginPath();
            ctx.arc(ballX, ballY, ballRadius, 0, Math.PI * 2);
            ctx.fillStyle = "#fff";
            ctx.fill();
            ctx.closePath();
        }

        function drawGameOver() {
            ctx.font = "48px Arial";
            ctx.fillStyle = "#fff";
            ctx.textAlign = "center";
            ctx.fillText("Game Over", canvas.width / 2, canvas.height / 2 - 50);
            finalSpeedSpan.innerText = speed.toFixed(2);
            gameOverDiv.style.display = 'block';
            saveScore();
        }

        function draw() {
            if (gameOver) {
                drawGameOver();
                return;
            }

            ctx.clearRect(0, 0, canvas.width, canvas.height);
            drawPaddle(0, playerY);
            drawPaddle(canvas.width - paddleWidth, aiY);
            drawBall();

            if (ballX + dx < paddleWidth + ballRadius) {
                if (ballY > playerY && ballY < playerY + paddleHeight) {
                    dx = -dx * speedMultiplier; 
                    dy = dy * speedMultiplier;
                    speed *= speedMultiplier;
                } else if (ballX + dx < 0) {
                    gameOver = true;
                }
            } else if (ballX + dx > canvas.width - paddleWidth - ballRadius) {
                if (ballY > aiY && ballY < aiY + paddleHeight) {
                    dx = -dx * speedMultiplier; 
                    dy = dy * speedMultiplier;
                    speed *= speedMultiplier;
                } else if (ballX + dx > canvas.width) {
                    dx = -dx;
                }
            }

            if (ballY + dy < ballRadius || ballY + dy > canvas.height - ballRadius) {
                dy = -dy;
            }

            ballX += dx;
            ballY += dy;

            if (upPressed && playerY > 0) {
                playerY -= 7;
            } else if (downPressed && playerY < canvas.height - paddleHeight) {
                playerY += 7;
            }

            if (ballY < aiY + paddleHeight / 2) {
                aiY -= 7;
            } else if (ballY > aiY + paddleHeight / 2) {
                aiY += 7;
            }

            requestAnimationFrame(draw);
        }

        document.addEventListener("keydown", (e) => {
            if (["Space", "ArrowUp", "ArrowDown", "ArrowLeft", "ArrowRight"].indexOf(e.code) > -1) {
                e.preventDefault();
            }
            if (e.key == "Up" || e.key == "ArrowUp") {
                upPressed = true;
            } else if (e.key == "Down" || e.key == "ArrowDown") {
                downPressed = true;
            }
        });

        document.addEventListener("keyup", (e) => {
            if (e.key == "Up" || e.key == "ArrowUp") {
                upPressed = false;
            } else if (e.key == "Down" || e.key == "ArrowDown") {
                downPressed = false;
            }
        });

        function increaseSpeed() {
            dx *= speedMultiplier;
            dy *= speedMultiplier;
            speed *= speedMultiplier;
        }

        setInterval(increaseSpeed, speedIncreaseInterval); 

        function resetGame() {
            initGame();
        }

        function saveScore() {
            fetch('save_score_pong.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({speed: speed.toFixed(2)})
            });
        }

        initGame();
    </script>
</body>
</html>
