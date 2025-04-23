<?php
session_start();
$conn = new mysqli("localhost", "root", "", "E_Commerce_db");

//Ladda HTML-mallen
$template = file_get_contents('cart.html');

//Bygg kundvagnsinnehåll
$cart_output = "";
if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
    $total = 0;

    foreach ($_SESSION['cart'] as $product_id => $quantity) {
        $sql = "SELECT * FROM products WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($product = $result->fetch_assoc()) {
            $subtotal = $product['price'] * $quantity;
            $total += $subtotal;
            $cart_output .= "<p>{$product['name']} - Quantity: $quantity - Price: {$subtotal} kr</p>";
        }
        $stmt->close();
    }

    $cart_output .= "<p><strong>Total: $total kr</strong></p>";
    $cart_output .= "<p><a href='pay.php' class='button'>Go to Checkout</a></p>";
} else {
    $cart_output = "<p>Your cart is empty.</p>";
}

//Ersätt kundvagnssektion
$template = str_replace('<!--===cart===-->', $cart_output, $template);

//Visa hela sidan
echo $template;

$conn->close();
?>
