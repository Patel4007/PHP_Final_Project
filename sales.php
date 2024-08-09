<?php


    function addSale(&$sales, $item, $quantity, $total) {
        $sales[] = ["item" => $item, "quantity" => $quantity, "total" => $total];
    }



    function calculateTotalSales($sales) {
        $totalSales = 0;
        foreach ($sales as $sale) {
            $totalSales += $sale['total'];
        }
        return $totalSales;
    }


    function displayTotalSales($sales) {
        echo "<h2>Sales Report</h2>";
        echo "<table border='1' class='sales-table'>";
        echo "<tr><th>Item Name</th><th>Quantity</th><th>Total ($)</th></tr>";
    
        foreach ($sales as $sale) {
            echo "<tr>";
            echo "<td>{$sale['item']}</td>";
            echo "<td>{$sale['quantity']}</td>";
            echo "<td>{$sale['total']}</td>";
            echo "</tr>";
        }
    
        echo "</table>";
    }

?>
