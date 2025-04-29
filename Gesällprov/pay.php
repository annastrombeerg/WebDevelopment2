<?php
/**
 * Hanterar sammanställning och slutförande av kundens beställning.
 * Visar en sammanfattning av varukorgen inklusive kundens valda meddelande.
 * Vid bekräftelse sparas beställningen i databasen och kundvagnen töms.
 */

session_start();
$conn = new mysqli("localhost", "root", "", "E_Commerce_db");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);
//Om kunden inte är inloggad, skicka till login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

//Om meddelande skickas från cart.php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['customMessage'])) {
    $_SESSION['customMessage'] = htmlspecialchars($_POST['customMessage']);
}
//Om kundvagnen är tom
if (!isset($_SESSION['cart']) || count($_SESSION['cart']) === 0) {
    echo "<p>Your cart is empty. <a href='product.php'>Go back</a></p>";
    exit();
}

$user_id = $_SESSION['user_id'];
$summary = "";
$total = 0;

//Visa kundens personliga meddelande om det finns
if (isset($_SESSION['customMessage'])) {
    $summary .= "<p><strong>Your message:</strong> \"" . $_SESSION['customMessage'] . "\"</p>";
}
//Generera produktöversikten/summeringen
foreach ($_SESSION['cart'] as $product_id => $quantity) {
    $stmt = $conn->prepare("SELECT name, price FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $stmt->bind_result($name, $price);
    $stmt->fetch();
    $stmt->close();

    $subtotal = $price * $quantity;
    $total += $subtotal;

    $summary .= <<<EOD
    <p>{$name} - Quantity: {$quantity} - Price: {$subtotal} kr in total</p>
    EOD;
}
//Lägg till totalsumma och betalningsknapp
$summary .= <<<EOD
<p><strong>To Pay: {$total} kr</strong></p>
<form method="post">
    <button type="submit" class="button">Confirm and Pay</button>
</form>
EOD;

//Ladda HTML-mallen
$template = file_get_contents("pay.html");

//Visa logout om användaren är inloggad
$logout = "";
if (isset($_SESSION['user_id'])) {
    $logout = '<li class="logout-right"><a href="logout.php">Logout</a></li>';
}
$template = str_replace("<!--===logout===-->", $logout, $template);
$template = str_replace("<!--===summary===-->", $summary, $template);

//Om kunden bekräftar beställningen (utan att skicka nytt meddelande)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['customMessage'])){
    $customMessage = $_SESSION['customMessage'] ?? '';
    foreach ($_SESSION['cart'] as $product_id => $quantity) {
        $stmt = $conn->prepare("INSERT INTO orders (user_id, product_id, quantity, message, order_date) VALUES (?, ?, ?, ?, NOW())");
        $stmt->bind_param("iiis", $user_id, $product_id, $quantity, $customMessage);
        $stmt->execute();
        $stmt->close();
    }

    unset($_SESSION['cart']); //Töm kundvagnen
    unset($_SESSION['customMessage']);

    //Tackmeddelande
    $output = <<<EOD
    <h3>Thank you for your order!</h3>
    <p>Your order has been placed successfully.</p>
    <p><a href='startpage.html' class='button return'>Return to homepage</a></p>
    EOD;

    $template = file_get_contents("pay.html");
    //Visa logout om användaren är inloggad
    $logout = "";
    if (isset($_SESSION['user_id'])) {
        $logout = '<li class="logout-right"><a href="logout.php">Logout</a></li>';
    }
    $template = str_replace("<!--===logout===-->", $logout, $template);
    $template = str_replace("<!--===summary===-->", $output, $template);
    echo $template;
    $conn->close();
    exit();
}

echo $template;
$conn->close();
?>
