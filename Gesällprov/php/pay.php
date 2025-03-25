<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    echo "You have to be logged in to be able to make a order!";
    exit;
}

//Hämta kundvagnsinnehåll och skapa en beställning i databasen.
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
}
?>
