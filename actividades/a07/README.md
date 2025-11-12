# Actividad 7 - Programación Orientada a Objetos del lado del Servidor

## Objetivo
Familiarizarse con la sintaxis y funcionalidad de la Programación Orientada a Objetos (POO) del lado del servidor, mejorando la aplicación Web para la gestión de productos.

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

## Ventajas de la Implementación POO

1. **Reutilización de código**: La lógica de base de datos se centraliza en una clase
2. **Mantenibilidad**: Cambios en la BD solo requieren actualizar `DataBase.php` y `Products.php`
3. **Escalabilidad**: Fácil agregar nuevas clases que hereden de `DataBase`
4. **Seguridad**: Uso de `real_escape_string()` para prevenir SQL injection
5. **Organización**: Código limpio y estructurado con namespaces

## Instrucciones de Uso

### Requisitos
- Servidor XAMPP con MySQL
- Base de datos `marketzone` con tabla `productos`

### Pruebas de Funcionamiento

1. **Listar productos**: Accede a `http://localhost/tecweb/actividades/a07/product_app/`
2. **Agregar producto**: Usa el formulario en la aplicación
3. **Buscar producto**: Utiliza la barra de búsqueda
4. **Editar producto**: Haz clic en un producto para editarlo
5. **Eliminar producto**: Usa el botón de eliminar

## Nota Importante

El código comentado ha sido removido después de verificar el funcionamiento correcto de todas las operaciones CRUD. Si necesitas ver el código anterior (procedural), consulta la práctica `p12`.

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

---
**Autor**: Actividad 7 - POO del lado del servidor
**Fecha**: 2025
**Versión**: 1.0
