<?php
/**
 * Tar emot session-id från GET- eller POST-parametrarna (från länk eller formulär).
 * Om session-id inte finns, sätts ett standardmeddelande.
 * Visar mottagna session-idet.
 */

//Ta emot session-id från GET- eller POST-parametrarna
if (isset($_GET['session-id'])) {
    $session_id = $_GET['session-id']; //Från URL
} elseif (isset($_POST['session-id'])) {
    $session_id = $_POST['session-id']; //Från formulär
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
