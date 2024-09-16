<?php
session_start();
include 'connect.php';

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest';

if ($user_id) {
    $query = "SELECT score_flappy FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $current_score_flappy = $user['score_flappy'];
}

$query_leaderboard = "SELECT username, score_flappy FROM users ORDER BY score_flappy DESC LIMIT 10";
$result_leaderboard = $conn->query($query_leaderboard);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flappy Bird</title>
    <style>
        body {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background: #70c5ce;
            font-family: Arial, sans-serif;
        }
        canvas {
            background: #fff;
            display: block;
            border: 1px solid #000;
            margin-top: 20px;
        }
        #score {
            color: #fff;
            font-size: 24px;
            margin-top: 120px;
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
        #resetButton {
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
        #leaderboard .top-score {
            background-color: #ffd700;
            color: #000;
        }
    </style>
</head>
<body>
    <div id="score">Score: 0</div>
    <canvas id="flappyBird" width="320" height="480"></canvas>
    <div class="btn-button">
        <button id="resetButton" onclick="resetGame()">Restart Game</button>
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
                        <td><?php echo htmlspecialchars($row['score_flappy']); ?></td>
                    </tr>
                <?php 
                    $rank++;
                endwhile; 
                ?>
            </tbody>
        </table>
    </div>

    <script>
        const canvas = document.getElementById('flappyBird');
        const context = canvas.getContext('2d');

        const bird = {
            x: 50,
            y: 150,
            width: 20,
            height: 20,
            gravity: 0.4,
            lift: -10,
            velocity: 0
        };

        const pipes = [];
        const pipeWidth = 20;
        const pipeGap = 150;

        let score = 0;
        let isGameOver = false;

        document.addEventListener('keydown', event => {
            if (["Space", "ArrowUp", "ArrowDown", "ArrowLeft", "ArrowRight"].indexOf(event.code) > -1) {
                event.preventDefault();
            }
            if (event.code === "Space" || event.keyCode === 32) {
                if (!isGameOver) {
                    bird.velocity = bird.lift;
                }
            }
        });

        function drawBird() {
            context.fillStyle = '#ff0';
            context.fillRect(bird.x, bird.y, bird.width, bird.height);
        }

        function drawPipes() {
            context.fillStyle = '#0f0';
            pipes.forEach(pipe => {
                context.fillRect(pipe.x, 0, pipeWidth, pipe.top);
                context.fillRect(pipe.x, canvas.height - pipe.bottom, pipeWidth, pipe.bottom);
            });
        }

        function updateBird() {
            bird.velocity += bird.gravity;
            bird.y += bird.velocity;

            if (bird.y + bird.height > canvas.height || bird.y < 0) {
                isGameOver = true;
                showGameOver();
            }
        }

        function updatePipes() {
            if (pipes.length === 0 || pipes[pipes.length - 1].x < canvas.width - 200) {
                const top = Math.random() * (canvas.height - pipeGap);
                const bottom = canvas.height - top - pipeGap;
                pipes.push({ x: canvas.width, top, bottom });
            }

            pipes.forEach(pipe => {
                pipe.x -= 1.5;
                if (pipe.x + pipeWidth < 0) {
                    pipes.shift();
                    score++;
                    document.getElementById('score').innerText = `Score: ${score}`;
                }

                if (bird.x < pipe.x + pipeWidth &&
                    bird.x + bird.width > pipe.x &&
                    (bird.y < pipe.top || bird.y + bird.height > canvas.height - pipe.bottom)) {
                    isGameOver = true;
                    showGameOver();
                }
            });
        }

        function showGameOver() {
            document.getElementById('resetButton').style.display = 'block';
            context.fillStyle = '#000';
            context.font = '24px Arial';
            context.fillText('Game Over', canvas.width / 2 - 50, canvas.height / 2);
            saveScore();
        }

        function gameLoop() {
            if (!isGameOver) {
                context.clearRect(0, 0, canvas.width, canvas.height);
                drawBird();
                drawPipes();
                updateBird();
                updatePipes();
                requestAnimationFrame(gameLoop);
            } else {
                context.fillStyle = '#000';
                context.font = '24px Arial';
                context.fillText('Game Over', canvas.width / 2 - 50, canvas.height / 2);
            }
        }

        function resetGame() {
            bird.y = 150;
            bird.velocity = 0;
            pipes.length = 0;
            score = 0;
            document.getElementById('score').innerText = `Score: ${score}`;
            document.getElementById('resetButton').style.display = 'none';
            isGameOver = false;
            gameLoop();
        }

        function saveScore() {
            fetch('save_score_flappy.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({score: score})
            });
        }

        gameLoop();
    </script>
</body>
</html>
