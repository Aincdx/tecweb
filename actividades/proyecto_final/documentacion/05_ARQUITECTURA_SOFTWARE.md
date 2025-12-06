# DOCUMENTO DE INGENIERÍA DE SOFTWARE - PARTE 5

## Dashboard para la Gestión de Recursos Digitales

---

# 9. ARQUITECTURA DE SOFTWARE Y COMUNICACIÓN FRONT-BACK

## 9.1 Estilo Arquitectónico

El sistema adopta una **arquitectura Cliente-Servidor** con separación clara de responsabilidades mediante un **patrón de capas** (Layered Architecture). Esta arquitectura facilita el mantenimiento, la escalabilidad y la independencia entre los componentes del sistema.

### 9.1.1 Diagrama de Arquitectura General

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                              CLIENTE (Navegador Web)                         │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                             │
│  ┌──────────────────────────────────────────────────────────────────────┐  │
│  │                    CAPA DE PRESENTACIÓN (Frontend)                    │  │
│  ├──────────────────────────────────────────────────────────────────────┤  │
│  │  ┌────────────┐  ┌────────────┐  ┌────────────┐  ┌────────────────┐  │  │
│  │  │   HTML5    │  │  CSS3 /    │  │ JavaScript │  │    Chart.js    │  │  │
│  │  │  (XHTML)   │  │ Bootstrap  │  │  + jQuery  │  │   (Gráficas)   │  │  │
│  │  └────────────┘  └────────────┘  └────────────┘  └────────────────┘  │  │
│  │                                                                       │  │
│  │  ┌─────────────────────────────────────────────────────────────────┐ │  │
│  │  │                    Módulo AJAX (Comunicación)                    │ │  │
│  │  │              $.ajax() / fetch() → Peticiones HTTP               │ │  │
│  │  └─────────────────────────────────────────────────────────────────┘ │  │
│  └──────────────────────────────────────────────────────────────────────┘  │
│                                                                             │
└───────────────────────────────────┬─────────────────────────────────────────┘
                                    │
                           HTTP/HTTPS (JSON)
                           GET, POST, PUT, DELETE
                                    │
                                    ▼
┌─────────────────────────────────────────────────────────────────────────────┐
│                          SERVIDOR WEB (Apache/Nginx)                         │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                             │
│  ┌──────────────────────────────────────────────────────────────────────┐  │
│  │                   CAPA DE LÓGICA / API REST (Backend)                 │  │
│  ├──────────────────────────────────────────────────────────────────────┤  │
│  │                                                                       │  │
│  │  ┌─────────────────────────────────────────────────────────────────┐ │  │
│  │  │                        PHP 7.4+ con Composer                     │ │  │
│  │  ├─────────────────────────────────────────────────────────────────┤ │  │
│  │  │  ┌───────────────┐  ┌───────────────┐  ┌───────────────┐       │ │  │
│  │  │  │   Routing     │  │  Controllers  │  │   Middleware  │       │ │  │
│  │  │  │  (Endpoints)  │  │  (Lógica)     │  │  (Auth, CORS) │       │ │  │
│  │  │  └───────────────┘  └───────────────┘  └───────────────┘       │ │  │
│  │  │                                                                 │ │  │
│  │  │  ┌───────────────┐  ┌───────────────┐  ┌───────────────┐       │ │  │
│  │  │  │    Models     │  │   Services    │  │   Validators  │       │ │  │
│  │  │  │  (Entidades)  │  │  (Negocio)    │  │  (Sanitizar)  │       │ │  │
│  │  │  └───────────────┘  └───────────────┘  └───────────────┘       │ │  │
│  │  └─────────────────────────────────────────────────────────────────┘ │  │
│  └──────────────────────────────────────────────────────────────────────┘  │
│                                                                             │
└───────────────────────────────────┬─────────────────────────────────────────┘
                                    │
                              PDO / MySQLi
                          (Prepared Statements)
                                    │
                                    ▼
┌─────────────────────────────────────────────────────────────────────────────┐
│                           CAPA DE DATOS                                      │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                             │
│  ┌──────────────────────────────────────────────────────────────────────┐  │
│  │                         MySQL 5.7+ / MariaDB                          │  │
│  ├──────────────────────────────────────────────────────────────────────┤  │
│  │  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐  │  │
│  │  │  USUARIOS   │  │  RECURSOS   │  │  BITACORA   │  │  BITACORA   │  │  │
│  │  │             │  │             │  │  _ACCESO    │  │  _DESCARGAS │  │  │
│  │  └─────────────┘  └─────────────┘  └─────────────┘  └─────────────┘  │  │
│  └──────────────────────────────────────────────────────────────────────┘  │
│                                                                             │
└─────────────────────────────────────────────────────────────────────────────┘
```

---

## 9.2 Descripción de las Capas

### 9.2.1 Capa de Presentación (Frontend)

**Responsabilidad**: Proporcionar la interfaz de usuario y gestionar la interacción con el usuario final.

| Tecnología | Función |
|------------|---------|
| **HTML5/XHTML** | Estructura y marcado semántico de las páginas web. |
| **CSS3 / Bootstrap** | Estilos visuales, diseño responsive y componentes UI prediseñados. |
| **JavaScript + jQuery** | Manipulación del DOM, validación de formularios, gestión de eventos. |
| **Chart.js** | Renderizado de gráficas estadísticas interactivas (pie, bar, line). |
| **AJAX (jQuery.ajax)** | Comunicación asíncrona con el backend sin recarga de página. |

**Componentes principales**:
- Página de Login (`login.html`)
- Página de Signup (`signup.html`)
- Dashboard administrativo (`dashboard.html` o SPA)
- Catálogo público (`catalogo.html`)
- Módulo de estadísticas (integrado en dashboard)

### 9.2.2 Capa de Lógica / API REST (Backend)

**Responsabilidad**: Procesar las solicitudes del cliente, aplicar reglas de negocio y coordinar el acceso a datos.

| Componente | Función |
|------------|---------|
| **Router/Endpoints** | Mapear URLs a controladores específicos según método HTTP. |
| **Controllers** | Recibir peticiones, invocar servicios y retornar respuestas JSON. |
| **Middleware** | Verificar autenticación, manejar CORS, logging de peticiones. |
| **Services** | Implementar lógica de negocio (validaciones complejas, cálculos). |
| **Models** | Representar entidades y encapsular acceso a datos. |
| **Validators** | Sanitizar y validar datos de entrada antes del procesamiento. |

**Tecnologías**:
- PHP 7.4 o superior
- Composer para autoloading y gestión de dependencias
- PDO con prepared statements para acceso a base de datos
- Sesiones PHP para autenticación

### 9.2.3 Capa de Datos

**Responsabilidad**: Almacenar y gestionar la persistencia de información.

| Elemento | Descripción |
|----------|-------------|
| **MySQL/MariaDB** | Sistema de gestión de base de datos relacional. |
| **Tablas** | USUARIOS, RECURSOS, BITACORA_ACCESO, BITACORA_DESCARGAS. |
| **Índices** | Optimización de consultas frecuentes (búsquedas, filtros). |
| **Integridad referencial** | Foreign keys para mantener consistencia de datos. |

---

## 9.3 Comunicación mediante AJAX con API REST

### 9.3.1 Principios de Comunicación

La comunicación entre el frontend y el backend se realiza mediante **peticiones HTTP asíncronas** utilizando **AJAX** (Asynchronous JavaScript And XML), implementado con jQuery. Las respuestas se transmiten en formato **JSON**.

**Características**:
- **Asincronía**: Las peticiones no bloquean la interfaz de usuario.
- **Sin recarga**: La página no se recarga completamente al interactuar con el servidor.
- **JSON**: Formato ligero y fácil de procesar en JavaScript.
- **RESTful**: Uso semántico de métodos HTTP (GET, POST, PUT, DELETE).

### 9.3.2 Estructura de Peticiones y Respuestas

**Estructura de petición AJAX típica**:
```javascript
$.ajax({
    url: '/api/endpoint',
    method: 'GET|POST|PUT|DELETE',
    dataType: 'json',
    contentType: 'application/json',
    data: JSON.stringify({ /* datos */ }),
    headers: {
        'X-Requested-With': 'XMLHttpRequest'
    },
    success: function(response) {
        // Procesar respuesta exitosa
    },
    error: function(xhr, status, error) {
        // Manejar error
    }
});
```

**Estructura de respuesta JSON exitosa**:
```json
{
    "success": true,
    "message": "Operación realizada correctamente",
    "data": { /* datos solicitados */ }
}
```

**Estructura de respuesta JSON de error**:
```json
{
    "success": false,
    "message": "Descripción del error",
    "errors": ["Error 1", "Error 2"]
}
```

---

## 9.4 Especificación de Endpoints del API REST

### 9.4.1 Módulo de Autenticación

| Método | Endpoint | Descripción | Auth | Body/Params |
|--------|----------|-------------|------|-------------|
| POST | `/api/auth/login` | Iniciar sesión | No | `{username, password}` |
| POST | `/api/auth/logout` | Cerrar sesión | Sí | - |
| POST | `/api/auth/signup` | Registrar administrador | No | `{nombre, email, username, password}` |
| GET | `/api/auth/check` | Verificar sesión activa | Sí | - |

### 9.4.2 Módulo de Recursos

| Método | Endpoint | Descripción | Auth | Body/Params |
|--------|----------|-------------|------|-------------|
| GET | `/api/recursos` | Listar recursos activos | No | `?page=1&limit=10&tipo=&lenguaje=&q=` |
| GET | `/api/recursos/{id}` | Obtener recurso por ID | No | - |
| POST | `/api/recursos` | Crear nuevo recurso | Sí | `{nombre, descripcion, tipo_recurso, lenguaje, url_descarga, imagen}` |
| PUT | `/api/recursos/{id}` | Actualizar recurso | Sí | `{nombre, descripcion, ...}` |
| DELETE | `/api/recursos/{id}` | Eliminar lógicamente | Sí | - |

### 9.4.3 Módulo de Estadísticas

| Método | Endpoint | Descripción | Auth | Body/Params |
|--------|----------|-------------|------|-------------|
| GET | `/api/estadisticas/por-tipo` | Descargas por tipo de recurso | Sí | `?fecha_inicio=&fecha_fin=` |
| GET | `/api/estadisticas/por-lenguaje` | Descargas por lenguaje | Sí | `?fecha_inicio=&fecha_fin=` |
| GET | `/api/estadisticas/por-dia` | Descargas por día de semana | Sí | `?fecha_inicio=&fecha_fin=` |
| GET | `/api/estadisticas/por-hora` | Descargas por hora del día | Sí | - |
| GET | `/api/estadisticas/top-recursos` | Top 10 más descargados | Sí | - |

### 9.4.4 Módulo de Descargas (Bitácora)

| Método | Endpoint | Descripción | Auth | Body/Params |
|--------|----------|-------------|------|-------------|
| POST | `/api/descargas` | Registrar descarga | No | `{id_recurso}` |
| GET | `/api/descargas` | Consultar bitácora | Sí | `?fecha_inicio=&fecha_fin=` |

---

## 9.5 Flujos de Comunicación Detallados

### 9.5.1 Flujo: Login de Administrador

```
┌──────────────┐                    ┌──────────────┐                    ┌──────────────┐
│   Frontend   │                    │   API REST   │                    │    MySQL     │
│   (jQuery)   │                    │    (PHP)     │                    │              │
└──────┬───────┘                    └──────┬───────┘                    └──────┬───────┘
       │                                   │                                   │
       │  POST /api/auth/login             │                                   │
       │  {username: "admin",              │                                   │
       │   password: "admin123"}           │                                   │
       │──────────────────────────────────►│                                   │
       │                                   │                                   │
       │                                   │  SELECT * FROM USUARIOS           │
       │                                   │  WHERE username = ?               │
       │                                   │──────────────────────────────────►│
       │                                   │                                   │
       │                                   │◄──────────────────────────────────│
       │                                   │  {id, nombre, password_hash, ...} │
       │                                   │                                   │
       │                                   │  password_verify()                │
       │                                   │  session_start()                  │
       │                                   │  $_SESSION['user'] = data         │
       │                                   │                                   │
       │◄──────────────────────────────────│                                   │
       │  HTTP 200                         │                                   │
       │  {success: true,                  │                                   │
       │   message: "Login exitoso",       │                                   │
       │   data: {id, nombre, username}}   │                                   │
       │                                   │                                   │
       │  Redirigir a dashboard.html       │                                   │
       ▼                                   │                                   │
```

### 9.5.2 Flujo: Listar Recursos (Dashboard/Catálogo)

```
┌──────────────┐                    ┌──────────────┐                    ┌──────────────┐
│   Frontend   │                    │   API REST   │                    │    MySQL     │
│   (jQuery)   │                    │    (PHP)     │                    │              │
└──────┬───────┘                    └──────┬───────┘                    └──────┬───────┘
       │                                   │                                   │
       │  GET /api/recursos                │                                   │
       │  ?page=1&limit=10&tipo=manual     │                                   │
       │──────────────────────────────────►│                                   │
       │                                   │                                   │
       │                                   │  SELECT * FROM RECURSOS           │
       │                                   │  WHERE deleted_at IS NULL         │
       │                                   │  AND tipo_recurso = ?             │
       │                                   │  LIMIT 10 OFFSET 0                │
       │                                   │──────────────────────────────────►│
       │                                   │                                   │
       │                                   │◄──────────────────────────────────│
       │                                   │  [array de recursos]              │
       │                                   │                                   │
       │◄──────────────────────────────────│                                   │
       │  HTTP 200                         │                                   │
       │  {success: true,                  │                                   │
       │   data: [{id, nombre, ...}, ...], │                                   │
       │   pagination: {page, total, ...}} │                                   │
       │                                   │                                   │
       │  renderTabla(data)                │                                   │
       ▼                                   │                                   │
```

### 9.5.3 Flujo: Crear/Actualizar Recurso

```
┌──────────────┐                    ┌──────────────┐                    ┌──────────────┐
│   Frontend   │                    │   API REST   │                    │    MySQL     │
│   (jQuery)   │                    │    (PHP)     │                    │              │
└──────┬───────┘                    └──────┬───────┘                    └──────┬───────┘
       │                                   │                                   │
       │  POST /api/recursos               │                                   │
       │  {nombre: "Nuevo Manual",         │                                   │
       │   descripcion: "...",             │                                   │
       │   tipo_recurso: "manual",         │                                   │
       │   lenguaje: "PHP",                │                                   │
       │   url_descarga: "https://..."}    │                                   │
       │──────────────────────────────────►│                                   │
       │                                   │                                   │
       │                                   │  Verificar sesión                 │
       │                                   │  Validar datos                    │
       │                                   │  Sanitizar inputs                 │
       │                                   │                                   │
       │                                   │  INSERT INTO RECURSOS (...)       │
       │                                   │  VALUES (?, ?, ?, ...)            │
       │                                   │──────────────────────────────────►│
       │                                   │                                   │
       │                                   │◄──────────────────────────────────│
       │                                   │  lastInsertId()                   │
       │                                   │                                   │
       │◄──────────────────────────────────│                                   │
       │  HTTP 201                         │                                   │
       │  {success: true,                  │                                   │
       │   message: "Recurso creado",      │                                   │
       │   data: {id: 15, ...}}            │                                   │
       │                                   │                                   │
       │  Actualizar listado               │                                   │
       │  Mostrar mensaje éxito            │                                   │
       ▼                                   │                                   │
```

### 9.5.4 Flujo: Descarga y Registro en Bitácora

```
┌──────────────┐                    ┌──────────────┐                    ┌──────────────┐
│   Frontend   │                    │   API REST   │                    │    MySQL     │
│   (Catálogo) │                    │    (PHP)     │                    │              │
└──────┬───────┘                    └──────┬───────┘                    └──────┬───────┘
       │                                   │                                   │
       │  Click en "Descargar"             │                                   │
       │                                   │                                   │
       │  POST /api/descargas              │                                   │
       │  {id_recurso: 5}                  │                                   │
       │──────────────────────────────────►│                                   │
       │                                   │                                   │
       │                                   │  $ip = $_SERVER['REMOTE_ADDR']    │
       │                                   │  $ua = $_SERVER['HTTP_USER_AGENT']│
       │                                   │                                   │
       │                                   │  INSERT INTO BITACORA_DESCARGAS   │
       │                                   │  (id_recurso, ip_cliente, ...)    │
       │                                   │──────────────────────────────────►│
       │                                   │                                   │
       │                                   │  UPDATE RECURSOS                  │
       │                                   │  SET descargas = descargas + 1    │
       │                                   │  WHERE id = ?                     │
       │                                   │──────────────────────────────────►│
       │                                   │                                   │
       │                                   │  SELECT url_descarga              │
       │                                   │  FROM RECURSOS WHERE id = ?       │
       │                                   │──────────────────────────────────►│
       │                                   │                                   │
       │◄──────────────────────────────────│                                   │
       │  HTTP 200                         │                                   │
       │  {success: true,                  │                                   │
       │   url: "https://ejemplo.com/..."}│                                   │
       │                                   │                                   │
       │  window.open(response.url)        │                                   │
       ▼                                   │                                   │
```

### 9.5.5 Flujo: Obtener Datos para Gráficas

```
┌──────────────┐                    ┌──────────────┐                    ┌──────────────┐
│   Frontend   │                    │   API REST   │                    │    MySQL     │
│  (Chart.js)  │                    │    (PHP)     │                    │              │
└──────┬───────┘                    └──────┬───────┘                    └──────┬───────┘
       │                                   │                                   │
       │  GET /api/estadisticas/por-tipo   │                                   │
       │──────────────────────────────────►│                                   │
       │                                   │                                   │
       │                                   │  SELECT tipo_recurso,             │
       │                                   │  COUNT(*) as total                │
       │                                   │  FROM BITACORA_DESCARGAS bd       │
       │                                   │  JOIN RECURSOS r ...              │
       │                                   │  GROUP BY tipo_recurso            │
       │                                   │──────────────────────────────────►│
       │                                   │                                   │
       │◄──────────────────────────────────│                                   │
       │  {labels: ["manual", "libreria"], │                                   │
       │   data: [156, 245]}               │                                   │
       │                                   │                                   │
       │  new Chart(ctx, {                 │                                   │
       │    type: 'pie',                   │                                   │
       │    data: { labels, datasets }     │                                   │
       │  });                              │                                   │
       ▼                                   │                                   │
```

---

## 9.6 Diagrama de Componentes (Descripción Textual)

Para elaborar en Visual Paradigm o Miro, el diagrama de componentes debe mostrar:

**Componentes Frontend**:
- `login.html` + `login.js`
- `signup.html` + `signup.js`
- `dashboard.html` + `dashboard.js`
- `catalogo.html` + `catalogo.js`
- `estadisticas.js` (módulo de gráficas)
- `ajax-service.js` (módulo centralizado de peticiones)
- `styles.css` o Bootstrap CDN

**Componentes Backend (PHP)**:
- `index.php` (punto de entrada / router)
- `/api/auth/` (controladores de autenticación)
- `/api/recursos/` (controladores de recursos)
- `/api/estadisticas/` (controladores de estadísticas)
- `/api/descargas/` (controlador de bitácora)
- `/config/database.php` (configuración de conexión)
- `/models/` (clases de entidades)
- `/middleware/` (verificación de auth, CORS)

**Interfaces/Conexiones**:
- Frontend ↔ API: HTTP/JSON
- API ↔ MySQL: PDO

---

## 9.7 Diagrama de Despliegue (Descripción Textual)

El diagrama de despliegue representa la distribución física de los componentes:

```
┌─────────────────────────────────────────────────────────────────┐
│                    Nodo: Dispositivo Cliente                    │
│                    (PC, Laptop, Tablet, Móvil)                  │
├─────────────────────────────────────────────────────────────────┤
│  ┌───────────────────────────────────────────────────────────┐  │
│  │                    Navegador Web                          │  │
│  │              (Chrome, Firefox, Edge, Safari)              │  │
│  ├───────────────────────────────────────────────────────────┤  │
│  │  Artefactos desplegados:                                  │  │
│  │  - HTML, CSS, JavaScript (descargados del servidor)       │  │
│  │  - jQuery (CDN)                                           │  │
│  │  - Chart.js (CDN)                                         │  │
│  │  - Bootstrap (CDN)                                        │  │
│  └───────────────────────────────────────────────────────────┘  │
└───────────────────────────────┬─────────────────────────────────┘
                                │ HTTPS
                                ▼
┌─────────────────────────────────────────────────────────────────┐
│                 Nodo: Servidor Web (XAMPP/Apache)               │
│                     (localhost o hosting)                        │
├─────────────────────────────────────────────────────────────────┤
│  ┌───────────────────────────────────────────────────────────┐  │
│  │                     Apache HTTP Server                     │  │
│  │                       (Puerto 80/443)                      │  │
│  └───────────────────────────────────────────────────────────┘  │
│                              │                                   │
│  ┌───────────────────────────────────────────────────────────┐  │
│  │                        PHP 7.4+                            │  │
│  │                    (mod_php o PHP-FPM)                     │  │
│  ├───────────────────────────────────────────────────────────┤  │
│  │  Artefactos desplegados:                                  │  │
│  │  /var/www/html/proyecto/                                  │  │
│  │    ├── public/          (archivos estáticos)              │  │
│  │    ├── api/             (endpoints PHP)                   │  │
│  │    ├── config/          (configuración)                   │  │
│  │    ├── vendor/          (dependencias Composer)           │  │
│  │    └── composer.json                                      │  │
│  └───────────────────────────────────────────────────────────┘  │
└───────────────────────────────┬─────────────────────────────────┘
                                │ TCP/IP (3306)
                                ▼
┌─────────────────────────────────────────────────────────────────┐
│                  Nodo: Servidor de Base de Datos                │
│                   (MySQL 5.7+ / MariaDB 10.x)                   │
├─────────────────────────────────────────────────────────────────┤
│  ┌───────────────────────────────────────────────────────────┐  │
│  │                   MySQL Server (Puerto 3306)               │  │
│  ├───────────────────────────────────────────────────────────┤  │
│  │  Base de datos: recursos_digitales                        │  │
│  │    ├── USUARIOS                                           │  │
│  │    ├── RECURSOS                                           │  │
│  │    ├── BITACORA_ACCESO                                    │  │
│  │    └── BITACORA_DESCARGAS                                 │  │
│  └───────────────────────────────────────────────────────────┘  │
└─────────────────────────────────────────────────────────────────┘
```

**Nota para desarrollo local**: En XAMPP, el servidor web y el servidor de base de datos se ejecutan en el mismo equipo físico (localhost).

