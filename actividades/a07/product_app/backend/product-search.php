<?php
namespace MyAPI;

require_once __DIR__ . '/myapi/Products.php';

$producto = new Products('marketzone');
if (isset($_GET['search'])) {
    $producto->search($_GET['search']);
}
echo $producto->getData();
?>