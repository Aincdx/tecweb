<?php
declare(strict_types=1);

$mysqli = new mysqli("localhost","root","","tecweb");
if ($mysqli->connect_errno) {
    die("Error MySQL: ".$mysqli->connect_error);
}
$mysqli->set_charset("utf8mb4");

$nombre = $_POST['nombre'] ?? '';
$modelo = $_POST['modelo'] ?? '';
$marca  = $_POST['marca'] ?? '';
$precio = $_POST['precio'] ?? 0;
$stock  = $_POST['stock'] ?? 0;
$descripcion = $_POST['descripcion'] ?? '';
$imagen = $_POST['imagen'] ?? '';

/* Validar */
if ($nombre==='' || $modelo==='' || $marca==='') {
    die("Faltan campos obligatorios.");
}

/* Duplicados */
$sql = "SELECT COUNT(*) FROM productos WHERE nombre=? AND modelo=? AND marca=?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("sss",$nombre,$modelo,$marca);
$stmt->execute();
$stmt->bind_result($c); $stmt->fetch(); $stmt->close();
if ($c>0) { die("Ya existe ese producto."); }

/* Insertar */
$sql = "INSERT INTO productos (nombre,modelo,marca,precio,stock,descripcion,imagen,eliminado)
        VALUES (?,?,?,?,?,?,?,0)";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("sssdis s",$nombre,$modelo,$marca,$precio,$stock,$descripcion,$imagen);
$stmt->execute();
$id = $stmt->insert_id;

/* Respuesta */
echo "<h1>Producto guardado</h1>";
echo "<p>ID: $id<br>Nombre: $nombre<br>Modelo: $modelo<br>Marca: $marca</p>";
