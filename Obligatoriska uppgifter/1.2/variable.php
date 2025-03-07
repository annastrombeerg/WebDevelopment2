<?php
/**
 * Skriver ut alla omgivningsvariabler från $_SERVER och $_ENV.
 * För varje variabel skrivs dess namn och värde ut på en egen rad.
 */

//Aktivera felrapportering för att underlätta felsökning
ini_set('display_errors', 1);
error_reporting(E_ALL);

//Sätt rätt mime-typ för att säkerställa att svaret är i textformat
header('Content-Type: text/plain; charset=UTF-8');

//Skriver ut variabler från $_SERVER
echo "Variabler från \$_SERVER:\n";
foreach ($_SERVER as $name => $value) {
    echo "$name: $value\n";
}

echo "\n";

//Skriver ut variabler från $_ENV
echo "Variabler från \$_ENV:\n";
foreach ($_ENV as $name => $value) {
    echo "$name: $value\n";
}
?>
