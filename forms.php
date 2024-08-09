<?php

function renderAddItemForm() {
    echo '
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
    ';
}

function renderUpdateQuantityForm($groceryItems) {
    echo '
    <h2>Update Product Quantity</h2>
    <form method="post" action="">
        <label for="name">Select Product:</label>
        <select id="name" name="name">
    ';
    foreach ($groceryItems as $name => $details) {
        echo '<option value="' . $name . '">' . $name . '</option>';
    }
    echo '
        </select>
        <label for="quantity">New Quantity:</label>
        <input type="number" id="quantity" name="quantity" required>
        <input type="submit" name="update_quantity" value="Update Quantity">
    </form>
    ';
}

function renderPlaceOrderForm($groceryItems) {
    echo '
    <h2>Place Customer Order</h2>
    <form method="post" action="">
        <label for="customerName">Customer Name:</label>
        <input type="text" id="customerName" name="customerName" required>
        <fieldset>
            <legend>Select Products:</legend>
    ';
    foreach ($groceryItems as $name => $details) {
        echo '
            <label for="' . $name . '">' . $name . ' (' . $details['price'] . ' $ per unit)</label>
            <input type="number" id="' . $name . '" name="quantities[' . $name . ']" placeholder="Quantity" min="0">
            <br>
        ';
    }
    echo '
        </fieldset>
        <input type="submit" name="place_order" value="Place Order">
    </form>
    ';
}


function renderSearchForm() {
    echo '
    <h2>Search Inventory</h2>
    <form method="post" action="">
        <label for="searchTerm">Search Term:</label>
        <input type="text" id="searchTerm" name="searchTerm" required>
        <input type="submit" name="search" value="Search">
    </form>
    ';
}

?>