
<div id="memorygame-board"></div>
<style>
    #memorygame-board {
        display: grid;
        grid-template-columns: repeat(4, 100px);
        grid-gap: 10px;
        margin: 20px auto;
        width: 440px;
    }
    .memorygame-card {
        width: 100px;
        height: 100px;
        background-color: #aaa;
        cursor: pointer;
        text-align: center;
        line-height: 100px;
        font-size: 24px;
        color: transparent;
    }
    .memorygame-flipped, .memorygame-matched {
        color: #000;
        background-color: #fff;
    }
</style>
<script>
    var memoryGameCards = ['A', 'A', 'B', 'B', 'C', 'C', 'D', 'D', 'E', 'E', 'F', 'F', 'G', 'G', 'H', 'H'];
    var memoryGameFirstCard, memoryGameSecondCard;
    var memoryGameLockBoard = false;

    function initMemoryGame() {
        var board = document.getElementById('memorygame-board');
        board.innerHTML = '';
        shuffle(memoryGameCards).forEach(card => {
            var cardElement = document.createElement('div');
            cardElement.className = 'memorygame-card';
            cardElement.dataset.value = card;
            cardElement.addEventListener('click', flipMemoryGameCard);
            board.appendChild(cardElement);
        });
    }

    function flipMemoryGameCard() {
        if (memoryGameLockBoard) return;
        if (this === memoryGameFirstCard) return;

        this.classList.add('memorygame-flipped');
        this.innerText = this.dataset.value;

        if (!memoryGameFirstCard) {
            memoryGameFirstCard = this;
            return;
        }

        memoryGameSecondCard = this;
        checkMemoryGameMatch();
    }

    function checkMemoryGameMatch() {
        var isMatch = memoryGameFirstCard.dataset.value === memoryGameSecondCard.dataset.value;
        isMatch ? disableMemoryGameCards() : unflipMemoryGameCards();
    }

    function disableMemoryGameCards() {
        memoryGameFirstCard.removeEventListener('click', flipMemoryGameCard);
        memoryGameSecondCard.removeEventListener('click', flipMemoryGameCard);
        resetMemoryGameBoard();
    }

    function unflipMemoryGameCards() {
        memoryGameLockBoard = true;
        setTimeout(() => {
            memoryGameFirstCard.classList.remove('memorygame-flipped');
            memoryGameSecondCard.classList.remove('memorygame-flipped');
            memoryGameFirstCard.innerText = '';
            memoryGameSecondCard.innerText = '';
            resetMemoryGameBoard();
        }, 1500);
    }

    function resetMemoryGameBoard() {
        [memoryGameFirstCard, memoryGameSecondCard, memoryGameLockBoard] = [null, null, false];
    }

    function shuffle(array) {
        for (let i = array.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [array[i], array[j]] = [array[j], array[i]];
        }
        return array;
    }

    document.addEventListener('DOMContentLoaded', initMemoryGame);
</script>
