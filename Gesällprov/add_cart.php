<?php
/**
 * Hanterar tillägg av en produkt till kundens kundvagn via POST-förfrågan.
 * Produkten och antalet som valts läggs till i sessionens "cart"-array.
 * Om produkten redan finns i kundvagnen uppdateras antalet.
 */

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //Hämta produktens ID och antal och se till att antal är minst 1
    $product_id = intval($_POST['product_id']);
    $quantity = max(1, intval($_POST['quantity']));

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    //Om produkten redan finns, öka antal
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] += $quantity;
    } else {
        //Annars lägg till produkten med angiven antal
        $_SESSION['cart'][$product_id] = $quantity;
    }

    //Skicka tillbaka kunden till produktsidan
    header("Location: product.php");
    exit;
}
?>
