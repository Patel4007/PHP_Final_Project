<?php

function searchItem($groceryItems, $searchTerm) {
    $result = [];
    foreach ($groceryItems as $name => $details) {
        if (stripos($name, $searchTerm) !== false) {
            $result[$name] = $details;
        }
    }
    return $result;
}
?>