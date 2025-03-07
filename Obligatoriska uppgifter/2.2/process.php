<?php
/**
 * Skriver ut alla namn/värde-par som skickats via GET eller POST.
 * För varje variabel skrivs dess namn och värde ut på en egen rad.
 */

//Aktivera felrapportering för att underlätta felsökning
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Sätt rätt mime-typ för att säkerställa att svaret är i textformat
header('Content-Type: text/plain; charset=UTF-8');

// Kontrollera om några GET-variabler har skickats
if (count($_GET) > 0) {
    echo "GET-variabler:\n";
    foreach ($_GET as $name => $value) {
        echo "$name: $value\n";
    }
} else {
    echo "Inga GET-variabler mottogs.\n";
}

echo "\n";  // Radbrytning mellan GET och POST

// Kontrollera om några POST-variabler har skickats
if (count($_POST) > 0) {
    echo "POST-variabler:\n";
    foreach ($_POST as $name => $value) {
        echo "$name: $value\n";
    }
} else {
    echo "Inga POST-variabler mottogs.\n";
}
?>
