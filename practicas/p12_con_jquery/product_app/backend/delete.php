<?php
include_once __DIR__.'/database.php';
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'msg' => 'Método no permitido']);
    exit;
}

if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'msg' => 'ID inválido']);
    exit;
}
$id = (int)$_POST['id'];

// Verificar que el producto exista y no esté ya eliminado
$stmt = $conexion->prepare("SELECT id FROM productos WHERE id = ? AND eliminado = 0");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    $stmt->close();
    $conexion->close();
    http_response_code(404);
    echo json_encode(['ok' => false, 'msg' => 'Producto no encontrado o ya eliminado']);
    exit;
}
$stmt->close();

// Marcar como eliminado (1)
$stmt = $conexion->prepare("UPDATE productos SET eliminado = 1 WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo json_encode(['ok' => true, 'msg' => 'Producto eliminado correctamente']);
} else {
    http_response_code(500);
    echo json_encode(['ok' => false, 'msg' => 'Error al eliminar: '.mysqli_error($conexion)]);
}

$stmt->close();
$conexion->close();