<?php
session_start();
$conn = new mysqli("localhost", "root", "", "E_Commerce_db");

//Kontrollera om användaren redan är inloggad
if (isset($_SESSION['user_id'])) {
    header("Location: startpage.php"); //Redirect till startsidan om användaren är inloggad
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //Rensa och hämta inloggningsuppgifter från formuläret
    $email = $conn->real_escape_string($_POST["email"]);
    $password = $_POST["password"];

    //Förbereda och köra SQL-fråga för att hämta användaren
    $sql = "SELECT id, password FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email); //binda email-parametern
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        //Om användaren finns, kontrollera lösenordet
        $stmt->bind_result($user_id, $hashed_password);
        $stmt->fetch();
        if (password_verify($password, $hashed_password)) {
            //Om lösenordet är korrekt, spara användarens id i sessionen
            $_SESSION["user_id"] = $user_id;
            header("Location: startpage.php"); //Redirect till startsidan
            exit();
        } else {
            //Om lösenordet är felaktigt
            $error_message = "Wrong Password!";
        }
    } else {
        //Om användaren inte finns
        $error_message = "The user doesn't exist!";
    }
}
?>