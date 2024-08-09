<?php
include 'process_item.php';
include 'forms.php';

$page = isset($_GET['page']) ? $_GET['page'] : 'inventory';

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
        .inventory-table, .sales-table {
            margin-top: 20px;
        }
        .nav-menu {
            margin-bottom: 20px;
        }
        .nav-menu a {
            margin-right: 10px;
            text-decoration: none;
            color: #4CAF50;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function() {
        // Event handler for the link
        $("#loadDataTransfer").click(function() {
            $("#content").load("data_transfer.html");
            return false; // Prevent default link behavior
        });
    });
    </script>
</head>
<body>
    <h1>Grocery Store Management System</h1>
    <div class="nav-menu">
        <a href="?page=add_item">Add New Product</a>
        <a href="?page=update_quantity">Update Product Quantity</a>
        <a href="?page=place_order">Place Customer Order</a>
        <a href="?page=search">Search Inventory</a>
        <a href="?page=inventory">Current Inventory</a>
        <a href="#" id="loadDataTransfer">Upload from File</a>
        <a href="?page=sales">Sales Report</a>
    </div>

    <div id="content">
        <?php
        switch ($page) {
            case 'add_item':
                renderAddItemForm();
                break;
            case 'update_quantity':
                renderUpdateQuantityForm($groceryItems);
                break;
            case 'place_order':
                renderPlaceOrderForm($groceryItems);
                break;
            case 'search':
                if (isset($_POST['search'])) {
                    renderSearchForm();
                    $searchTerm = $_POST['searchTerm'];
                    $searchResults = searchItem($groceryItems, $searchTerm);
                    if (empty($searchResults)) {
                        echo "<p>No results found for '{$searchTerm}'</p>";
                    } else {
                        displayInventory($searchResults);
                    }
                } else {
                    renderSearchForm();
                }
                break;
            case 'data_transfer':
                
                echo '<p id="content"></p>';
                break;
            case 'sales':
                displayTotalSales($sales);
                break;
            case 'inventory':
            default:
                displayInventory($groceryItems);
                break;
        }
        ?>
    </div>
</body>
</html>

<?php
$content = ob_get_clean();
echo $content;
?>
