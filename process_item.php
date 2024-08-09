<?php

include 'inventory.php';
include 'sales.php';
include 'search.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add_item'])) {
        $name = $_POST["name"];
        $type = $_POST["type"];
        $price = $_POST["price"];
        $quantity = $_POST["quantity"];
        $expiry_date = $_POST["expiry_date"];
        addItem($groceryItems, $name, $type, $price, $quantity, $expiry_date);
    } elseif (isset($_POST['update_quantity'])) {
        $name = $_POST["name"];
        $quantity = $_POST["quantity"];
        updateQuantity($groceryItems, $name, $quantity);
    } elseif (isset($_POST['place_order'])) {
        $customerName = $_POST["customerName"];
        $items = $_POST["quantities"];
        placeOrder($orders, $groceryItems, $sales, $customerName, $items);
    } 
}
?>
