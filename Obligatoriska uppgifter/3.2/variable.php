<?php
/**
 * Skriver ut alla omgivningsvariabler från $_SERVER och $_ENV
 * och ersätter markörerna i HTML-mallen med den informationen.
 */

//Aktivera felrapportering för att underlätta felsökning
ini_set('display_errors', 1);
error_reporting(E_ALL);

//Läs in HTML-mallen från filen
$html = file_get_contents('index.html');

// Skapa en sträng med variabler från $_SERVER
$server_variables = '';
foreach ($_SERVER as $name => $value) {
    $server_variables .= "<tr><td>" . htmlspecialchars($name) . "</td><td>" . htmlspecialchars($value) . "</td></tr>\n";
}

//Skapa en sträng med variabler från $_ENV
$env_variables = '';
foreach ($_ENV as $name => $value) {
    $env_variables .= "<tr><td>" . htmlspecialchars($name) . "</td><td>" . htmlspecialchars($value) . "</td></tr>\n";
}

//Ersätt markörerna med de skapade strängarna
$html = str_replace('<!--===server_variables===-->', $server_variables, $html);
$html = str_replace('<!--===env_variables===-->', $env_variables, $html);

//Skriv ut den uppdaterade HTML-sidan
echo $html;
?>
