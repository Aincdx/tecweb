<?php
    include_once __DIR__.'/database.php';

    // Configurar headers de respuesta
    header('Content-Type: application/json; charset=utf-8');
    http_response_code(200);

    // SE CREA EL ARREGLO QUE SE VA A DEVOLVER EN FORMA DE JSON
    $data = array();

    // Verificar si se recibió el parámetro de búsqueda 'q' por GET o POST
    $termino_busqueda = '';
    if (isset($_GET['q'])) {
        $termino_busqueda = trim($_GET['q']);
    } elseif (isset($_POST['q'])) {
        $termino_busqueda = trim($_POST['q']);
    }

    // Mantener compatibilidad con búsqueda por ID (funcionalidad original)
    if (isset($_POST['id'])) {
        $id = $_POST['id'];
        // SE REALIZA LA QUERY DE BÚSQUEDA POR ID
        $stmt = $conexion->prepare("SELECT * FROM productos WHERE id = ?");
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $row = $result->fetch_array(MYSQLI_ASSOC);

            if (!is_null($row)) {
                // SE CODIFICAN A UTF-8 LOS DATOS Y SE MAPEAN AL ARREGLO DE RESPUESTA
                foreach($row as $key => $value) {
                    $data[$key] = utf8_encode($value);
                }
            }
        } else {
            http_response_code(500);
            echo json_encode(array('error' => 'Error en la consulta SQL: ' . mysqli_error($conexion)));
            exit;
        }
        $stmt->close();
    }
    // Nueva funcionalidad: búsqueda flexible por nombre, marca o detalles
    elseif (!empty($termino_busqueda)) {
        // Normalizar el término de búsqueda para evitar inyección SQL
        if (strlen($termino_busqueda) < 1) {
            // Devolver array vacío si el término es muy corto, pero mantener 200
            echo json_encode($data);
            $conexion->close();
            exit;
        }

        // Preparar consulta con LIKE para búsqueda parcial
        $termino_like = '%' . $termino_busqueda . '%';
        $stmt = $conexion->prepare("SELECT id, nombre, marca, detalles, precio, unidades, eliminado FROM productos WHERE (nombre LIKE ? OR marca LIKE ? OR detalles LIKE ?) AND eliminado = 0");
        $stmt->bind_param("sss", $termino_like, $termino_like, $termino_like);
        
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            
            // Recopilar todos los resultados en un array
            while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
                $producto = array();
                foreach($row as $key => $value) {
                    $producto[$key] = utf8_encode($value);
                }
                $data[] = $producto;
            }
        } else {
            http_response_code(500);
            echo json_encode(array('error' => 'Error en la consulta SQL: ' . mysqli_error($conexion)));
            exit;
        }
        $stmt->close();
    }

    $conexion->close();
    
    // SE HACE LA CONVERSIÓN DE ARRAY A JSON (siempre devuelve array, incluso si está vacío)
    echo json_encode($data, JSON_PRETTY_PRINT);
?>