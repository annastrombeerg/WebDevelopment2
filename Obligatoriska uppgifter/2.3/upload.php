<?php
/**
 * Hanterar uppladdning av en fil från användaren.
 * Filens information (namn, storlek och mime-typ) kontrolleras för att säkerställa att den inte överskrider 
 * maximalt tillåten storlek och har en godkänd mime-typ (text/plain, image/jpeg, eller image/png).
 */

//Aktivera felrapportering för att underlätta felsökning
ini_set('display_errors', 1);
error_reporting(E_ALL);

//Maximal storlek på uppladdad fil i byte
$max_file_size = 5 * 1024 * 1024;

//Kontrollera om filen är uppladdad och om det inte finns några fel
if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
    //Hämta filens information
    $file_tmp = $_FILES['file']['tmp_name'];
    $file_name = $_FILES['file']['name'];
    $file_size = $_FILES['file']['size'];
    $file_type = $_FILES['file']['type'];

    //Kontrollera filens storlek
    if ($file_size > $max_file_size) {
        echo "Filen är för stor. Maximal storlek är 5 MB.";
        exit;
    }

    //Kontrollera om filen har en godkänd mime-typ
    if ($file_type === "text/plain") {
        //Om filen är en textfil, visa innehållet
        header('Content-Type: text/plain; charset=UTF-8');
        echo "Innehållet i textfilen:\n";
        echo file_get_contents($file_tmp);
    } elseif ($file_type === "image/jpeg") {
        //Om filen är en JPEG-bild, visa bilden
        header('Content-Type: image/jpeg');
        echo file_get_contents($file_tmp);
    } elseif ($file_type === "image/png") {
        //Om filen är en PNG-bild, visa bilden
        header('Content-Type: image/png');
        echo file_get_contents($file_tmp);
    } else {
        //Om filens MIME-typ inte är godkänd, visa information om filen
        echo "Filnamn: " . basename($file_name) . "\n";
        echo "MIME-typ: " . $file_type . "\n";
        echo "Filstorlek: " . $file_size . " bytes\n";
    }
} else {
    echo "Ingen fil valdes eller det inträffade ett fel vid uppladdningen.";
}
?>
