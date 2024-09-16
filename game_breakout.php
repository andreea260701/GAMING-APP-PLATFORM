<?php
session_start();
include 'connect.php';

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest';

if ($user_id) {
    $query = "SELECT score_breakout FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $current_score_breakout = $user['score_breakout'];
}

$query_leaderboard = "SELECT username, score_breakout FROM users ORDER BY score_breakout DESC LIMIT 10";
$result_leaderboard = $conn->query($query_leaderboard);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Breakout</title>
    <style>
        body {
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            align-items: center;
            height: 100vh;
            margin: 0;
            background: #000;
            color: #fff;
            font-family: Arial, sans-serif;
        }
        #breakout-game {
            margin-top: 50px;
            display: flex;
            flex-direction: column;
            align-items: center;
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
    <div id="breakout-game">
        <h1>Breakout</h1>
        <canvas id="breakoutCanvas" width="800" height="600"></canvas>
        <div id="game-over">
            <h2>Game Over</h2>
            <p>Score: <span id="final-score">0</span></p>
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
                            <td><?php echo htmlspecialchars($row['score_breakout']); ?></td>
                        </tr>
                    <?php 
                        $rank++;
                    endwhile; 
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <script>
        const canvas = document.getElementById('breakoutCanvas');
        const ctx = canvas.getContext('2d');
        const gameOverDiv = document.getElementById('game-over');
        const finalScoreSpan = document.getElementById('final-score');

        let ballRadius = 10;
        let x, y, dx, dy;
        let paddleHeight = 10, paddleWidth = 100, paddleX;
        let rightPressed = false, leftPressed = false;
        let brickRowCount = 5, brickColumnCount = 10;
        let brickWidth = (canvas.width - (brickColumnCount - 1) * 10) / brickColumnCount; 
        let brickHeight = 20, brickPadding = 10;
        let brickOffsetTop = 30, brickOffsetLeft = 0;
        let bricks = [];
        let score = 0;
        let gameOver = false;
        let speedIncrement = 0.1;

        for (let c = 0; c < brickColumnCount; c++) {
            bricks[c] = [];
            for (let r = 0; r < brickRowCount; r++) {
                bricks[c][r] = { x: 0, y: 0, status: 1 };
            }
        }

        function initGame() {
            x = canvas.width / 2;
            y = canvas.height - 30;
            dx = 3 * (Math.random() > 0.5 ? 1 : -1); 
            dy = -3 * (Math.random() > 0.5 ? 1 : -1); 
            paddleX = (canvas.width - paddleWidth) / 2;
            score = 0;
            gameOver = false;
            for (let c = 0; c < brickColumnCount; c++) {
                for (let r = 0; r < brickRowCount; r++) {
                    bricks[c][r].status = 1;
                }
            }
            gameOverDiv.style.display = 'none';
            draw();
        }

        function drawBall() {
            ctx.beginPath();
            ctx.arc(x, y, ballRadius, 0, Math.PI * 2);
            ctx.fillStyle = "#fff";
            ctx.fill();
            ctx.closePath();
        }

        function drawPaddle() {
            ctx.beginPath();
            ctx.rect(paddleX, canvas.height - paddleHeight, paddleWidth, paddleHeight);
            ctx.fillStyle = "#fff";
            ctx.fill();
            ctx.closePath();
        }

        function drawBricks() {
            for (let c = 0; c < brickColumnCount; c++) {
                for (let r = 0; r < brickRowCount; r++) {
                    if (bricks[c][r].status == 1) {
                        let brickX = c * (brickWidth + brickPadding) + brickOffsetLeft;
                        let brickY = r * (brickHeight + brickPadding) + brickOffsetTop;
                        bricks[c][r].x = brickX;
                        bricks[c][r].y = brickY;
                        ctx.beginPath();
                        ctx.rect(brickX, brickY, brickWidth, brickHeight);
                        ctx.fillStyle = getBrickColor(r);
                        ctx.fill();
                        ctx.closePath();
                    }
                }
            }
        }

        function getBrickColor(row) {
            const colors = ["#f00", "#ff0", "#0f0", "#0ff", "#00f"];
            return colors[row % colors.length];
        }

        function drawScore() {
            ctx.font = "16px Arial";
            ctx.fillStyle = "#fff";
            ctx.fillText("Score: " + score, canvas.width / 2 - 40, 20);
        }

        function drawGameOver() {
            ctx.font = "48px Arial";
            ctx.fillStyle = "#fff";
            ctx.textAlign = "center";
            ctx.fillText("Game Over", canvas.width / 2, canvas.height / 2 - 50);
            finalScoreSpan.innerText = score;
            gameOverDiv.style.display = 'block';
            saveScore(score);
        }

        function collisionDetection() {
            for (let c = 0; c < brickColumnCount; c++) {
                for (let r = 0; r < brickRowCount; r++) {
                    let b = bricks[c][r];
                    if (b.status == 1) {
                        if (x > b.x && x < b.x + brickWidth && y > b.y && y < b.y + brickHeight) {
                            dy = -dy;
                            b.status = 0;
                            score++;
                            if (score == brickRowCount * brickColumnCount) {
                                alert("YOU WIN, CONGRATS!");
                                document.location.reload();
                            }
                        }
                    }
                }
            }
        }

        function draw() {
            if (gameOver) {
                drawGameOver();
                return;
            }

            ctx.clearRect(0, 0, canvas.width, canvas.height);
            drawBricks();
            drawBall();
            drawPaddle();
            drawScore();
            collisionDetection();

            if (x + dx > canvas.width - ballRadius || x + dx < ballRadius) {
                dx = -dx;
            }

            if (y + dy < ballRadius) {
                dy = -dy;
            } else if (y + dy > canvas.height - ballRadius) {
                if (x > paddleX && x < paddleX + paddleWidth) {
                    dy = -dy;
                    if (dx > 0) {
                        dx += speedIncrement;
                    } else {
                        dx -= speedIncrement;
                    }
                    if (dy > 0) {
                        dy += speedIncrement;
                    } else {
                        dy -= speedIncrement;
                    }
                } else {
                    gameOver = true;
                }
            }

            x += dx;
            y += dy;

            if (rightPressed && paddleX < canvas.width - paddleWidth) {
                paddleX += 7;
            } else if (leftPressed && paddleX > 0) {
                paddleX -= 7;
            }

            requestAnimationFrame(draw);
        }

        document.addEventListener("keydown", keyDownHandler, false);
        document.addEventListener("keyup", keyUpHandler, false);

        function keyDownHandler(e) {
            if (e.key == "Right" || e.key == "ArrowRight") {
                rightPressed = true;
            } else if (e.key == "Left" || e.key == "ArrowLeft") {
                leftPressed = true;
            }
        }

        function keyUpHandler(e) {
            if (e.key == "Right" || e.key == "ArrowRight") {
                rightPressed = false;
            } else if (e.key == "Left" || e.key == "ArrowLeft") {
                leftPressed = false;
            }
        }

        function resetGame() {
            initGame();
        }

        function saveScore(score) {
            fetch('save_score_breakout.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({score: score})
            });
        }

        initGame();
        window.addEventListener("keydown", function(e) {
         
            if(["Space", "ArrowUp", "ArrowDown", "ArrowLeft", "ArrowRight"].indexOf(e.code) > -1) {
                e.preventDefault();
            }
        }, false);

    </script>
</body>
</html>
