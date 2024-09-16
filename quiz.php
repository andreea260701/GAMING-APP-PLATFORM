
<?php
include 'connect.php';

global $conn;
?>

<h1>Quiz Game</h1>
<button class="m-lg-5" id="nextButton" onclick="getNextQuestion()">First question</button>
<div id="questionContainer"></div>
<div id="resultContainer"></div>

<script>
    var resultContainer = document.getElementById('resultContainer');
    var correctCount = 0;
    var totalQuestions = 0;
    var questions = [];

    function getQuestions() {
        $.ajax({
            url: 'get_questions.php',
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                questions = data;
                console.log(questions);
            },
            error: function (error) {
                console.error('Eroare la obținerea întrebărilor: ', error);
            }
        });
    }

    function getRandomQuestion() {
        if (questions.length === 0) {
            console.log('Toate întrebările au fost răspunse.');
            return null;
        }

        var randomIndex = Math.floor(Math.random() * questions.length);
        var randomQuestion = questions[randomIndex];
        questions.splice(randomIndex, 1);

        return randomQuestion;
    }

    function shuffleArray(array) {
        for (let i = array.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [array[i], array[j]] = [array[j], array[i]];
        }
    }

    function displayQuestion(question) {
        var questionContainer = document.getElementById('questionContainer');

        var allChoices = Object.keys(question)
            .filter(key => key.startsWith('choice'))
            .map(key => question[key]);

        allChoices.push(question.correct_answer);

        shuffleArray(allChoices);

        questionContainer.innerHTML = `
    <p>${question.question_text}</p>
    <form id="answerForm">
        ${allChoices.map((choice, index) => `
            <div>
                <input type="checkbox" id="choice${index}" name="choices" value="${choice}">
                <label for="choice${index}">${choice}</label>
            </div>
        `).join('')}
        <button type="button" onclick="checkAnswer()">Verifică răspunsul</button>
        <p id="result"></p>
    </form>`;

        questionContainer.style.display = 'block';

        window.checkAnswer = function () {
            var selectedAnswers = document.querySelectorAll('input[name="choices"]:checked');
            var selectedChoices = Array.from(selectedAnswers).map(answer => answer.value);

            var correctChoices = allChoices.filter(choice => choice === question.correct_answer);
            var isCorrect = selectedChoices.length === correctChoices.length && selectedChoices.every(choice => correctChoices.includes(choice));

            if (isCorrect) {
                correctCount++;
                document.getElementById('result').innerText = "Răspuns corect!";
            } else {
                document.getElementById('result').innerText = "Răspuns greșit. Răspunsul corect era: " + question.correct_answer;
            }

            totalQuestions++;

            getNextQuestion();
        };
    }

    var isFirstClick = true;
    function getNextQuestion() {
        var randomQuestion = getRandomQuestion();
        if (randomQuestion !== null) {
            displayQuestion(randomQuestion);

            if (isFirstClick) {
                document.getElementById('nextButton').style.display = 'none';
            }
        } else {
            resultContainer.innerHTML = `
        <p>Număr total de întrebări: ${totalQuestions}</p>
        <p>Număr de raspunsuri corecte: ${correctCount}</p>`;

            document.getElementById('questionContainer').style.display = 'none';

            saveScore(correctCount);
        }
    }

    function saveScore(score) {
        $.ajax({
            url: 'save_score.php',
            type: 'POST',
            data: { score: score },
            success: function (response) {
                console.log(response);
            },
            error: function (error) {
                console.error('Eroare la salvarea scorului: ', error);
            }
        });
    }

    $(document).ready(function () {
        getQuestions();
    });
</script>
    <?php
    $servername = 'localhost';
    $username = 'root';
    $password = '';
    $database = "quizdb";
    $conn = new mysqli($servername, $username, $password, $database);
    if ($conn->connect_error) {
      die("Conexiunea la baza de date a eșuat: " . $conn->connect_error);
    }
    $sql = "SELECT * FROM users ORDER BY score DESC";
    $result = $conn->query($sql);
    ?>

    <section>
      <h1>Scoreboard</h1>
      <div class="container">
        <table class="table">
          <thead>
            <tr>
              <th>Nume</th>
              <th>Score</th>
            </tr>
          </thead>
          <tbody>
            <?php
            if ($result->num_rows > 0) {
              while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['nume'] . "</td>";
                echo "<td>" . $row['score'] . "</td>";
                echo "</tr>";
              }
            } else {
              echo "<tr><td colspan='12'>Nu există utilizatori în baza de date.</td></tr>";
            }
            ?>
          </tbody>
        </table>
      </div>
    </section>
    