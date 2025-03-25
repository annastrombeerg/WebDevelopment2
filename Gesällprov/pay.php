<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    echo "You have to be logged in to be able to make a order!";
    exit;
}

$conn = new mysqli("localhost", "root", "", "E_Commerce_db");

echo "<h1>Payment</h1>";

if (isset($_SESSION['cart'])) {
    $cart = $_SESSION['cart'];
    $cart_items = implode(',', array_keys($cart)); // Hämta alla produkt-ID:n
    $sql = "SELECT * FROM products WHERE id IN ($cart_items)";
    $result = $conn->query($sql);

    echo "<ul>";
    while ($row = $result->fetch_assoc()) {
        echo "<li>" . $row['name'] . " - Quantity: " . $cart[$row['id']] . " - Price: " . $row['price'] * $cart[$row['id']] . " kr</li>";
    }
    echo "</ul>";

    $total = 0;
    foreach ($cart as $product_id => $quantity) {
        $sql = "SELECT * FROM products WHERE id = $product_id";
        $result = $conn->query($sql);
        $product = $result->fetch_assoc();
        $total += $product['price'] * $quantity;
    }

    echo "<p>Total amount: $total kr</p>";
    echo "<a href='order_complete.php'>Proceed to Payment</a>";
} else {
    echo "Your cart is empty!";
}

$conn->close();

/* //Hämta kundvagnsinnehåll och skapa en beställning i databasen.
if (isset($_SESSION['cart'])) {
    $total = 0;
    foreach ($_SESSION['cart'] as $product_id => $quantity) {
        $sql = "SELECT * FROM products WHERE id = $product_id";
        $result = $conn->query($sql);
        $product = $result->fetch_assoc();
        $total += $product['price'] * $quantity;

        $stmt = $conn->prepare("INSERT INTO orders (user_id, product_id, quantity) VALUES (?, ?, ?)");
        $stmt->bind_param("iii", $_SESSION['user_id'], $product_id, $quantity);
        $stmt->execute();
    }
    echo "Your order has successfully been completed!";
    //Töm kundvagnen efter beställningen
    unset($_SESSION['cart']);
} */
?>
