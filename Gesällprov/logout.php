<?php
session_start();
session_unset();    //Tar bort alla sessionvariabler
session_destroy();  //Förstör hela sessionen

//Skicka användaren till startsidan
header("Location: startpage.php?loggedout=true");
exit();
?>
