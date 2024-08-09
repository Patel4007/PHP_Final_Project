<?php

$groceryItems = [
    "Milk" => ["type" => "Dairy", "price" => 1.50, "quantity" => 10, "expiry_date" => "2024-06-15"],
    "Bread" => ["type" => "Bakery", "price" => 2.00, "quantity" => 20, "expiry_date" => "2024-06-10"],
    "Cheese" => ["type" => "Dairy", "price" => 3.00, "quantity" => 15, "expiry_date" => "2024-06-20"]
];

$orders = [];
$sales = [];

function displayInventory($groceryItems) {
    echo "<table border='1' class='inventory-table'>";
    echo "<tr><th>Item Name</th><th>Type</th><th>Price ($)</th><th>Quantity</th><th>Expiry Date</th></tr>";
    foreach ($groceryItems as $name => $details) {
        echo "<tr>";
        echo "<td>$name</td>";
        echo "<td>{$details['type']}</td>";
        echo "<td>{$details['price']}</td>";
        echo "<td>{$details['quantity']}</td>";
        echo "<td>{$details['expiry_date']}</td>";
        echo "</tr>";
    }
    echo "</table>";
}

function addItem(&$groceryItems, $name, $type, $price, $quantity, $expiry_date) {
    $groceryItems[$name] = ["type" => $type, "price" => $price, "quantity" => $quantity, "expiry_date" => $expiry_date];
}

function updateQuantity(&$groceryItems, $name, $quantity) {
    if (isset($groceryItems[$name])) {
        $groceryItems[$name]['quantity'] = $quantity;
    }
}

function placeOrder(&$orders, &$groceryItems, &$sales, $customerName, $items) {
    $orderTotal = 0;
    foreach ($items as $item => $quantity) {
        if (isset($groceryItems[$item]) && $groceryItems[$item]['quantity'] >= $quantity) {
            $groceryItems[$item]['quantity'] -= $quantity;
            $orderTotal += $groceryItems[$item]['price'] * $quantity;
            $sales[] = ["item" => $item, "quantity" => $quantity, "total" => $groceryItems[$item]['price'] * $quantity];
        }
    }
    $orders[] = ["customer" => $customerName, "items" => $items, "total" => $orderTotal];
}

function displaySales($sales) {
    echo "<table border='1' class='sales-table'>";
    echo "<tr><th>Item Name</th><th>Quantity Sold</th><th>Total ($)</th></tr>";
    foreach ($sales as $sale) {
        echo "<tr>";
        echo "<td>{$sale['item']}</td>";
        echo "<td>{$sale['quantity']}</td>";
        echo "<td>{$sale['total']}</td>";
        echo "</tr>";
    }
    echo "</table>";
}

function searchItem($groceryItems, $searchTerm) {
    $result = [];
    foreach ($groceryItems as $name => $details) {
        if (stripos($name, $searchTerm) !== false) {
            $result[$name] = $details;
        }
    }
    return $result;
}

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
        $items = [];
        foreach ($_POST["items"] as $item => $quantity) {
            if (!empty($quantity)) {
                $items[$item] = $quantity;
            }
        }
        placeOrder($orders, $groceryItems, $sales, $customerName, $items);
    } elseif (isset($_POST['search'])) {
        $searchTerm = $_POST["searchTerm"];
        $searchResults = searchItem($groceryItems, $searchTerm);
    }
}

ob_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Grocery Store Management System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        h1, h2 {
            color: #333;
        }
        form {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-top: 10px;
        }
        input[type="text"], input[type="number"], select {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            box-sizing: border-box;
        }
        input[type="submit"], input[type="button"] {
            margin-top: 10px;
            padding: 10px 15px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px 12px;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f4f4f4;
        }
    </style>
</head>
<body>
    <h1>Grocery Store Management System</h1>
    
    <h2>Add New Product</h2>
    <form method="post" action="">
        <label for="name">Item Name:</label>
        <input type="text" id="name" name="name" required>
        <label for="type">Item Type:</label>
        <input type="text" id="type" name="type" required>
        <label for="price">Item Price ($):</label>
        <input type="number" step="0.01" id="price" name="price" required>
        <label for="quantity">Quantity:</label>
        <input type="number" id="quantity" name="quantity" required>
        <label for="expiry_date">Expiry Date (YYYY-MM-DD):</label>
        <input type="text" id="expiry_date" name="expiry_date" required>
        <input type="submit" name="add_item" value="Add Item">
    </form>
    
    <h2>Update Product Quantity</h2>
    <form method="post" action="">
        <label for="name">Select Product:</label>
        <select id="name" name="name">
            <?php foreach ($groceryItems as $name => $details): ?>
                <option value="<?= $name ?>"><?= $name ?></option>
            <?php endforeach; ?>
        </select>
        <label for="quantity">New Quantity:</label>
        <input type="number" id="quantity" name="quantity" required>
        <input type="submit" name="update_quantity" value="Update Quantity">
    </form>
    
    <h2>Place Customer Order</h2>
    <form method="post" action="">
        <label for="customerName">Customer Name:</label>
        <input type="text" id="customerName" name="customerName" required>
        <fieldset>
            <legend>Select Products:</legend>
            <?php foreach ($groceryItems as $name => $details): ?>
                <input type="checkbox" id="<?= $name ?>" name="items[<?= $name ?>]">
                <label for="<?= $name ?>"><?= $name ?> (<?= $details['price'] ?> $ per unit)</label>
                <input type="number" id="quantity_<?= $name ?>" name="items[<?= $name ?>]" placeholder="Quantity">
            <?php endforeach; ?>
        </fieldset>
        <input type="submit" name="place_order" value="Place Order">
    </form>
    
    <h2>Search Inventory</h2>
    <form method="post" action="">
        <label for="searchTerm">Search Term:</label>
        <input type="text" id="searchTerm" name="searchTerm">
        <input type="submit" name="search" value="Search">
    </form>

    <?php if (isset($searchResults)): ?>
        <h2>Search Results</h2>
        <?php displayInventory($searchResults); ?>
    <?php endif; ?>

    <h2>Current Inventory</h2>
    <?php displayInventory($groceryItems); ?>
    
    <h2>Sales Report</h2>
    <?php displaySales($sales); ?>
</body>
</html>

<?php
$content = ob_get_clean();
echo $content;
?>
