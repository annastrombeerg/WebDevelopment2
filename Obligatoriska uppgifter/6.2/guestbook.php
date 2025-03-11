<?php
/**
 * Detta PHP-skript skapar en gästbok där användare kan lämna sina namn, e-postadresser, hemsidor och kommentarer. 
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

//Variabler för formulärdata
$name = $email = $homepage = $comment = "";

//Kontrollera om formuläret är skickat
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //Rensa och hämta data från formuläret
    $name = clean_input($_POST["name"]);
    $email = clean_input($_POST["email"]);
    $homepage = clean_input($_POST["homepage"]);
    $comment = clean_input($_POST["comment"]);

    //Förbered SQL-fråga för att lägga till inlägg i databasen
    $stmt = $conn->prepare("INSERT INTO guestbook_entries (name, email, homepage, comment) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $homepage, $comment);

    //Exekvera frågan och kontrollera om den lyckades
    if ($stmt->execute()) {
        //Visa ett tack-meddelande och visa formuläret igen
        echo "<p>Tack för ditt inlägg!</p>";
        $name = $email = $homepage = $comment = "";
    } else {
        echo "Fel vid inlämning: " . $stmt->error;
    }

    //Stäng förberedelsen
    $stmt->close();
}

//Hämta och visa alla inlägg
$sql = "SELECT * FROM guestbook_entries ORDER BY created_at DESC";
$result = $conn->query($sql);

echo "<form method='post' action=''>";
echo "<p><input type='text' name='name' placeholder='Namn' value='" . $name . "'></p>";
echo "<p><input type='text' name='email' placeholder='Email' value='" . $email . "'></p>";
echo "<p><input type='text' name='homepage' placeholder='Hemsida' value='" . $homepage . "'></p>";
echo "<p><textarea name='comment' rows='5' cols='30' placeholder='Kommentar'>" . $comment . "</textarea></p>";
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
        echo "<hr>";
    }
} else {
    echo "Inga inlägg än.";
}

//Stäng anslutningen
$conn->close();
?>
