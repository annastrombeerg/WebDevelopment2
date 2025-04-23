<?php
session_start();

//Ladda in HTML-mall
$template = file_get_contents('startpage.html');

//Meddelande om användaren precis loggade ut
$message = "";
if (isset($_GET['loggedout']) && $_GET['loggedout'] === "true") {
    $message = "<p style='color: green; font-weight: bold;'>You have successfully logged out.</p>";
}
$template = str_replace('<!--===message===-->', $message, $template);

//Lägg till "Logout"-knapp uppe till höger om inloggad
$logout = "";
if (isset($_SESSION['user_id'])) {
    $logout = '<li class="logout-right"><a href="logout.php">Logout</a></li>';
}
$template = str_replace('<!--===logout===-->', $logout, $template);

//Visa hela sidan
echo $template;
?>
