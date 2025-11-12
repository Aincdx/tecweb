<?php
namespace MyAPI;

require_once __DIR__ . '/myapi/Products.php';

$producto = new Products('marketzone');
$producto->add($_POST);
echo $producto->getData();
?>