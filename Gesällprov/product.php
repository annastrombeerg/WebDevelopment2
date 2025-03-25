<?php
session_start();
$conn = new mysqli("localhost", "root", "", "E_Commerce_db");

$sql = "SELECT * FROM products";
$result = $conn->query($sql);

//Kontrollera om vi har några resultat
if ($result->num_rows > 0) {
    //Loop igenom alla produkter och visa dem
    while ($product = $result->fetch_assoc()) {
        echo "<div class='product'>";
        echo "<h3>" . $product["name"] . "</h3>";
        echo "<p>" . $product["description"] . "</p>";
        echo "<p>Price: " . $product["price"] . " kr</p>";
        
        //Visa bild från BLOB
        $image = $product["image"];
        echo "<img src='data:image/jpeg;base64," . base64_encode($image) . "' alt='" . $product["name"] . "' />";
        
        echo "<a href='add_cart.php?product_id=" . $product["id"] . "'>Add to cart</a>";
        echo "</div>";
    }
} else {
    echo "<p>No products found.</p>";
}

//Stäng anslutningen
$conn->close();

?>