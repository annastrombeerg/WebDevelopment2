<?php
/**
 * Läs in HTML-mallen och ersätt markören ---session-id--- med det genererade sessions-idet.
 */

//Kontrollera om session-id finns i URL-parametrarna, annars skapa ett nytt
if (isset($_GET['session-id'])) {
    $session_id = $_GET['session-id']; //Ta emot sessions-id från URL
} else {
    //Skapa ett slumpmässigt numeriskt sessions-id
    $session_id = rand(100000, 999999); //Skapar ett tal mellan 100000 och 999999
}

//Läs in HTML-dokumentet
$html = file_get_contents('index.html');

//Ersätt markören ---session-id--- med det genererade sessions-id
$html = str_replace('---session-id---', $session_id, $html);

//Skicka vidare det modifierade HTML-dokumentet
echo $html;
?>
