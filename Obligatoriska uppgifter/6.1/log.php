<?php
/**
 * Detta skript loggar besökare och sparar information om:
 * - Tid
 * - REMOTE_ADDR (IP-adress)
 * - HTTP_USER_AGENT (webbläsare)
 * 
 * Loggar lagras i en JSON-fil och visas för besökaren. (Kontrollera att du har RW behörighet)
 */

//Filen där loggar ska sparas
$log_file = 'visitor_logs.json';

//Samla information om besökaren
$timestamp = date("Y-m-d H:i:s");
//Hämta extern IP-adress då vi är på localhost och får annars bara en loopback
if ($_SERVER['REMOTE_ADDR'] == '::1') {
    //Använd en extern tjänst för att få extern IP-adress
    $remote_addr = file_get_contents('https://api.ipify.org');
} else {
    $remote_addr = $_SERVER['REMOTE_ADDR']; //Använd den aktuella REMOTE_ADDR om det inte är localhost
}
$user_agent = $_SERVER['HTTP_USER_AGENT'];

//Skapa ett loggobjekt
$log_entry = array(
    "Tid" => $timestamp,
    "IP" => $remote_addr,
    "User-Agent" => $user_agent
);

//Läs in befintliga loggar från JSON-filen (om filen finns)
if (file_exists($log_file)) {
    $logs = json_decode(file_get_contents($log_file), true); //Hämta tidigare loggar
} else {
    $logs = array(); //Om ingen fil finns, skapa en tom array
}

//Lägg till den nya loggen i logglistan
$logs[] = $log_entry;

//Skriv tillbaka loggarna till JSON-filen
if (file_put_contents($log_file, json_encode($logs, JSON_PRETTY_PRINT))) {
    echo "Loggen sparades!";
} else {
    echo "Det gick inte att spara loggen.";
}

//Visa alla loggar
echo "<h1>Besökarloggar</h1><pre>";
foreach ($logs as $log) {
    echo "TID: " . $log['Tid'] . "\n";
    echo "REMOTE_ADDR: " . $log['IP'] . "\n";
    echo "REMOTE_USER_AGENT: " . $log['User-Agent'] . "\n\n";
}
echo "</pre>";
?>
