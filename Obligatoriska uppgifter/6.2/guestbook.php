<?php
/**
 * Detta PHP-skript skapar en gästbok där användare kan lämna sina namn, e-postadresser, hemsidor och kommentarer.
 * Inläggen lagras i en MySQL-databas och visas på webbsidan. 
 * Skyddar mot XSS och SQL-injektion genom att använda prepared statements och rensa indata.
 */

//Visar felmeddelanden
ini_set('display_errors', 1);
error_reporting(E_ALL);

//Konfiguration för databasanslutning
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "guestbook_db";

try {
    //Skapa en anslutning till databasen
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    //Funktion för att rensa data och förhindra HTML och SQL-injektion
    function clean_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = strip_tags($data);
        return $data;
    }

    //Variabler för formulärdata
    $name = $email = $homepage = $comment = "";

    //Hantera formulärinlämning
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        //Rensa och hämta data från formuläret
        $name = clean_input($_POST["name"]);
        $email = clean_input($_POST["email"]);
        $homepage = clean_input($_POST["homepage"]);
        $comment = clean_input($_POST["comment"]);
        $created_at = date('Y-m-d H:i:s');

        //Förbered SQL-fråga för att lägga till inlägg i databasen
        $stmt = $conn->prepare("INSERT INTO guestbook_entries (name, email, homepage, comment) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $homepage, $comment);

        //Exekvera frågan och kontrollera om den lyckades
        if ($stmt->execute()) {
            $name = $email = $homepage = $comment = "";
        } else {
            throw new Exception("Fel vid inlämning: " . $stmt->error);
        }
        $stmt->close();
    }

    //Hämta alla inlägg från databasen
    $sql = "SELECT * FROM guestbook_entries ORDER BY created_at DESC";
    $result = $conn->query($sql);

    //Ladda HTML
    $template = file_get_contents("index.html");

    //Förbered inlägg
    $output = "";
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $block = <<<EOD
            <p><strong>Inlägg:</strong> {$row['id']}</p>
            <p><strong>Tid:</strong> {$row['created_at']}<br>
            <strong>Från:</strong> <a href="{$row['homepage']}">{$row['name']}</a><br>
            <strong>E-post:</strong> <a href="mailto:{$row['email']}">{$row['email']}</a></p>
            <p><strong>Kommentar:</strong> {$row['comment']}</p>
            <hr>
            EOD;
            $output .= $block;
        }
    } else {
        $output = "<p>Inga inlägg än.</p>";
    }

    //Ersätt placeholder med inlägg i mallen
    $result = preg_replace('/<!--===entries===-->.*<!--===entries===-->/s', "<!--===entries===-->\n" . $output . "\n<!--===entries===-->", $template);

    //Skicka HTML till användaren
    echo $result;

} catch (Exception $e) {
    echo "Fel: " . $e->getMessage();
} finally {
    if (isset($conn)) {
        $conn->close();
    }
}
?>
