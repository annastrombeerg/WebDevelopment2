<?php
// Starta session
session_start();

// Databasanslutning
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "E_Commerce_db";

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    try {
        // Skapa en anslutning till databasen
        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            throw new Exception("Connection failed: " . $conn->connect_error);
        }

        // Hämta produktbild och MIME-typ från databasen
        $sql = "SELECT image FROM products WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            //Sätt rätt Content-Type för bilden (ex image/jpeg)
            header("Content-Type: " . $row['mime_type']);
            //Visa bildens binära data
            echo $row['image'];
        } else {
            echo "Ingen bild hittades!";
        }

        $stmt->close();
        $conn->close();

    } catch (Exception $e) {
        echo "Fel: " . $e->getMessage();
    }
} else {
    echo "Ingen bild-id angiven.";
}
?>
