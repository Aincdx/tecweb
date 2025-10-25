<?php
include_once __DIR__.'/database.php';
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  echo json_encode(['ok'=>false,'msg'=>'Método no permitido']);
  exit;
}

if (function_exists('mysqli_set_charset')) {
  mysqli_set_charset($conexion, 'utf8mb4');
}

// Leer JSON
$raw = file_get_contents('php://input');
if (!$raw) { http_response_code(400); echo json_encode(['ok'=>false,'msg'=>'No se recibieron datos']); exit; }
$data = json_decode($raw, true);
if (!is_array($data)) { http_response_code(400); echo json_encode(['ok'=>false,'msg'=>'JSON inválido']); exit; }

// Campos
$id       = isset($data['id']) ? (int)$data['id'] : 0;  // si >0 => actualizar
$nombre   = isset($data['nombre']) ? trim($data['nombre']) : '';
$marca    = isset($data['marca']) ? trim($data['marca']) : '';
$precio   = isset($data['precio']) ? $data['precio'] : null;
$unidades = isset($data['unidades']) ? $data['unidades'] : null;
$detalles = isset($data['detalles']) ? trim($data['detalles']) : '';
$modelo   = isset($data['modelo']) ? trim($data['modelo']) : '';
$imagen   = isset($data['imagen']) ? trim($data['imagen']) : 'img/default.png';

// Validaciones comunes
$errores = [];
if ($nombre === '' || strlen($nombre) < 3 || strlen($nombre) > 80) $errores[] = 'El nombre debe tener entre 3 y 80 caracteres';
if ($marca  === '' || strlen($marca)  < 2 || strlen($marca)  > 50) $errores[] = 'La marca debe tener entre 2 y 50 caracteres';
if (!is_numeric($precio) || floatval($precio) <= 0)                 $errores[] = 'El precio debe ser un número mayor a 0';
if (!is_numeric($unidades) || intval($unidades) < 0)                $errores[] = 'Las unidades deben ser mayor o igual a 0';
if (strlen($detalles) > 255)                                        $errores[] = 'Los detalles no pueden exceder 255 caracteres';
if ($modelo !== '' && (strlen($modelo) < 1 || strlen($modelo) > 25))$errores[] = 'El modelo no puede exceder 25 caracteres y debe tener al menos 1';

if ($errores) {
  http_response_code(400);
  echo json_encode(['ok'=>false,'msg'=>implode(', ', $errores)]);
  exit;
}

// Normalizar
$precioF = floatval($precio);
$unidI   = intval($unidades);

// === MODO ACTUALIZAR ===
if ($id > 0) {
  // Existe y activo
  $stmt = $conexion->prepare("SELECT id FROM productos WHERE id = ? AND eliminado = 0");
  $stmt->bind_param("i", $id);
  if (!$stmt->execute()) { http_response_code(500); echo json_encode(['ok'=>false,'msg'=>'Error al verificar existencia: '.mysqli_error($conexion)]); exit; }
  $existe = $stmt->get_result()->num_rows > 0;
  $stmt->close();

  if (!$existe) { http_response_code(404); echo json_encode(['ok'=>false,'msg'=>'Producto no encontrado o eliminado']); exit; }

  // Duplicado por nombre excluyendo el mismo id
  $stmt = $conexion->prepare("SELECT 1 FROM productos WHERE nombre = ? AND eliminado = 0 AND id <> ? LIMIT 1");
  $stmt->bind_param("si", $nombre, $id);
  if (!$stmt->execute()) { http_response_code(500); echo json_encode(['ok'=>false,'msg'=>'Error al verificar duplicados: '.mysqli_error($conexion)]); exit; }
  $dup = $stmt->get_result()->num_rows > 0;
  $stmt->close();

  if ($dup) { http_response_code(409); echo json_encode(['ok'=>false,'msg'=>'Ya existe otro producto activo con ese nombre']); exit; }

  // Update
  $stmt = $conexion->prepare("
    UPDATE productos
    SET nombre = ?, marca = ?, modelo = ?, precio = ?, detalles = ?, unidades = ?, imagen = ?
    WHERE id = ? AND eliminado = 0
  ");
  $stmt->bind_param("sssdsisi", $nombre, $marca, $modelo, $precioF, $detalles, $unidI, $imagen, $id);

  if ($stmt->execute()) {
    $stmt->close(); $conexion->close();
    echo json_encode(['ok'=>true,'msg'=>'Producto actualizado','id'=>$id]);
  } else {
    $err = mysqli_error($conexion); $stmt->close(); $conexion->close();
    http_response_code(500); echo json_encode(['ok'=>false,'msg'=>'Error al actualizar: '.$err]);
  }
  exit;
}

// === MODO INSERTAR ===

// Duplicado por nombre activo
$stmt = $conexion->prepare("SELECT 1 FROM productos WHERE nombre = ? AND eliminado = 0 LIMIT 1");
$stmt->bind_param("s", $nombre);
if (!$stmt->execute()) { http_response_code(500); echo json_encode(['ok'=>false,'msg'=>'Error al verificar duplicados: '.mysqli_error($conexion)]); exit; }
$dup = $stmt->get_result()->num_rows > 0;
$stmt->close();

if ($dup) { http_response_code(409); echo json_encode(['ok'=>false,'msg'=>'Ya existe un producto activo con ese nombre']); exit; }

// Insert
$stmt = $conexion->prepare("
  INSERT INTO productos (nombre, marca, modelo, precio, detalles, unidades, imagen, eliminado)
  VALUES (?, ?, ?, ?, ?, ?, ?, 0)
");
$stmt->bind_param("sssdsid", $nombre, $marca, $modelo, $precioF, $detalles, $unidI, $imagen);

if ($stmt->execute()) {
  $nuevo_id = $conexion->insert_id;
  $stmt->close(); $conexion->close();
  http_response_code(201);
  echo json_encode(['ok'=>true,'msg'=>'Producto insertado correctamente','id'=>$nuevo_id]);
} else {
  $err = mysqli_error($conexion); $stmt->close(); $conexion->close();
  http_response_code(500); echo json_encode(['ok'=>false,'msg'=>'Error al insertar producto: '.$err]);
}
