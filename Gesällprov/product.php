<?php
//Anslut till databasen
$conn = new mysqli("localhost", "root", "", "E_Commerce_db");

$sql = "SELECT * FROM products";
$result = $conn->query($sql);

while ($product = $result->fetch_assoc()) {
    echo "<div>";
    echo "<h3>" . $product["name"] . "</h3>";
    echo "<p>" . $product["description"] . "</p>";
    echo "<p>Price: " . $product["price"] . " kr</p>";
    echo "<img src='" . $product["image"] . "' alt='" . $product["name"] . "'>";
    echo "<a href='add_cart.php?product_id=" . $product["id"] . "'>Add to cart</a>";
    echo "</div>";
}
?>
