<?php
session_start(); 


if ($_SERVER["REQUEST_METHOD"] == "POST") {
   
    $email = $_POST["email"];
    $password = $_POST["password"];

   
    $servername = "localhost";
    $dbusername = "root";
    $dbpassword = "";
    $dbname = "quizdb";

    $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

    if ($conn->connect_error) {
        die("Conexiunea la baza de date a eșuat: " . $conn->connect_error);
    }

  
    $query = "SELECT * FROM users WHERE email=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

      
        if (password_verify($password, $row["parola"])) {
            
            $_SESSION["user_id"] = $row["id"]; 
            $_SESSION["username"] = $row["username"];
            $_SESSION["status"] = $row["status"];

            echo '<script>alert("Logare reușită!");</script>';
            header("Location: index.php");
            exit();
        } else {
            echo '<script>alert("Eroare: Parolă incorectă.");</script>';
            echo '<script>window.location = "login.php";</script>';
        }
    } else {
        echo '<script>alert("Eroare: Utilizatorul nu există.");</script>';
        echo '<script>window.location = "login.php";</script>';
    }

    $conn->close();
}
?>
