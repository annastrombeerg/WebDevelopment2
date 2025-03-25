<?php
session_start();

if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];

    //Om kundvagnen inte finns, skapa den
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    //Lägg till produkt i kundvagnen
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]++;
    } else {
        $_SESSION['cart'][$product_id] = 1;
    }

    echo "Product successfully added to cart!";
    header("Location: cart.php");
}
?>
