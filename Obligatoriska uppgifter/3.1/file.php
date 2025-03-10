<?php
/**
 * Detta PHP-skript implementerar en besöksräknare som lagrar antalet besök i en textfil ("counter.txt").
 * HTML-sidan laddas in och ersätts med besöksräknarens värde.
 */

//Aktivera felrapportering för att underlätta felsökning
ini_set('display_errors', 1);
error_reporting(E_ALL);

//Filen där vi lagrar besöksräknaren
$filename = 'counter.txt';

//Kontrollera om filen finns, skapa den om den inte gör det
if (!file_exists($filename)) {
    file_put_contents($filename, "0");  //Starta räknaren med 0 om filen inte finns
}

//Öppna filen för läsning och skrivning
$file = fopen($filename, 'c+');

//Lås filen för skrivoperation (för att undvika krockar)
if (flock($file, LOCK_EX)) {
    //Kontrollera filens storlek
    $filesize = filesize($filename);

    //Om filen är tom, sätt räknaren till 0
    if ($filesize > 0) {
        //Läs nuvarande värde från filen
        $counter = (int)fread($file, $filesize);
    } else {
        //Om filen är tom, sätt räknaren till 0
        $counter = 0;
    }

    //Öka räknaren med 1
    $counter++;

    //Gå tillbaka till början av filen för att skriva det nya värdet
    fseek($file, 0);

    //Skriv det uppdaterade värdet till filen
    fwrite($file, $counter);

    //Släpp låset
    flock($file, LOCK_UN);
} else {
    echo "Det gick inte att låsa filen!";
    exit;
}

//Stäng filen
fclose($file);

//Läs in HTML-filen som innehåller markören ---$hits---
$html = file_get_contents("index.html");

//Ersätt markören med det aktuella antalet besök
$html = str_replace('---$hits---', $counter, $html);

//Skriv ut den uppdaterade HTML-sidan
echo $html;
?>
