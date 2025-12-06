<?php
namespace MyAPI;


abstract class DataBase {
    /**
     * @var mysqli Conexión protegida a la base de datos
     */
    protected $conexion;

    /**
     * Constructor de la clase DataBase
     * 
     * @param string $db 
     * @param string $user 
     * @param string $pass
     * @param string $host 
     */
    public function __construct($db, $user = 'root', $pass = '', $host = 'localhost') {
        // Intenta conectarse a la base de datos
        $this->conexion = @mysqli_connect($host, $user, $pass, $db);

        // Verifica si la conexión fue exitosa
        if (!$this->conexion) {
            die('¡Base de datos NO conectada! Error: ' . mysqli_connect_error());
        }

        // Configura el charset a UTF-8
        $this->conexion->set_charset("utf8");
    }

    /**
     * Método para obtener la conexión
     * 
     * @return mysqli Objeto de conexión
     */
    public function getConexion() {
        return $this->conexion;
    }

    /**
     * Destructor para cerrar la conexión cuando el objeto se destruye
     */
    public function __destruct() {
        if ($this->conexion) {
            $this->conexion->close();
        }
    }
}
?>
