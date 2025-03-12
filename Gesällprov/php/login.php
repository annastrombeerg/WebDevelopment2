<?php
session_start();
$conn = new mysqli("localhost", "root", "", "E_Commerce_db");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user["password"])) {
            $_SESSION["user_id"] = $user["id"];
            header("Location: index.php");
        } else {
            echo "Wrong password!";
        }
    } else {
        echo "The user do not exist!";
    }
}
?>
