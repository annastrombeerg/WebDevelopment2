<?php
/**
 * Detta skript hanterar e-postsändning med bifogade filer via PHP:s mail()-funktion.
 * Skriptet skickar e-post med två bifogade filer.
 * Bilderna får inte vara större än 5 MB.
 * Det är ett krav att bifoga exakt två filer, annars skickas inte meddelandet.
 */

 //Aktivera felrapportering för att se detaljerade felmeddelanden
error_reporting(E_ALL);
ini_set('display_errors', 1);

$max_file_size = 5 * 1024 * 1024;

//Ta emot användarens data från formuläret
$from = $_POST['from'];
$to = $_POST['to'];
$cc = $_POST['cc'];
$bcc = $_POST['bcc'];
$subject = $_POST['subject'];
$message = $_POST['message'];

//Läs in filer
$file1 = $_FILES['file'];
$file2 = $_FILES['file2'];

//Funktion för att läsa in filinnehåll och konvertera till base64
function encode_file($file) {
    $file_path = $file['tmp_name'];
    $file_data = file_get_contents($file_path);
    return chunk_split(base64_encode($file_data));
}

//Skapa en unik boundary för att separera innehållet
$boundary = md5(uniqid(time()));

//Skapa headers för e-posten
$headers = "From: $from\r\n";
if (!empty($cc)) {
    $headers .= "CC: $cc\r\n";
}
if (!empty($bcc)) {
    $headers .= "BCC: $bcc\r\n";
}
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: multipart/mixed; boundary=\"$boundary\"\r\n";

//Skapa meddelandet med bifogade filer
$email_content = "--$boundary\r\n";
$email_content .= "Content-Type: text/plain; charset=UTF-8\r\n";
$email_content .= "Content-Transfer-Encoding: 7bit\r\n";
$email_content .= "\r\n$message\r\n";
$email_content .= "\r\n--$boundary\r\n";

//Bifoga fil 1
$email_content .= "Content-Type: " . $file1['type'] . "; name=\"" . $file1['name'] . "\"\r\n";
$email_content .= "Content-Transfer-Encoding: base64\r\n";
$email_content .= "Content-Disposition: attachment; filename=\"" . $file1['name'] . "\"\r\n";
$email_content .= "\r\n" . encode_file($file1) . "\r\n";
$email_content .= "--$boundary\r\n";

//Bifoga fil 2
$email_content .= "Content-Type: " . $file2['type'] . "; name=\"" . $file2['name'] . "\"\r\n";
$email_content .= "Content-Transfer-Encoding: base64\r\n";
$email_content .= "Content-Disposition: attachment; filename=\"" . $file2['name'] . "\"\r\n";
$email_content .= "\r\n" . encode_file($file2) . "\r\n";
$email_content .= "--$boundary--\r\n";

//Skicka e-posten
$success = mail($to, $subject, $email_content, $headers);

//Visa meddelande om e-posten skickades
if ($success) {
    echo "Meddelandet har skickats!";
} else {
    echo "Det gick inte att skicka meddelandet.";
}
echo "<br>";

echo "Observera! Detta meddelande är sänt från ett formulär på Internet och avsändaren kan vara felaktig!";
?>
