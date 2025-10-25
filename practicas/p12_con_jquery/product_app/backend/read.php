<?php
include_once __DIR__.'/database.php';
header('Content-Type: application/json; charset=utf-8');

// Asegura UTF-8 real en la conexión
if (function_exists('mysqli_set_charset')) {
    mysqli_set_charset($conexion, 'utf8mb4');
}

$data = [];

// Entrada
$id_param = isset($_POST['id']) ? $_POST['id'] : (isset($_GET['id']) ? $_GET['id'] : null);
$q_param  = isset($_GET['q']) ? trim($_GET['q']) : (isset($_POST['q']) ? trim($_POST['q']) : '');

// --- 1) BÚSQUEDA POR ID (POST/GET). Respuesta: [fila] o [] ---
if ($id_param !== null && $id_param !== '') {
    $id = (int)$id_param;

    $stmt = $conexion->prepare("
        SELECT id, nombre, marca, detalles, precio, unidades, eliminado
        FROM productos
        WHERE id = ? AND eliminado = 0
        LIMIT 1
    ");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $res = $stmt->get_result();
        if ($row = $res->fetch_assoc()) $data[] = $row;
    } else {
        http_response_code(500);
        echo json_encode(['error'=>'Error SQL: '.mysqli_error($conexion)]);
        exit;
    }
    $stmt->close();
}
// --- 2) BÚSQUEDA FLEXIBLE q: por id exacto o LIKE en nombre/marca/detalles ---
elseif ($q_param !== '') {
    $like = '%'.$q_param.'%';
    $idq  = ctype_digit($q_param) ? (int)$q_param : -1; // -1 no coincide con ningún id

    $stmt = $conexion->prepare("
        SELECT id, nombre, marca, detalles, precio, unidades, eliminado
        FROM productos
        WHERE eliminado = 0
          AND (id = ? OR nombre LIKE ? OR marca LIKE ? OR detalles LIKE ?)
        ORDER BY id DESC
    ");
    $stmt->bind_param("isss", $idq, $like, $like, $like);

    if ($stmt->execute()) {
        $res = $stmt->get_result();
        while ($row = $res->fetch_assoc()) $data[] = $row;
    } else {
        http_response_code(500);
        echo json_encode(['error'=>'Error SQL: '.mysqli_error($conexion)]);
        exit;
    }
    $stmt->close();
}
// --- 3) LISTAR TODOS LOS NO ELIMINADOS ---
else {
    $sql = "
        SELECT id, nombre, marca, detalles, precio, unidades, eliminado
        FROM productos
        WHERE eliminado = 0
        ORDER BY id DESC
    ";
    $res = $conexion->query($sql);
    if ($res) {
        while ($row = $res->fetch_assoc()) $data[] = $row;
    } else {
        http_response_code(500);
        echo json_encode(['error'=>'Error SQL: '.mysqli_error($conexion)]);
        exit;
    }
}

$conexion->close();
echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
