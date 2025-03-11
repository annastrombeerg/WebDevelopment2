<?php
/**
 * Tar emot session-id från GET-, POST- eller COOKIE-parametrar och visar det på sidan.
 * Först försöker skriptet ta emot sessions-id från URL-parametrarna (GET), formulärdata (POST) eller från en kaka (COOKIE).
 * Visar mottagna session-idet.
 */

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
