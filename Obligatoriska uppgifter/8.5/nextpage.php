<?php
/**
 * Tar emot session-id från GET-, POST- eller COOKIE-parametrar och visar det på sidan.
 * Först försöker skriptet ta emot sessions-id från URL-parametrarna (GET), formulärdata (POST) eller från en säker kaka (COOKIE).
 * Visar mottagna session-idet.
 */

//Kontrollera om säker anslutning används (HTTPS)
if ($_SERVER['HTTPS'] !== 'on') {
    die("<h2>Den här sidan kräver en säker anslutning (HTTPS).</h2>");
}

//Ta emot session-id från GET- eller POST-parametrarna
if (isset($_GET['session-id'])) {
    $session_id = $_GET['session-id']; //Från URL
} elseif (isset($_POST['session-id'])) {
    $session_id = $_POST['session-id']; //Från formulär
} elseif (isset($_COOKIE['session-id'])) {
    $session_id = $_COOKIE['session-id']; //Från kaka
} else {
    $session_id = 'Ingen session-id mottogs.';
}

//Visa informationen
echo '<h1>Information</h1>';

if (isset($_GET['name'])) {
    echo '<p>Namn: ' . htmlspecialchars($_GET['name']) . '</p>';
}

echo '<p>Session ID: ' . htmlspecialchars($session_id) . '</p>';
?>
