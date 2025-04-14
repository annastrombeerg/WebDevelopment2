<?php
/**
 * Detta PHP-skript skapar en gästbok där användare kan lämna sina namn, e-postadresser, hemsidor och kommentarer samt en bild (sparas som BLOB i db).
 * Inläggen lagras i en MySQL-databas och visas på webbsidan. 
 * Sparar bilddata + mime-typ i en separat tabell `guestbook_images`
 * Använder transaktion för att säkerställa att både text och bild sparas tillsammans
 * Om något misslyckas sker rollback
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

    if (isset($_GET['id'])) {
        $id = $_GET['id'];
    
        //Hämta bilddata från databasen
        $sql = "SELECT * FROM guestbook_images WHERE guestbook_entry_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($row = $result->fetch_assoc()) {
            //Sätt rätt Content-Type för bilden (ex image/jpeg)
            header("Content-Type: " . $row['mime_type']);
            //Visa bildens binära data
            echo $row['image_data'];
        } else {
            echo "Ingen bild hittades!";
        }
    
        $stmt->close();
        exit;
    }

    //Variabler för formulärdata
    $name = $email = $homepage = $comment = "";
    $image_data = null;
    $mime_type = null;

    //Hantera formulärinlämning
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        //Rensa och hämta data från formuläret
        $name = clean_input($_POST["name"]);
        $email = clean_input($_POST["email"]);
        $homepage = clean_input($_POST["homepage"]);
        $comment = clean_input($_POST["comment"]);
        $created_at = date('Y-m-d H:i:s');

        //Kontrollera om en bild har laddats upp
        if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
            //Läs in bildfilen och spara den som en BLOB
            $image_data = file_get_contents($_FILES['file']['tmp_name']);
            $mime_type = $_FILES['file']['type'];  //Till exempel image/jpeg
        }

        //Starta en transaktion
        $conn->begin_transaction();

        try {
            //Förbered SQL-fråga för att lägga till inlägg i gästboken
            $stmt = $conn->prepare("INSERT INTO guestbook_entries (name, email, homepage, comment, created_at) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $name, $email, $homepage, $comment, $created_at);
            $stmt->execute();
            $entry_id = $stmt->insert_id; //Hämta det senaste inläggs ID

            //Om en bild laddades upp, spara den i guestbook_images-tabellen
            if ($image_data) {
                $stmt = $conn->prepare("INSERT INTO guestbook_images (guestbook_entry_id, image_data, mime_type) VALUES (?, ?, ?)");
                $stmt->bind_param("iss", $entry_id, $image_data, $mime_type);
                $stmt->execute();
            }

            //Om allt gick bra, commit transaktionen
            $conn->commit();

            //Återställ formulärdata
            echo "<p>Tack för ditt inlägg!</p>";
            $name = $email = $homepage = $comment = "";
        } catch (Exception $e) {
            //Om något går fel, rulla tillbaka transaktionen
            $conn->rollback();
            throw new Exception("Fel vid uppladdning: " . $e->getMessage());
        }
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
            EOD;

            // Hämta bilden om den finns
            $image_sql = "SELECT * FROM guestbook_images WHERE guestbook_entry_id = " . $row['id'];
            $image_result = $conn->query($image_sql);
            if ($image_result->num_rows > 0) {
                $image_row = $image_result->fetch_assoc();
                // Lägg till bilden direkt efter "Inlägg:"
                $block .= "<p><img src='guestbook.php?id=" . $row['id'] . "' alt='Bild saknas!' /></p>";
            }

            // Lägg till tid, namn, e-post och kommentar efter bilden
            $block .= <<<EOD
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