<?php
/**
 * Detta PHP-skript skapar en gästbok där användare kan lämna sina namn, e-postadresser, hemsidor och kommentarer samt en bild (som lagras som BLOB i databasen).  
 * Inläggen lagras i en MySQL-databas och visas på webbsidan. 
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

//Konfiguration för databasanslutning
$servername = "localhost"; //server
$username = "root";
$password = "";
$dbname = "guestbook_db"; //använd rätt databasnamn

//Skapa en anslutning
$conn = new mysqli($servername, $username, $password, $dbname);

//Kontrollera om anslutningen är lyckad
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

//Funktion för att rensa data och förhindra HTML och SQL-injektion
function clean_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

//Kontrollera om 'id' är satt i URL:en för att visa bild
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
$image_data = null;  //Variabel för bilddata
$mime_type = null;   //Variabel för mime-typen

//Kontrollera om formuläret är skickat
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //Rensa och hämta data från formuläret
    $name = clean_input($_POST["name"]);
    $email = clean_input($_POST["email"]);
    $homepage = clean_input($_POST["homepage"]);
    $comment = clean_input($_POST["comment"]);

    //Kontrollera om en bild har laddats upp
    if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
        //Läs in bildfilen och spara den som en BLOB
        $image_data = file_get_contents($_FILES['file']['tmp_name']);
        $mime_type = $_FILES['file']['type'];  //Till exempel image/jpeg
    }

    //Starta en transaktion
    $conn->begin_transaction();

    try {
        //Förbered SQL-fråga för att lägga till gästboksinlägg
        $stmt = $conn->prepare("INSERT INTO guestbook_entries (name, email, homepage, comment) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $homepage, $comment);
        $stmt->execute();
        $entry_id = $stmt->insert_id; //Hämta ID för det nyligen tillagda inlägget

        //Om en bild laddades upp, spara den i guestbook_images-tabellen
        if ($image_data) {
            $stmt = $conn->prepare("INSERT INTO guestbook_images (guestbook_entry_id, image_data, mime_type) VALUES (?, ?, ?)");
            $stmt->bind_param("iss", $entry_id, $image_data, $mime_type);
            $stmt->execute();
        }

        //Om allt gick bra, gör commit
        $conn->commit();

        //Visa ett tack-meddelande och rensa formuläret
        echo "<p>Tack för ditt inlägg!</p>";
        $name = $email = $homepage = $comment = "";
    } catch (Exception $e) {
        //Om något går fel, rulla tillbaka transaktionen
        $conn->rollback();
        echo "Fel vid inlämning: " . $e->getMessage();
    }
}

//Hämta och visa alla inlägg
$sql = "SELECT * FROM guestbook_entries ORDER BY created_at DESC";
$result = $conn->query($sql);

echo "<form method='post' action='' enctype='multipart/form-data'>";
echo "<p><input type='text' name='name' placeholder='Namn' value='" . $name . "'></p>";
echo "<p><input type='text' name='email' placeholder='Email' value='" . $email . "'></p>";
echo "<p><input type='text' name='homepage' placeholder='Hemsida' value='" . $homepage . "'></p>";
echo "<p><textarea name='comment' rows='5' cols='30' placeholder='Kommentar'>" . $comment . "</textarea></p>";
echo "<p>Bild: <input type='file' name='file'></p>";
echo "<p><input type='submit' name='push_button' value='Sänd'></p>";
echo "</form>";

if ($result->num_rows > 0) {
    //Visa varje inlägg
    while($row = $result->fetch_assoc()) {
        echo "<p><strong>Inlägg:</strong> " . $row['id'] . "</p>";
        echo "<p><strong>Tid:</strong> " . $row['created_at'] . "<br>";
        echo "<strong>Från:</strong> <a href='" . $row['homepage'] . "'>" . $row['name'] . "</a><br>";
        echo "<strong>E-post:</strong> <a href='mailto:" . $row['email'] . "'>" . $row['email'] . "</a></p>";
        echo "<p><strong>Kommentar:</strong> " . nl2br($row['comment']) . "</p>";

        //Visa bild om den finns
        $image_sql = "SELECT * FROM guestbook_images WHERE guestbook_entry_id = " . $row['id'];
        $image_result = $conn->query($image_sql);
        if ($image_result->num_rows > 0) {
            $image_row = $image_result->fetch_assoc();
            echo "<p><img src='guestbook.php?id=" . $row['id'] . "' alt='Bild saknas!' /></p>";
        }


        echo "<hr>";
    }
} else {
    echo "Inga inlägg än.";
}

//Stäng anslutningen
$conn->close();
?>