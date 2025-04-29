<?php
/**
 * Visar startsidan för e-handeln. Hälsar kunden välkommen och visar eventuella meddelanden
 * beroende på om kunden är inloggad, redan inloggad eller precis har loggat ut.
 */

session_start();

//Ladda in HTML-mall
$template = file_get_contents('startpage.html');

//Sätt välkomstmeddelande beroende på om kund är inloggad
$welcome_message = "Welcome to Anna E-Commerce LLC";
if (isset($_SESSION['username'])) {
    $name = htmlspecialchars($_SESSION['username']);
    $welcome_message = "Welcome <strong>$name</strong> to Anna E-Commerce LLC";
}
$template = str_replace("<!--===welcomeuser===-->", $welcome_message, $template);

//Meddelande om användaren precis loggade ut eller om användaren är redan inloggad
$message = "";
if (isset($_GET['loggedout']) && $_GET['loggedout'] === "true") {
    $message = "<p style='color: green; font-weight: bold;'>You have successfully logged out.</p>";
}
if (isset($_GET['msg']) && $_GET['msg'] === "already_logged_in") {
    $message = "<p style='color: blue; font-weight: bold;'>You are already logged in!</p>";
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
