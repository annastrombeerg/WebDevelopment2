<?php
/**
 * Visar innehållet i kundens kundvagn.
 * Hämtar produktdata från databasen, beräknar totalsumma och visar möjligheten att lägga till ett personligt meddelande.
 * Om kundvagnen är tom visas ett meddelande om detta.
 */

session_start();
$conn = new mysqli("localhost", "root", "", "E_Commerce_db");

if (!isset($_SESSION['user_id'])) {
    //Om inte inloggad, skicka till login
    header("Location: login.php?msg=not_logged_in");
    exit;
}

//Ladda HTML-mallen
$template = file_get_contents('cart.html');

//Visa logout om användaren är inloggad
$logout = "";
if (isset($_SESSION['user_id'])) {
    $logout = '<li class="logout-right"><a href="logout.php">Logout</a></li>';
}
$template = str_replace("<!--===logout===-->", $logout, $template);

//Generera kundvagnsinnehåll
$cart_output = "";
if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
    $total = 0;
    //Loopar igenom varje produkt i kundvagnen
    foreach ($_SESSION['cart'] as $product_id => $quantity) {
        $sql = "SELECT * FROM products WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        //Om produkten finns, lägg till informationen till utskriften
        if ($product = $result->fetch_assoc()) {
            $subtotal = $product['price'] * $quantity;
            $total += $subtotal;
            $cart_output .= "<p>{$product['name']} - Quantity: $quantity - Price: {$product['price']} kr</p>";
        }
        $stmt->close();
    }
    //Visa totalsumma och formulär för personligt meddelande
    $cart_output .= "<p><strong>Total: $total kr</strong></p>";
    $cart_output .= <<<EOD
    <form action="pay.php" method="post">
        <label for="customMessage"><strong>Your Cupcake Message (max 3 words):</strong></label><br>
        <input type="text" name="customMessage" id="customMessage" maxlength="50"
               pattern="(\b\w+\b[\s]*){1,3}" placeholder="e.g. Happy Birthday Alice" required><br><br>
        <button type="submit" class="button">Go to Checkout</button>
    </form>
    EOD;
} else {
    $cart_output = "<p>Your cart is empty.</p>";
}

//Ersätt placeholder i HTML-mall med genererat innehåll
$template = str_replace('<!--===cart===-->', $cart_output, $template);

//Visa hela sidan
echo $template;

$conn->close();
?>
