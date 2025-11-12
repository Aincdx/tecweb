<?php
namespace MyAPI;

require_once __DIR__ . '/DataBase.php';

/**
 * Clase Products
 * Maneja todas las operaciones CRUD de productos
 */
class Products extends DataBase {
    /**
     * @var array Arreglo de respuesta
     */
    private $data;

    /**
     * Constructor de la clase Products
     * 
     * @param string $db Nombre de la base de datos
     * @param string $user Usuario de MySQL (default: 'root')
     * @param string $pass Contraseña de MySQL (default: '')
     * @param string $host Host del servidor (default: 'localhost')
     */
    public function __construct($db, $user = 'root', $pass = '', $host = 'localhost') {
        // Inicializar el atributo response como array vacío
        $this->data = array();

        // Llamar al constructor de la superclase (DataBase)
        parent::__construct($db, $user, $pass, $host);
    }

    /**
     * Listar todos los productos activos (no eliminados)
     * 
     * @return void Almacena resultado en $this->data
     */
    public function list() {
        $this->data = array();
        
        $sql = "SELECT * FROM productos WHERE eliminado = 0";
        
        if ($result = $this->conexion->query($sql)) {
            $rows = $result->fetch_all(MYSQLI_ASSOC);

            if (!is_null($rows)) {
                foreach ($rows as $num => $row) {
                    foreach ($row as $key => $value) {
                        $this->data[$num][$key] = utf8_encode($value);
                    }
                }
            }
            $result->free();
        } else {
            $this->data = array('error' => 'Query Error: ' . mysqli_error($this->conexion));
        }
    }

    /**
     * Agregar un nuevo producto
     * 
     * @param array $producto Arreglo con datos del producto (nombre, marca, modelo, precio, detalles, unidades, imagen)
     * @return void Almacena resultado en $this->data
     */
    public function add($producto) {
        $this->data = array(
            'status'  => 'error',
            'message' => 'Ya existe un producto con ese nombre'
        );

        // Validar que se recibieron los datos
        if (isset($producto['nombre'])) {
            // Verificar que el producto no existe
            $sql = "SELECT * FROM productos WHERE nombre = '{$producto['nombre']}' AND eliminado = 0";
            $result = $this->conexion->query($sql);

            if ($result->num_rows == 0) {
                // Insertar el nuevo producto
                $nombre = $this->conexion->real_escape_string($producto['nombre']);
                $marca = $this->conexion->real_escape_string($producto['marca']);
                $modelo = $this->conexion->real_escape_string($producto['modelo']);
                $precio = floatval($producto['precio']);
                $detalles = $this->conexion->real_escape_string($producto['detalles']);
                $unidades = intval($producto['unidades']);
                $imagen = $this->conexion->real_escape_string($producto['imagen']);

                $sql = "INSERT INTO productos VALUES (null, '{$nombre}', '{$marca}', '{$modelo}', {$precio}, '{$detalles}', {$unidades}, '{$imagen}', 0)";

                if ($this->conexion->query($sql)) {
                    $this->data['status'] = "success";
                    $this->data['message'] = "Producto agregado";
                } else {
                    $this->data['message'] = "ERROR: No se ejecutó la inserción. " . mysqli_error($this->conexion);
                }
            }

            $result->free();
        }
    }

    /**
     * Eliminar un producto (marcar como eliminado)
     * 
     * @param int $id ID del producto a eliminar
     * @return void Almacena resultado en $this->data
     */
    public function delete($id) {
        $this->data = array(
            'status'  => 'error',
            'message' => 'La consulta falló'
        );

        if (!empty($id)) {
            $id = intval($id);
            $sql = "UPDATE productos SET eliminado=1 WHERE id = {$id}";

            if ($this->conexion->query($sql)) {
                $this->data['status'] = "success";
                $this->data['message'] = "Producto eliminado";
            } else {
                $this->data['message'] = "ERROR: No se ejecutó la actualización. " . mysqli_error($this->conexion);
            }
        }
    }

    /**
     * Editar un producto existente
     * 
     * @param array $producto Arreglo con datos del producto incluyendo id
     * @return void Almacena resultado en $this->data
     */
    public function edit($producto) {
        $this->data = array(
            'status'  => 'error',
            'message' => 'La consulta falló'
        );

        if (isset($producto['id'])) {
            $id = intval($producto['id']);
            $nombre = $this->conexion->real_escape_string($producto['nombre']);
            $marca = $this->conexion->real_escape_string($producto['marca']);
            $modelo = $this->conexion->real_escape_string($producto['modelo']);
            $precio = floatval($producto['precio']);
            $detalles = $this->conexion->real_escape_string($producto['detalles']);
            $unidades = intval($producto['unidades']);
            $imagen = $this->conexion->real_escape_string($producto['imagen']);

            $sql = "UPDATE productos SET nombre='{$nombre}', marca='{$marca}', ";
            $sql .= "modelo='{$modelo}', precio={$precio}, detalles='{$detalles}', ";
            $sql .= "unidades={$unidades}, imagen='{$imagen}' WHERE id={$id}";

            if ($this->conexion->query($sql)) {
                $this->data['status'] = "success";
                $this->data['message'] = "Producto actualizado";
            } else {
                $this->data['message'] = "ERROR: No se ejecutó la actualización. " . mysqli_error($this->conexion);
            }
        }
    }

    /**
     * Buscar productos por ID, nombre, marca o detalles
     * 
     * @param string $search Términos de búsqueda
     * @return void Almacena resultado en $this->data
     */
    public function search($search) {
        $this->data = array();

        if (!empty($search)) {
            $search = $this->conexion->real_escape_string($search);
            $sql = "SELECT * FROM productos WHERE (id = '{$search}' OR nombre LIKE '%{$search}%' OR marca LIKE '%{$search}%' OR detalles LIKE '%{$search}%') AND eliminado = 0";

            if ($result = $this->conexion->query($sql)) {
                $rows = $result->fetch_all(MYSQLI_ASSOC);

                if (!is_null($rows)) {
                    foreach ($rows as $num => $row) {
                        foreach ($row as $key => $value) {
                            $this->data[$num][$key] = utf8_encode($value);
                        }
                    }
                }
                $result->free();
            } else {
                $this->data = array('error' => 'Query Error: ' . mysqli_error($this->conexion));
            }
        }
    }

    /**
     * Obtener un producto por su ID
     * 
     * @param int $id ID del producto
     * @return void Almacena resultado en $this->data
     */
    public function single($id) {
        $this->data = array();

        if (!empty($id)) {
            $id = intval($id);
            $sql = "SELECT * FROM productos WHERE id = {$id}";

            if ($result = $this->conexion->query($sql)) {
                $row = $result->fetch_assoc();

                if (!is_null($row)) {
                    foreach ($row as $key => $value) {
                        $this->data[$key] = utf8_encode($value);
                    }
                }
                $result->free();
            } else {
                $this->data = array('error' => 'Query Error: ' . mysqli_error($this->conexion));
            }
        }
    }

    /**
     * Obtener un producto por su nombre
     * 
     * @param string $name Nombre del producto
     * @return void Almacena resultado en $this->data
     */
    public function singleByName($name) {
        $this->data = array();

        if (!empty($name)) {
            $name = $this->conexion->real_escape_string($name);
            $sql = "SELECT * FROM productos WHERE nombre = '{$name}' AND eliminado = 0";

            if ($result = $this->conexion->query($sql)) {
                $row = $result->fetch_assoc();

                if (!is_null($row)) {
                    foreach ($row as $key => $value) {
                        $this->data[$key] = utf8_encode($value);
                    }
                }
                $result->free();
            } else {
                $this->data = array('error' => 'Query Error: ' . mysqli_error($this->conexion));
            }
        }
    }

    /**
     * Convertir el arreglo de datos a JSON
     * 
     * @return string Datos convertidos a JSON
     */
    public function getData() {
        return json_encode($this->data, JSON_PRETTY_PRINT);
    }
}
?>
