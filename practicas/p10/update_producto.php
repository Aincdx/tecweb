<?php
/* MySQL Conexion*/
$link = mysqli_connect("localhost", "root", "", "marketzone");

// Variables para el mensaje
$success = false;
$error_message = '';
$producto_nombre = '';

// Chequea conexion
if($link === false){
    $error_message = "ERROR: No pudo conectarse con la DB. " . mysqli_connect_error();
} else {
    // Obtener datos del POST
    $id = $_POST['id'] ?? '';
    $nombre = $_POST['nombre'] ?? '';
    $marca = $_POST['marca'] ?? '';
    $modelo = $_POST['modelo'] ?? '';
    $precio = $_POST['precio'] ?? '';
    $unidades = $_POST['unidades'] ?? '';
    $detalles = $_POST['detalles'] ?? '';
    $imagen = $_POST['imagen'] ?? '';

    // Validar que se recibieron los datos necesarios
    if(empty($id) || empty($nombre) || empty($marca) || empty($modelo) || empty($precio) || $unidades === ''){
        $error_message = "ERROR: Faltan datos requeridos para actualizar el producto.";
    } else {
        // Ejecuta la actualización del registro usando prepared statement
        $sql = "UPDATE productos SET nombre=?, marca=?, modelo=?, precio=?, unidades=?, detalles=?, imagen=? WHERE id=?";
        $stmt = mysqli_prepare($link, $sql);

        if($stmt){
            mysqli_stmt_bind_param($stmt, "sssdissi", $nombre, $marca, $modelo, $precio, $unidades, $detalles, $imagen, $id);
            
            if(mysqli_stmt_execute($stmt)){
                $success = true;
                $producto_nombre = $nombre;
            } else {
                $error_message = "ERROR: No se pudo actualizar el registro. " . mysqli_stmt_error($stmt);
            }
            
            mysqli_stmt_close($stmt);
        } else {
            $error_message = "ERROR: No se pudo preparar la consulta. " . mysqli_error($link);
        }
    }
    
    // Cierra la conexion
    mysqli_close($link);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $success ? 'Producto Actualizado' : 'Error en Actualización' ?> - Tienda de Electrónicos</title>
    <style>
        body {
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .result-container {
            background: #ffffff;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
            text-align: center;
            max-width: 500px;
            width: 90%;
            animation: slideUp 0.5s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .success-icon, .error-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px auto;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
            color: white;
            font-weight: bold;
        }

        .success-icon {
            background: linear-gradient(135deg, #4CAF50, #45a049);
        }

        .error-icon {
            background: linear-gradient(135deg, #f44336, #da190b);
        }

        h1 {
            margin: 0 0 15px 0;
            color: #333;
            font-size: 28px;
            font-weight: 600;
        }

        .success h1 {
            color: #4CAF50;
        }

        .error h1 {
            color: #f44336;
        }

        .message {
            color: #666;
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 30px;
        }

        .product-info {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 12px;
            margin: 20px 0;
            border-left: 4px solid #4CAF50;
        }

        .product-info h3 {
            margin: 0 0 10px 0;
            color: #333;
            font-size: 18px;
        }

        .product-details {
            color: #666;
            font-size: 14px;
        }

        .actions {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: white;
        }

        .btn-secondary {
            background: linear-gradient(135deg, #6b7280, #4b5563);
            color: white;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.2);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #2563eb, #1e40af);
        }

        .btn-secondary:hover {
            background: linear-gradient(135deg, #4b5563, #374151);
        }

        .error-details {
            background: #fff5f5;
            color: #dc2626;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #dc2626;
            margin: 20px 0;
            font-family: monospace;
            font-size: 14px;
            text-align: left;
        }
    </style>
</head>
<body>
    <div class="result-container">
        <?php if ($success): ?>
            <div class="success-icon">✓</div>
            <div class="success">
                <h1>¡Producto Actualizado!</h1>
                <div class="message">
                    El producto se ha actualizado correctamente en la base de datos.
                </div>
                
                <div class="product-info">
                    <h3>📦 Producto: <?= htmlspecialchars($producto_nombre) ?></h3>
                    <div class="product-details">
                        ID: <?= htmlspecialchars($id) ?> | 
                        Marca: <?= htmlspecialchars($marca) ?> | 
                        Modelo: <?= htmlspecialchars($modelo) ?>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="error-icon">✕</div>
            <div class="error">
                <h1>Error en la Actualización</h1>
                <div class="message">
                    No se pudo actualizar el producto. Por favor, verifica los datos e intenta nuevamente.
                </div>
                
                <div class="error-details">
                    <?= htmlspecialchars($error_message) ?>
                </div>
            </div>
        <?php endif; ?>

        <div class="actions">
            <a href="get_productos_vigentes_v2.php" class="btn btn-primary">
                ← Volver a Productos
            </a>
            <a href="formulario_productos.html" class="btn btn-secondary">
                + Agregar Producto
            </a>
        </div>
    </div>

    <script>
        // Auto redirección después de 5 segundos en caso de éxito
        <?php if ($success): ?>
        setTimeout(function() {
            window.location.href = 'get_productos_vigentes_v2.php';
        }, 5000);
        <?php endif; ?>
    </script>
</body>
</html>