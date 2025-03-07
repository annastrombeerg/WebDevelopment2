<?php
/**
 * Skriver ut alla namn/värde-par som skickats via GET.
 * För varje variabel skrivs dess namn och värde ut på en egen rad.
 */

//Aktivera felrapportering för att underlätta felsökning
ini_set('display_errors', 1);
error_reporting(E_ALL);

//Sätt mime-typ till text/plain för att säkerställa att svaret är i textformat
header('Content-Type: text/plain; charset=UTF-8');

//Kontrollera om det finns några GET-variabler
if (count($_GET) > 0) {
    echo "Från GET:\n";
    //Loop genom varje element i $_GET och skriv ut namn och värde
    foreach ($_GET as $name => $value) {
        echo "$name: $value\n";
    }
} else {
    echo "Inga GET-variabler mottogs.";
}
?>
