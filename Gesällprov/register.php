<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
//Anslut till databasen
$conn = new mysqli("localhost", "root", "", "E_Commerce_db");
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = password_hash($_POST["password"], PASSWORD_BCRYPT);

    //Kolla om e-post redan används
    $check_sql = "SELECT id FROM user WHERE email = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("s", $email);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows > 0) {
        echo "Email already registered!";
    } else {
        $sql = "INSERT INTO user (username, email, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $username, $email, $password);

        if ($stmt->execute()) {
            header("Location: login.html?registered=true"); //Redirect till login
            exit();
        } else {
            echo "Error during registration: " . $stmt->error;
        }
        $stmt->close();
    }
    $check_stmt->close();
}
$conn->close();
?>
