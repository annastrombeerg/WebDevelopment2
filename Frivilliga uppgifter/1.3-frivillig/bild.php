<?php
/**
 * Skapar en dynamisk PNG-bild med slumpmässiga grafikelement och aktuell tid.
 * Bilden genereras varje gång sidan laddas och innehåller text, cirkel och linje.
*/

//Skapa en 800x350-bild
$img = imagecreate(800, 350);

//Slumpa bakgrundsfärg
$bgColor = imagecolorallocate($img, rand(150, 255), rand(150, 255), rand(150, 255));

//Färger
$black = imagecolorallocate($img, 0, 0, 0);
$circleColor = imagecolorallocate($img, rand(100,255), rand(100,255), rand(100,255));
$lineColor = imagecolorallocate($img, rand(0,255), rand(0,255), rand(0,255));

//Rita cirkeln
$cx = rand(100, 700);
$cy = rand(100, 250);
$radius = rand(30, 60);
imageellipse($img, $cx, $cy, $radius * 2, $radius * 2, $circleColor);

//Rita linjen
$x1 = rand(0, 799);
$y1 = rand(0, 349);
$x2 = rand(0, 799);
$y2 = rand(0, 349);
imageline($img, $x1, $y1, $x2, $y2, $lineColor);

//Tidstext
$time = date("Y-m-d H:i:s");
imagestring($img, 5, 280, 20, "Current Time:", $black);
imagestring($img, 4, 280, 45, $time, $black);

//Slumpmässiga texter i olika storlekar
imagestring($img, 3, 100, 300, "Random text", $black);
imagestring($img, 2, 400, 300, "Tiny tiny text", $black);
imagestring($img, 1, 600, 300, "Very very tiny", $black);

header("Content-Type: image/png");
imagepng($img);
imagedestroy($img);
?>
