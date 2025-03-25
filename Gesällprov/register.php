<?php
//Anslut till databasen
$conn = new mysqli("localhost", "root", "", "E_Commerce_db");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = password_hash($_POST["password"], PASSWORD_BCRYPT);

    $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";
    if ($conn->query($sql)) {
        echo "Registration was successful!";
    } else {
        echo "Error during registration: " . $conn->error;
    }
}
?>
