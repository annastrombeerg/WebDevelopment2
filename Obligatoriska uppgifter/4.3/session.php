<?php
/**
 * Detta skript startar en session och använder PHP:s inbyggda stöd för sessionshantering.
 * Om det inte redan finns ett sessions-id genereras ett nytt
 * Skriptet ersätter markören '---session-id---' i HTML
 */

 session_start();

//Kontrollera om session-id finns i URL-parametrarna, annars skapa ett nytt
if (!isset($_SESSION['session-id'])) {
    $_SESSION['session-id'] = rand(100000, 999999);
}

//Läs in HTML-dokumentet
$html = file_get_contents('index.html');

//Ersätt markören ---session-id--- med det genererade sessions-idet
$html = str_replace('---session-id---', $_SESSION['session-id'], $html);

//Skicka vidare det modifierade HTML-dokumentet
echo $html;
?>
