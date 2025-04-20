<?php
/**
 * Detta PHP-skript hämtar produkter från databasen och visar dem på sidan, inklusive bilddata (sparas som BLOB i db).
 * Produkterna lagras i en MySQL-databas och visas på webbsidan.
 */

//Starta sessionen
session_start();

//Databasanslutning
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "E_Commerce_db";

try {
    //Skapa en anslutning till databasen
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    if (isset($_GET['id'])) {
        $id = $_GET['id'];
    
        //Hämta bilddata från databasen
        $sql = "SELECT * FROM products WHERE id = ?";
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
        exit;
    }

    $image = null;
    $mime_type = null;

    //Hämta produkter från databasen
    $sql = "SELECT * FROM products";
    $result = $conn->query($sql);

    //Ladda HTML
    $template = file_get_contents("product.html");

    //Förbered produktdata
    $output = "";
    if ($result->num_rows > 0) {
        //Loop igenom alla produkter och bygg HTML-strukturen
        while ($row = $result->fetch_assoc()) {
            $block = <<<EOD
            <div class="product">
            <h3>{$row['name']}</h3>
            <p>{$row['description']}</p>
            <p>Price: {$row['price']} kr</p>
            EOD;
            // Hämta bild om den finns
            $image_sql = "SELECT image FROM products WHERE id = " . $row['id'];
            $image_result = $conn->query($image_sql);
        if ($image_result->num_rows > 0) {
            $image_row = $image_result->fetch_assoc();
            // Lägg till bild direkt efter produktinformation
            $block .= "<p><img src='product.php?id=" . $row['id'] . "' alt='Bild saknas!' /></p>";
        }

            //Lägg till produkt i kundvagnen
            $block .= <<<EOD
            <a href='add_cart.php?product_id={$row['id']}'>Add to cart</a>
            </div>
            EOD;
            $output .= $block;
        }
    } else {
        $output = "<p>No products found.</p>";
    }

    //Ersätt placeholder med inlägg i mallen
    $result = preg_replace('/<!--===entries===-->.*<!--===entries===-->/s', "<!--===entries===-->\n" . $output . "\n<!--===entries===-->", $template);

    //Skicka HTML till användaren
    echo $result;

    //Stäng databasanslutning
    $conn->close();

} catch (Exception $e) {
    echo "Fel: " . $e->getMessage();
}
?>
