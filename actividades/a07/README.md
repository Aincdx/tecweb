# Actividad 7 - Programación Orientada a Objetos del lado del Servidor

## Estructura Implementada

### 1. Clases Base (backend/myapi/)

#### `DataBase.php` - Clase Abstracta
- **Namespace**: `MyAPI`
- **Propósito**: Manejar la conexión a la base de datos MySQL
- **Características**:
  - Constructor recibe parámetros: `$db`, `$user` (default: 'root'), `$pass` (default: ''), `$host` (default: 'localhost')
  - Atributo protegido `$conexion` para mantener la conexión
  - Método `getConexion()` para acceder a la conexión
  - Destructor para cerrar la conexión automáticamente
  - Configuración automática del charset a UTF-8

#### `Products.php` - Clase Concreta
- **Namespace**: `MyAPI`
- **Herencia**: Extiende la clase `DataBase`
- **Atributo privado**: `$data` para almacenar resultados
- **Métodos**:
  - `list()` - Lista todos los productos activos
  - `add($producto)` - Agrega un nuevo producto
  - `delete($id)` - Elimina un producto (marca como eliminado)
  - `edit($producto)` - Edita un producto existente
  - `search($search)` - Busca productos por ID, nombre, marca o detalles
  - `single($id)` - Obtiene un producto por su ID
  - `singleByName($name)` - Obtiene un producto por su nombre
  - `getData()` - Convierte el array de datos a JSON

### 2. Archivos Backend Refactorizados

Cada archivo backend ahora sigue esta estructura (5 líneas principales):

```php
<?php
namespace MyAPI;                                    // 1. Uso del namespace
require_once __DIR__ . '/myapi/Products.php';      // 2. Inclusión de la clase
$producto = new Products('marketzone');            // 3. Instanciación
$producto->list();                                 // 4. Invocación del método
echo $producto->getData();                         // 5. Respuesta en JSON
?>
```

#### Archivos modificados:
- `product-list.php` - Lista productos
- `product-add.php` - Agrega productos
- `product-search.php` - Busca productos
- `product-single.php` - Obtiene un producto por ID
- `product-delete.php` - Elimina productos
- `product-edit.php` - Edita productos

### 3. Frontend (Sin cambios)

El frontend (`index.html` y `app.js`) permanece sin cambios, mantiene compatibilidad con la API JSON.


## Diagrama de Clases UML

```
┌─────────────────────────────────────┐
│      <<abstract>> DataBase          │
├─────────────────────────────────────┤
│ #conexion: mysqli                   │
├─────────────────────────────────────┤
│ +DataBase(db, user, pass, host)     │
│ +getConexion(): mysqli              │
└─────────────────────────────────────┘
          △
          │ hereda
          │
┌─────────────────────────────────────┐
│         Products                    │
├─────────────────────────────────────┤
│ -data: array[]                      │
├─────────────────────────────────────┤
│ +Products(db, user, pass, host)     │
│ +add(Object): void                  │
│ +delete(string): void               │
│ +edit(Object): void                 │
│ +list(): void                       │
│ +search(string): void               │
│ +single(string): void               │
│ +singleByName(string): void         │
│ +getData(): string                  │
└─────────────────────────────────────┘
```
