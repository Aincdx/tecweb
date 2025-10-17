<?php
    include_once __DIR__.'/database.php';

    // Configurar headers de respuesta
    header('Content-Type: application/json; charset=utf-8');

    // Verificar método de petición
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(array('ok' => false, 'msg' => 'Método no permitido'));
        exit;
    }

    // SE OBTIENE LA INFORMACIÓN DEL PRODUCTO ENVIADA POR EL CLIENTE
    $producto = file_get_contents('php://input');
    
    if (empty($producto)) {
        http_response_code(400);
        echo json_encode(array('ok' => false, 'msg' => 'No se recibieron datos'));
        exit;
    }

    // SE TRANSFORMA EL STRING DEL JSON A OBJETO
    $jsonOBJ = json_decode($producto, true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        http_response_code(400);
        echo json_encode(array('ok' => false, 'msg' => 'JSON inválido'));
        exit;
    }

    // VALIDACIONES DEL SERVIDOR
    $errores = array();

    // Validar nombre (obligatorio, 3-80 caracteres)
    if (!isset($jsonOBJ['nombre']) || empty(trim($jsonOBJ['nombre']))) {
        $errores[] = 'El nombre es obligatorio';
    } elseif (strlen(trim($jsonOBJ['nombre'])) < 3 || strlen(trim($jsonOBJ['nombre'])) > 80) {
        $errores[] = 'El nombre debe tener entre 3 y 80 caracteres';
    }

    // Validar marca (obligatorio, 2-50 caracteres)
    if (!isset($jsonOBJ['marca']) || empty(trim($jsonOBJ['marca']))) {
        $errores[] = 'La marca es obligatoria';
    } elseif (strlen(trim($jsonOBJ['marca'])) < 2 || strlen(trim($jsonOBJ['marca'])) > 50) {
        $errores[] = 'La marca debe tener entre 2 y 50 caracteres';
    }

    // Validar precio (obligatorio, numérico > 0)
    if (!isset($jsonOBJ['precio']) || !is_numeric($jsonOBJ['precio'])) {
        $errores[] = 'El precio debe ser un número válido';
    } elseif (floatval($jsonOBJ['precio']) <= 0) {
        $errores[] = 'El precio debe ser mayor a 0';
    }

    // Validar unidades (obligatorio, entero >= 0)
    if (!isset($jsonOBJ['unidades']) || !is_numeric($jsonOBJ['unidades'])) {
        $errores[] = 'Las unidades deben ser un número válido';
    } elseif (intval($jsonOBJ['unidades']) < 0) {
        $errores[] = 'Las unidades deben ser mayor o igual a 0';
    }

    // Validar detalles (opcional, máximo 255 caracteres)
    if (isset($jsonOBJ['detalles']) && strlen($jsonOBJ['detalles']) > 255) {
        $errores[] = 'Los detalles no pueden exceder 255 caracteres';
    }

    // Validar modelo (opcional, máximo 25 caracteres)
    if (isset($jsonOBJ['modelo']) && strlen($jsonOBJ['modelo']) > 25) {
        $errores[] = 'El modelo no puede exceder 25 caracteres';
    }

    // Si hay errores de validación, devolverlos
    if (!empty($errores)) {
        http_response_code(400);
        echo json_encode(array('ok' => false, 'msg' => implode(', ', $errores)));
        exit;
    }

    // Normalizar valores
    $nombre = trim($jsonOBJ['nombre']);
    $marca = trim($jsonOBJ['marca']);
    $precio = floatval($jsonOBJ['precio']);
    $unidades = intval($jsonOBJ['unidades']);
    $detalles = isset($jsonOBJ['detalles']) ? trim($jsonOBJ['detalles']) : '';
    $modelo = isset($jsonOBJ['modelo']) ? trim($jsonOBJ['modelo']) : '';
    $imagen = isset($jsonOBJ['imagen']) ? trim($jsonOBJ['imagen']) : 'img/default.png';

    // VERIFICAR DUPLICADO: producto con mismo nombre y eliminado = 0
    $stmt = $conexion->prepare("SELECT 1 FROM productos WHERE nombre = ? AND eliminado = 0 LIMIT 1");
    $stmt->bind_param("s", $nombre);
    
    if (!$stmt->execute()) {
        http_response_code(500);
        echo json_encode(array('ok' => false, 'msg' => 'Error al verificar duplicados: ' . mysqli_error($conexion)));
        exit;
    }

    $result = $stmt->get_result();
    if ($result->fetch_array()) {
        $stmt->close();
        $conexion->close();
        http_response_code(409);
        echo json_encode(array('ok' => false, 'msg' => 'Ya existe un producto activo con ese nombre'));
        exit;
    }
    $stmt->close();

    // INSERTAR NUEVO PRODUCTO
    $stmt = $conexion->prepare("INSERT INTO productos (nombre, marca, modelo, precio, detalles, unidades, imagen, eliminado) VALUES (?, ?, ?, ?, ?, ?, ?, 0)");
    $stmt->bind_param("sssdsid", $nombre, $marca, $modelo, $precio, $detalles, $unidades, $imagen);

    if ($stmt->execute()) {
        $nuevo_id = $conexion->insert_id;
        $stmt->close();
        $conexion->close();
        
        http_response_code(201);
        echo json_encode(array(
            'ok' => true, 
            'msg' => 'Producto insertado correctamente',
            'id' => $nuevo_id
        ));
    } else {
        $error_msg = mysqli_error($conexion);
        $stmt->close();
        $conexion->close();
        
        http_response_code(500);
        echo json_encode(array('ok' => false, 'msg' => 'Error al insertar producto: ' . $error_msg));
    }
?>