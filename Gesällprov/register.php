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

//Ladda HTML-mallen
$template = file_get_contents('register.html');

//Visa logout om användaren är inloggad
$logout = "";
if (isset($_SESSION['user_id'])) {
    $logout = '<li class="logout-right"><a href="logout.php">Logout</a></li>';
}
$template = str_replace("<!--===logout===-->", $logout, $template);

$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = htmlspecialchars(trim($_POST["username"]));
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $password = password_hash($_POST["password"], PASSWORD_BCRYPT);

    //Kolla om e-post redan används
    $check_sql = "SELECT id FROM user WHERE email = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("s", $email);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows > 0) {
        $message = <<<EOD
        <p>This email is already registered.</p>
        EOD;
    } else {
        $sql = "INSERT INTO user (username, email, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $username, $email, $password);

        if ($stmt->execute()) {
            header("Location: login.php?registered=true"); //Redirect till login
            exit();
        } else {
            $message = <<<EOD
            <p>Error during registration. Please try again.</p>
            EOD;
        }
        $stmt->close();
    }
    $check_stmt->close();
}
$conn->close();

$template = str_replace("<!--===registermessage===-->", $message, $template);

//Visa sidan
echo $template;
?>
