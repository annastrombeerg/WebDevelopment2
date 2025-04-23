<?php
session_start();
$conn = new mysqli("localhost", "root", "", "E_Commerce_db");
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

//Kontrollera om användaren redan är inloggad
if (isset($_SESSION['user_id'])) {
    header("Location: startpage.html"); //Redirect till startsidan om användaren är inloggad
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    $sql = "SELECT id, password FROM user WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION["user_id"] = $user_id;
            header("Location: startpage.html");
            exit();
        } else {
            $error_message = "Wrong password!";
        }
    } else {
        $error_message = "User not found!";
    }

    $stmt->close();
}
$conn->close();
?>