<?php
/**
 * Loggar ut kunden från sessionen.
 * Rensar sessionens data och skickar tillbaka kunden till startsidan.
 */

session_start();
session_unset();    //Tar bort alla sessionvariabler
session_destroy();  //Förstör hela sessionen

//Skicka kunden till startsidan
header("Location: startpage.php?loggedout=true");
exit();
?>
