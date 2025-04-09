<?php
session_start();
$conn = new mysqli("localhost", "root", "", "E_Commerce_db");

$sql = "SELECT * FROM products";
$result = $conn->query($sql);

//Starta HTML-sidan
echo "<!doctype html>";
echo "<html lang='se'>";
echo "<head>";
echo "<meta charset='UTF-8'>";
echo "<title>Products</title>";
echo "<link rel='stylesheet' href='style.css?rnd=12345'>";
echo "</head>";
echo "<body>";

//Navigationsbar
echo "<header><nav><ul>
            <li><a href='startpage.html'>Home</a></li>
            <li><a href='login.html'>Login</a></li>
            <li><a href='register.html'>Register</a></li>
            <li><a href='product.php'>Products</a></li>
            <li><a href='cart.php'>Cart</a></li>
        </ul></nav></header>";

//Titel
echo "<h1>Our Delicious Cupcakes</h1>";
echo "<div class='product-container'>";

//Kontrollera om vi har några produkter i databasen
if ($result->num_rows > 0) {
    //Loop igenom alla produkter och visa dem
    while ($product = $result->fetch_assoc()) {
        echo "<div class='product'>";
        echo "<h3>" . $product["name"] . "</h3>";
        echo "<p>" . $product["description"] . "</p>";
        echo "<p>Price: " . $product["price"] . " kr</p>";
        
        //Visa bild från databasen
        $image = $product["image"];
        echo "<img src='data:image/jpeg;base64," . base64_encode($image) . "' alt='" . $product["name"] . "' />";
        
        //Lägg till produkt i kundvagnen
        echo "<a href='add_cart.php?product_id=" . $product["id"] . "'>Add to cart</a>";
        echo "</div>";
    }
} else {
    echo "<p>No products found.</p>";
}

//Stäng anslutningen till databasen
$conn->close();

echo "</div>";
echo "</body>";
echo "<footer><p>&copy; 2025 Anna E-Commerce LLC</p></footer>";
echo "</html>";
?>
