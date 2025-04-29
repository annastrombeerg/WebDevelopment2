<?php
/**
 * Hanterar inloggning för kund till e-handelssystemet.
 * Kontrollerar inmatad e-post och lösenord mot databasen och loggar in kunden om uppgifterna stämmer.
 * Visar även meddelanden vid felaktiga inloggningsförsök eller om registreringen lyckades.
 */

session_start();
$conn = new mysqli("localhost", "root", "", "E_Commerce_db");
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

//Ladda HTML-mallen
$template = file_get_contents('login.html');

//Visa logout om användaren är inloggad
$logout = "";
if (isset($_SESSION['user_id'])) {
    $logout = '<li class="logout-right"><a href="logout.php">Logout</a></li>';
}
$template = str_replace("<!--===logout===-->", $logout, $template);

//Kontrollera om användaren redan är inloggad
if (isset($_SESSION['user_id'])) {
    header("Location: startpage.php?msg=already_logged_in"); //Redirect till startsidan om användaren är inloggad
    exit();
}

//Visa eventuella meddelanden
$message = "";
if (isset($_GET['registered']) && $_GET['registered'] === "true") {
    $message = "<p>Registration successful! You can now log in.</p>";
}
//Hantera formulärdata
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $password = $_POST["password"];
    //Hämta kundinformation från databasen baserat på e-post
    $sql = "SELECT id, username, password FROM user WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    //Kontrollera om kund hittades
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $username, $hashed_password);
        $stmt->fetch();
        //Verifiera lösenord
        if (password_verify($password, $hashed_password)) {
            $_SESSION["user_id"] = $user_id;
            $_SESSION["username"] = $username;
            header("Location: startpage.php");
            exit();
        } else {
            $message = <<<EOD
            <p>Wrong password!</p>
            EOD;
        }
    } else {
        $message = <<<EOD
        <p>User not found!</p>
        EOD;
    }
    $stmt->close();
}
$conn->close();

//Ersätt placeholdern med felmeddelande (eller tom)
$template = str_replace("<!--===loginmessage===-->", $message, $template);

//Skriv ut hela sidan
echo $template;
?>