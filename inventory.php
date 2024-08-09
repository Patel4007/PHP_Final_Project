<?php

$orders = [];
$sales = [];

define('INVENTORY_FILE', 'inventory.json');
define('UPLOAD_DIR', 'uploads/');


if (!function_exists('getInventory')) {
    function getInventory() {
        $inventory = [
            'groceryNames' => [],
            'groceryDetails' => []
        ];

        if (file_exists(UPLOAD_DIR)) {
            $files = scandir(UPLOAD_DIR);

            foreach ($files as $file) {
                if ($file !== '.' && $file !== '..') {
                    $filePath = UPLOAD_DIR . $file;

                    $data = parseFile($filePath);

                    if (!empty($data)) {
                        foreach ($data as $item) {
                            $productDetails = [
                                "product_id" => $item['product_id'] ?? null,
                                "product_name" => $item['product_name'] ?? null,
                                "category" => $item['category'] ?? null,
                                "price" => $item['price'] ?? 0,
                                "stock" => $item['stock'] ?? 0,
                                "expiry_date" => $item['expiry_date'] ?? date("Y-m-d")
                            ];

                            if ($productDetails['product_name']) {
                                $inventory['groceryNames'][] = $productDetails['product_name'];
                                $inventory['groceryDetails'][$productDetails['product_name']] = $productDetails;
                            }
                        }
                    }
                }
            }
        }

        if (empty($inventory['groceryDetails'])) {
            $inventory['groceryDetails'] = [
                "Bread" => ["product_name" => "Bread", "category" => "Food", "price" => 2.5, "stock" => 3, "expiry_date" => "2024-06-05"],
                "Milk" => ["product_name" => "Milk", "category" => "Food",  "price" => 1.8, "stock" => 2, "expiry_date" => "2024-06-03"]
            ];
        }

        return $inventory['groceryDetails'];
    }
}

$groceryItems = getInventory();

function saveInventory($inventory) {
    $json = json_encode($inventory, JSON_PRETTY_PRINT);
    file_put_contents(INVENTORY_FILE, $json);
}

function upload($json) {
    $inventory = getInventory();

    foreach ($json as $item) {
        $productDetails = [
            "product_id" => $item['product_id'],
            "product_name" => $item['product_name'],
            "category" => $item['category'],
            "price" => $item['price'],
            "stock" => $item['stock'],
            "expiry_date" => isset($item['expiry_date']) ? $item['expiry_date'] : date("Y-m-d")
        ];

        $inventory['groceryNames'][] = $productDetails['product_name'];
        $inventory['groceryDetails'][$productDetails['product_name']] = $productDetails;
    }

    saveInventory($inventory);

    return count($json);
}

function displayInventory($inventory) {
    echo "<h2>Current Inventory</h2>";
    echo "<table border='1' class='inventory-table'>";
    echo "<tr><th>Item Name</th><th>Type</th><th>Price ($)</th><th>Quantity</th><th>Expiry Date</th></tr>";

    foreach ($inventory as $name => $details) {
        echo "<tr>";
        echo "<td>{$details['product_name']}</td>";
        echo "<td>{$details['category']}</td>";
        echo "<td>{$details['price']}</td>";
        echo "<td>{$details['stock']}</td>";
        echo "<td>{$details['expiry_date']}</td>";
        echo "</tr>";
    }

    echo "</table>";
}



function addItem(&$groceryItems, $name, $type, $price, $quantity, $expiry_date) {
    $groceryItems[$name] = ["type" => $type, "price" => $price, "quantity" => $quantity, "expiry_date" => $expiry_date];
    saveInventory(["groceryNames" => array_keys($groceryItems), "groceryDetails" => $groceryItems]);
}

function updateQuantity(&$groceryItems, $name, $quantity) {
    if (isset($groceryItems[$name])) {
        $groceryItems[$name]['quantity'] = $quantity;
        saveInventory(["groceryNames" => array_keys($groceryItems), "groceryDetails" => $groceryItems]);
    }
}

function placeOrder(&$orders, &$groceryItems, &$sales, $customerName, $items) {
    $orderTotal = 0;
    foreach ($items as $item => $quantity) {
        if (isset($groceryItems[$item]) && is_numeric($groceryItems[$item]['price']) && is_numeric($quantity)) {
            $groceryItems[$item]['quantity'] -= $quantity;
            $orderTotal += $groceryItems[$item]['price'] * $quantity;
            addSale($sales, $item, $quantity, $groceryItems[$item]['price'] * $quantity);
            displayTotalSales($sales);
        }
    }
    $orders[] = ["customer" => $customerName, "items" => $items, "total" => $orderTotal];
}


function parseFile($filePath) {
    $fileContent = file_get_contents($filePath);
    $data = [];

    
    $jsonData = json_decode($fileContent, true);
    if ($jsonData !== null) {
        $data = $jsonData['products'] ?? [];
    } else {
        
        $xml = simplexml_load_file($filePath);
        if ($xml !== false) {
            $json = json_encode($xml);
            $dataArray = json_decode($json, true);
            $data = $dataArray['products'] ?? [];
        } else {
            
            $csvData = array_map('str_getcsv', file($filePath));
            if ($csvData !== false && count($csvData) > 1) {
                $keys = array_shift($csvData);
                foreach ($csvData as $row) {
                    $data[] = array_combine($keys, $row);
                }
            }
        }
    }

    return $data;
}

?>
