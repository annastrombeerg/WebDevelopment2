<?php
/**
 * Detta skript tar emot användarens input från formulär eller länkar och visar informationen.
 * Det tar också emot sessions-id från $_SESSION och visar det på sidan.
 */

//Starta sessionen för att komma åt session-id
session_start();

//Ta emot användarens namn från GET- eller POST-parameter
$name = isset($_GET['name']) ? $_GET['name'] : 'Ingen namn mottogs';

//Hämta sessions-id från $_SESSION
$session_id = isset($_SESSION['session-id']) ? $_SESSION['session-id'] : 'Ingen session-id mottogs.';

//Visa informationen
echo '<h1>Information</h1>';

if (isset($_GET['name'])) {
    echo '<p>Namn: ' . htmlspecialchars($_GET['name']) . '</p>';
}

echo '<p>Session ID: ' . htmlspecialchars($session_id) . '</p>';
?>
