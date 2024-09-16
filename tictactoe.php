
<div id="tictactoe-game">
    <div id="board"></div>
    <button id="resetButton" onclick="initGame()">Reset Game</button>
</div>
<style>
    #tictactoe-game {
        display: flex;
        flex-direction: column;
        align-items: center;
        margin-top: 20px;
    }
    #board {
        display: grid;
        grid-template-columns: repeat(3, 100px);
        grid-gap: 5px;
        margin: 20px 0;
    }
    .cell {
        width: 100px;
        height: 100px;
        background-color: #eee;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 36px;
        cursor: pointer;
        border: 1px solid #000;
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
<script>
    let board;
    let currentPlayer;

    function initGame() {
        board = ['', '', '', '', '', '', '', '', ''];
        currentPlayer = 'X';
        document.getElementById('board').innerHTML = '';
        for (let i = 0; i < 9; i++) {
            let cell = document.createElement('div');
            cell.className = 'cell';
            cell.id = 'cell-' + i;
            cell.addEventListener('click', () => makeMove(i));
            document.getElementById('board').appendChild(cell);
        }
    }

    function makeMove(index) {
        if (board[index] === '') {
            board[index] = currentPlayer;
            document.getElementById('cell-' + index).innerText = currentPlayer;
            if (checkWin()) {
                alert(currentPlayer + ' wins!');
                initGame();
            } else if (board.every(cell => cell !== '')) {
                alert('Draw!');
                initGame();
            } else {
                currentPlayer = currentPlayer === 'X' ? 'O' : 'X';
            }
        }
    }

    function checkWin() {
        const winPatterns = [
            [0, 1, 2], [3, 4, 5], [6, 7, 8],
            [0, 3, 6], [1, 4, 7], [2, 5, 8],
            [0, 4, 8], [2, 4, 6]
        ];
        return winPatterns.some(pattern => {
            return pattern.every(index => board[index] === currentPlayer);
        });
    }

    document.addEventListener('DOMContentLoaded', initGame);
</script>
