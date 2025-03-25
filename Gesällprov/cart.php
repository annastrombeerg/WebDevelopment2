<?php
session_start();
$conn = new mysqli("localhost", "root", "", "E_Commerce_db");

echo "<h1>Your Cart</h1>";

if (isset($_SESSION['cart'])) {
    $total = 0;
    foreach ($_SESSION['cart'] as $product_id => $quantity) {
        $sql = "SELECT * FROM products WHERE id = $product_id";
        $result = $conn->query($sql);
        $product = $result->fetch_assoc();
        $total += $product['price'] * $quantity;
        echo "<p>" . $product['name'] . " - Quantity: $quantity - Price: " . $product['price'] * $quantity . " kr</p>";
    }
    echo "<p>Total: $total kr</p>";
    echo "<a href='pay.php'>Go to Checkout</a>";
} else {
    echo "<p>Your cart is empty.</p>";
}
$conn->close();
?>
