<?php
namespace MyAPI;

require_once __DIR__ . '/myapi/Products.php';

$producto = new Products('marketzone');
if (isset($_POST['id'])) {
    $producto->single($_POST['id']);
}
echo $producto->getData();
?>