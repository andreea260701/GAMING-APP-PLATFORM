<?php
session_start();
include 'connect.php';

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest';

if ($user_id) {
    $query = "SELECT score_tetris FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $current_score_tetris = $user['score_tetris'];
}

$query_leaderboard = "SELECT username, score_tetris FROM users ORDER BY score_tetris DESC LIMIT 10";
$result_leaderboard = $conn->query($query_leaderboard);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tetris</title>
    <style>
        body {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background: #222;
            color: #fff;
        }
        canvas {
            background: #fff;
            display: block;
            border: 5px solid #555;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
        }
        #score {
            color: #fff;
            font-size: 24px;
            margin-bottom: 20px;
            margin-top: 80px;
        }
        #tetris-game {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 20px;
        }
        #goHome {
            margin-top: 10px;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
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
            background-color: black;
        }
        #leaderboard tr:hover {
            background-color: black;
        }
        #leaderboard .top-score {
            background-color: #ffd700;
            color: #000;
        }
    </style>
</head>
<body>
    <div id="tetris-game">
        <div id="score">Score: 0</div>
        <canvas id="tetris" width="240" height="400"></canvas>
        <button id="resetButton" onclick="resetGame()">Reset</button>
        <div id="gameOverDisplay">Game Over</div>
        <button id="goHome" onclick="window.location.href='index.php'">Home</button>
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
                        <td><?php echo htmlspecialchars($row['score_tetris']); ?></td>
                    </tr>
                <?php 
                    $rank++;
                endwhile; 
                ?>
            </tbody>
        </table>
    </div>

    <script>
        console.log("Script loaded");

        const canvas = document.getElementById('tetris');
        const context = canvas.getContext('2d');
        context.scale(20, 20);

        function arenaSweep() {
            console.log("arenaSweep called");
            let rowCount = 1;
            outer: for (let y = arena.length - 1; y > 0; --y) {
                for (let x = 0; x < arena[y].length; ++x) {
                    if (arena[y][x] === 0) {
                        continue outer;
                    }
                }

                const row = arena.splice(y, 1)[0].fill(0);
                arena.unshift(row);
                ++y;

                player.score += rowCount * 10;
                rowCount *= 2;
            }
        }

        function collide(arena, player) {
            console.log("collide called");
            const [m, o] = [player.matrix, player.pos];
            for (let y = 0; y < m.length; ++y) {
                for (let x = 0; x < m[y].length; ++x) {
                    if (m[y][x] !== 0 &&
                       (arena[y + o.y] &&
                        arena[y + o.y][x + o.x]) !== 0) {
                        return true;
                    }
                }
            }
            return false;
        }

        function createMatrix(w, h) {
            console.log("createMatrix called");
            const matrix = [];
            while (h--) {
                matrix.push(new Array(w).fill(0));
            }
            return matrix;
        }

        function createPiece(type) {
            console.log("createPiece called with type:", type);
            if (type === 'T') {
                return [
                    [0, 0, 0],
                    [1, 1, 1],
                    [0, 1, 0],
                ];
            } else if (type === 'O') {
                return [
                    [2, 2],
                    [2, 2],
                ];
            } else if (type === 'L') {
                return [
                    [0, 3, 0],
                    [0, 3, 0],
                    [0, 3, 3],
                ];
            } else if (type === 'J') {
                return [
                    [0, 4, 0],
                    [0, 4, 0],
                    [4, 4, 0],
                ];
            } else if (type === 'I') {
                return [
                    [0, 5, 0, 0],
                    [0, 5, 0, 0],
                    [0, 5, 0, 0],
                    [0, 5, 0, 0],
                ];
            } else if (type === 'S') {
                return [
                    [0, 6, 6],
                    [6, 6, 0],
                    [0, 0, 0],
                ];
            } else if (type === 'Z') {
                return [
                    [7, 7, 0],
                    [0, 7, 7],
                    [0, 0, 0],
                ];
            }
        }

        function drawMatrix(matrix, offset) {
            console.log("drawMatrix called");
            matrix.forEach((row, y) => {
                row.forEach((value, x) => {
                    if (value !== 0) {
                        context.fillStyle = colors[value];
                        context.fillRect(x + offset.x,
                                         y + offset.y,
                                         1, 1);
                    }
                });
            });
        }

        function draw() {
            console.log("draw called");
            context.fillStyle = '#000';
            context.fillRect(0, 0, canvas.width, canvas.height);

            drawMatrix(arena, {x: 0, y: 0});
            drawMatrix(player.matrix, player.pos);
        }

        function merge(arena, player) {
            console.log("merge called");
            player.matrix.forEach((row, y) => {
                row.forEach((value, x) => {
                    if (value !== 0) {
                        arena[y + player.pos.y][x + player.pos.x] = value;
                    }
                });
            });
        }

        function rotate(matrix, dir) {
            console.log("rotate called");
            for (let y = 0; y < matrix.length; ++y) {
                for (let x = 0; x < y; ++x) {
                    [
                        matrix[x][y],
                        matrix[y][x],
                    ] = [
                        matrix[y][x],
                        matrix[x][y],
                    ];
                }
            }

            if (dir > 0) {
                matrix.forEach(row => row.reverse());
            } else {
                matrix.reverse();
            }
        }

        function playerDrop() {
            console.log("playerDrop called");
            player.pos.y++;
            if (collide(arena, player)) {
                player.pos.y--;
                merge(arena, player);
                playerReset();
                arenaSweep();
                updateScore();
            }
            dropCounter = 0;
        }

        function playerMove(dir) {
            console.log("playerMove called with dir:", dir);
            player.pos.x += dir;
            if (collide(arena, player)) {
                player.pos.x -= dir;
            }
        }

        function playerReset() {
            console.log("playerReset called");
            const pieces = 'TJLOSZI';
            player.matrix = createPiece(pieces[pieces.length * Math.random() | 0]);
            player.pos.y = 0;
            player.pos.x = (arena[0].length / 2 | 0) -
                           (player.matrix[0].length / 2 | 0);
            if (collide(arena, player)) {
                arena.forEach(row => row.fill(0));
                saveScore(player.score);
                player.score = 0;
                updateScore();
                document.getElementById('gameOverDisplay').style.display = 'block';
            }
        }

        function playerRotate(dir) {
            console.log("playerRotate called with dir:", dir);
            const pos = player.pos.x;
            let offset = 1;
            rotate(player.matrix, dir);
            while (collide(arena, player)) {
                player.pos.x += offset;
                offset = -(offset + (offset > 0 ? 1 : -1));
                if (offset > player.matrix[0].length) {
                    rotate(player.matrix, -dir);
                    player.pos.x = pos;
                    return;
                }
            }
        }

        let dropCounter = 0;
        let dropInterval = 1000;

        let lastTime = 0;
        function update(time = 0) {
            console.log("update called");
            const deltaTime = time - lastTime;
            lastTime = time;

            dropCounter += deltaTime;
            if (dropCounter > dropInterval) {
                playerDrop();
            }

            draw();
            requestAnimationFrame(update);
        }

        function updateScore() {
            console.log("updateScore called");
            document.getElementById('score').innerText = "Score: " + player.score;
        }

        function saveScore(score) {
            fetch('save_score_tetris.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({score: score})
            });
        }

        const colors = [
            null,
            '#FF0D72',
            '#0DC2FF',
            '#0DFF72',
            '#F538FF',
            '#FF8E0D',
            '#FFE138',
            '#3877FF',
        ];

        const arena = createMatrix(12, 20);

        const player = {
            pos: {x: 0, y: 0},
            matrix: null,
            score: 0,
        };

        document.addEventListener('keydown', event => {
            console.log("keydown event:", event.keyCode);
            if ([37, 38, 39, 40].includes(event.keyCode)) {
                event.preventDefault();
            }
            if (event.keyCode === 37) {
                playerMove(-1);
            } else if (event.keyCode === 39) {
                playerMove(1);
            } else if (event.keyCode === 40) {
                playerDrop();
            } else if (event.keyCode === 81) {
                playerRotate(-1);
            } else if (event.keyCode === 87) {
                playerRotate(1);
            }
        });

        function resetGame() {
            playerReset();
            document.getElementById('gameOverDisplay').style.display = 'none';
        }

        playerReset();
        updateScore();
        update();
    </script>
</body>
</html>
