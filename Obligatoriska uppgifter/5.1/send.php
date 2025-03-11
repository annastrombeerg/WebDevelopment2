<?php
/**
 * Detta skript tar emot användarens input från formulär och skickar e-post.
 * Använd PHP:s inbyggda mail() för att skicka meddelandet.
 */

//Ta emot användarens data från formuläret
$from = $_POST['from'];
$to = $_POST['to'];
$cc = $_POST['cc'];
$bcc = $_POST['bcc'];
$subject = $_POST['subject'];
$message = $_POST['message'];

//Skapa headers för e-posten
$headers = "From: $from\r\n";
if (!empty($cc)) {
    $headers .= "CC: $cc\r\n";
}
if (!empty($bcc)) {
    $headers .= "BCC: $bcc\r\n";
}
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

//Skicka e-posten
$success = mail($to, $subject, $message, $headers);

//Visa meddelande om e-posten skickades
if ($success) {
    echo "Meddelandet har skickats!";
} else {
    echo "Det gick inte att skicka meddelandet.";
}
echo "<br>";

echo "Observera! Detta meddelande är sänt från ett formulär på Internet och avsändaren kan vara felaktig!";
?>
