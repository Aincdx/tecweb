# DOCUMENTO DE INGENIERÍA DE SOFTWARE - PARTE 4

## Dashboard para la Gestión de Recursos Digitales

---

# 8. MODELO DE DATOS (DISEÑO DE LA BASE DE DATOS)

## 8.1 Introducción al Modelo de Datos

El modelo de datos del sistema está diseñado para soportar todas las funcionalidades requeridas: gestión de usuarios administradores, catálogo de recursos digitales, y registro de bitácoras para generación de estadísticas. Se utiliza MySQL como sistema de gestión de base de datos relacional.

El diseño sigue los principios de normalización hasta la tercera forma normal (3FN) para evitar redundancia de datos y mantener la integridad referencial.

## 8.2 Diagrama Entidad-Relación (Representación Textual)

```
┌─────────────────┐         ┌─────────────────────┐
│    USUARIOS     │         │      RECURSOS       │
├─────────────────┤         ├─────────────────────┤
│ PK id           │    ┌───►│ PK id               │
│    nombre       │    │    │    nombre           │
│    email        │    │    │    descripcion      │
│    username     │    │    │    tipo_recurso     │
│    password     │    │    │    lenguaje         │
│    created_at   │    │    │    url_descarga     │
│    updated_at   │    │    │    imagen           │
│    deleted_at   │    │    │    descargas        │
└─────────────────┘    │    │    created_at       │
                       │    │    updated_at       │
                       │    │    deleted_at       │
                       │    └─────────────────────┘
                       │              │
                       │              │
┌─────────────────┐    │    ┌────────┴────────────┐
│ BITACORA_ACCESO │    │    │ BITACORA_DESCARGAS  │
├─────────────────┤    │    ├─────────────────────┤
│ PK id           │    │    │ PK id               │
│    ip_cliente   │    │    │ FK id_recurso ──────┘
│    user_agent   │    │    │    ip_cliente       │
│    pagina       │    │    │    user_agent       │
│    fecha_hora   │    │    │    fecha_hora       │
└─────────────────┘    │    └─────────────────────┘
                       │
                       │
┌─────────────────┐    │
│  TIPOS_RECURSO  │◄───┘ (Opcional: catálogo)
├─────────────────┤
│ PK id           │
│    nombre       │
│    descripcion  │
└─────────────────┘
```

---

## 8.3 Descripción de Tablas

### 8.3.1 Tabla: USUARIOS

**Propósito**: Almacena la información de los usuarios administradores del sistema que tienen acceso al dashboard de gestión.

| Campo | Tipo de Dato | Restricciones | Descripción |
|-------|--------------|---------------|-------------|
| `id` | INT | PRIMARY KEY, AUTO_INCREMENT | Identificador único del usuario. |
| `nombre` | VARCHAR(100) | NOT NULL | Nombre completo del usuario. |
| `email` | VARCHAR(150) | NOT NULL, UNIQUE | Correo electrónico único para identificación y posible recuperación. |
| `username` | VARCHAR(50) | NOT NULL, UNIQUE | Nombre de usuario único para el login. |
| `password` | VARCHAR(255) | NOT NULL | Hash de la contraseña (bcrypt). |
| `activo` | TINYINT(1) | DEFAULT 1 | Indica si la cuenta está activa (1) o deshabilitada (0). |
| `created_at` | DATETIME | DEFAULT CURRENT_TIMESTAMP | Fecha y hora de creación del registro. |
| `updated_at` | DATETIME | NULL, ON UPDATE CURRENT_TIMESTAMP | Fecha y hora de última actualización. |
| `deleted_at` | DATETIME | NULL | Fecha de eliminación lógica (soft delete). Si es NULL, el registro está activo. |

**Índices**:
- PRIMARY KEY (`id`)
- UNIQUE INDEX (`email`)
- UNIQUE INDEX (`username`)

**Script SQL**:
```sql
CREATE TABLE USUARIOS (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    activo TINYINT(1) DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NULL ON UPDATE CURRENT_TIMESTAMP,
    deleted_at DATETIME NULL,
    INDEX idx_email (email),
    INDEX idx_username (username)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

---

### 8.3.2 Tabla: RECURSOS

**Propósito**: Almacena el catálogo de recursos digitales disponibles para consulta y descarga.

| Campo | Tipo de Dato | Restricciones | Descripción |
|-------|--------------|---------------|-------------|
| `id` | INT | PRIMARY KEY, AUTO_INCREMENT | Identificador único del recurso. |
| `nombre` | VARCHAR(150) | NOT NULL | Nombre o título del recurso digital. |
| `descripcion` | TEXT | NULL | Descripción detallada del contenido del recurso. |
| `tipo_recurso` | VARCHAR(50) | NOT NULL | Tipo de recurso: 'manual', 'libreria', 'ejemplo', 'plantilla', 'documentacion', 'otro'. |
| `lenguaje` | VARCHAR(50) | NULL | Lenguaje de programación asociado: 'JavaScript', 'PHP', 'Python', 'Java', 'C#', 'General', etc. |
| `url_descarga` | VARCHAR(500) | NOT NULL | URL donde se encuentra el archivo para descarga. |
| `imagen` | VARCHAR(500) | NULL | URL de imagen o icono representativo del recurso. |
| `descargas` | INT | DEFAULT 0 | Contador de descargas realizadas. |
| `created_at` | DATETIME | DEFAULT CURRENT_TIMESTAMP | Fecha y hora de creación del registro. |
| `updated_at` | DATETIME | NULL, ON UPDATE CURRENT_TIMESTAMP | Fecha y hora de última actualización. |
| `deleted_at` | DATETIME | NULL | Fecha de eliminación lógica. Si es NULL, el recurso está activo/visible. |

**Índices**:
- PRIMARY KEY (`id`)
- INDEX (`tipo_recurso`)
- INDEX (`lenguaje`)
- INDEX (`deleted_at`)
- FULLTEXT INDEX (`nombre`, `descripcion`) - Para búsqueda de texto

**Script SQL**:
```sql
CREATE TABLE RECURSOS (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL,
    descripcion TEXT NULL,
    tipo_recurso VARCHAR(50) NOT NULL,
    lenguaje VARCHAR(50) NULL,
    url_descarga VARCHAR(500) NOT NULL,
    imagen VARCHAR(500) NULL,
    descargas INT DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NULL ON UPDATE CURRENT_TIMESTAMP,
    deleted_at DATETIME NULL,
    INDEX idx_tipo (tipo_recurso),
    INDEX idx_lenguaje (lenguaje),
    INDEX idx_deleted (deleted_at),
    FULLTEXT INDEX idx_busqueda (nombre, descripcion)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

---

### 8.3.3 Tabla: BITACORA_ACCESO

**Propósito**: Registra cada acceso al catálogo público para fines de auditoría y análisis de tráfico.

| Campo | Tipo de Dato | Restricciones | Descripción |
|-------|--------------|---------------|-------------|
| `id` | BIGINT | PRIMARY KEY, AUTO_INCREMENT | Identificador único del registro de acceso. |
| `ip_cliente` | VARCHAR(45) | NOT NULL | Dirección IP del visitante (soporta IPv6). |
| `user_agent` | VARCHAR(500) | NULL | Cadena User-Agent del navegador del visitante. |
| `pagina` | VARCHAR(255) | NULL | Página o sección accedida dentro del catálogo. |
| `referer` | VARCHAR(500) | NULL | URL de referencia desde donde llegó el visitante. |
| `fecha_hora` | DATETIME | DEFAULT CURRENT_TIMESTAMP | Fecha y hora exacta del acceso. |

**Índices**:
- PRIMARY KEY (`id`)
- INDEX (`fecha_hora`)
- INDEX (`ip_cliente`)

**Script SQL**:
```sql
CREATE TABLE BITACORA_ACCESO (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    ip_cliente VARCHAR(45) NOT NULL,
    user_agent VARCHAR(500) NULL,
    pagina VARCHAR(255) NULL,
    referer VARCHAR(500) NULL,
    fecha_hora DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_fecha (fecha_hora),
    INDEX idx_ip (ip_cliente)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

---

### 8.3.4 Tabla: BITACORA_DESCARGAS

**Propósito**: Registra cada descarga de recurso para estadísticas detalladas y trazabilidad.

| Campo | Tipo de Dato | Restricciones | Descripción |
|-------|--------------|---------------|-------------|
| `id` | BIGINT | PRIMARY KEY, AUTO_INCREMENT | Identificador único del registro de descarga. |
| `id_recurso` | INT | NOT NULL, FOREIGN KEY | ID del recurso descargado (referencia a RECURSOS). |
| `ip_cliente` | VARCHAR(45) | NOT NULL | Dirección IP del visitante que descargó. |
| `user_agent` | VARCHAR(500) | NULL | User-Agent del navegador. |
| `fecha_hora` | DATETIME | DEFAULT CURRENT_TIMESTAMP | Fecha y hora exacta de la descarga. |

**Índices y Claves Foráneas**:
- PRIMARY KEY (`id`)
- FOREIGN KEY (`id_recurso`) REFERENCES RECURSOS(`id`)
- INDEX (`fecha_hora`)
- INDEX (`id_recurso`)

**Script SQL**:
```sql
CREATE TABLE BITACORA_DESCARGAS (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    id_recurso INT NOT NULL,
    ip_cliente VARCHAR(45) NOT NULL,
    user_agent VARCHAR(500) NULL,
    fecha_hora DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_recurso) REFERENCES RECURSOS(id) ON DELETE CASCADE,
    INDEX idx_fecha (fecha_hora),
    INDEX idx_recurso (id_recurso)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

---

## 8.4 Tabla Opcional: TIPOS_RECURSO (Catálogo Normalizado)

**Propósito**: Catálogo de tipos de recurso para normalización (opcional, puede manejarse como ENUM o VARCHAR directo).

| Campo | Tipo de Dato | Restricciones | Descripción |
|-------|--------------|---------------|-------------|
| `id` | INT | PRIMARY KEY, AUTO_INCREMENT | Identificador único del tipo. |
| `nombre` | VARCHAR(50) | NOT NULL, UNIQUE | Nombre del tipo de recurso. |
| `descripcion` | VARCHAR(255) | NULL | Descripción del tipo. |
| `icono` | VARCHAR(100) | NULL | Clase CSS o nombre de icono representativo. |

**Script SQL**:
```sql
CREATE TABLE TIPOS_RECURSO (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE,
    descripcion VARCHAR(255) NULL,
    icono VARCHAR(100) NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Datos iniciales
INSERT INTO TIPOS_RECURSO (nombre, descripcion, icono) VALUES
('manual', 'Manuales y guías de referencia', 'fa-book'),
('libreria', 'Librerías y frameworks', 'fa-cube'),
('ejemplo', 'Ejemplos de código y snippets', 'fa-code'),
('plantilla', 'Plantillas y boilerplates', 'fa-file-code'),
('documentacion', 'Documentación técnica', 'fa-file-alt'),
('herramienta', 'Herramientas y utilidades', 'fa-tools'),
('otro', 'Otros recursos', 'fa-folder');
```

---

## 8.5 Consultas SQL para Estadísticas (Chart.js)

A continuación se presentan las consultas SQL que alimentarán las gráficas de estadísticas.

### 8.5.1 Descargas por Tipo de Recurso

```sql
-- Obtener total de descargas agrupadas por tipo de recurso
SELECT 
    r.tipo_recurso AS tipo,
    COUNT(bd.id) AS total_descargas
FROM BITACORA_DESCARGAS bd
INNER JOIN RECURSOS r ON bd.id_recurso = r.id
WHERE r.deleted_at IS NULL
GROUP BY r.tipo_recurso
ORDER BY total_descargas DESC;
```

**Resultado esperado** (ejemplo):
```json
[
    {"tipo": "ejemplo", "total_descargas": 245},
    {"tipo": "libreria", "total_descargas": 189},
    {"tipo": "manual", "total_descargas": 156},
    {"tipo": "plantilla", "total_descargas": 87},
    {"tipo": "documentacion", "total_descargas": 45}
]
```

### 8.5.2 Descargas por Lenguaje de Programación

```sql
-- Obtener total de descargas agrupadas por lenguaje de programación
SELECT 
    COALESCE(r.lenguaje, 'General') AS lenguaje,
    COUNT(bd.id) AS total_descargas
FROM BITACORA_DESCARGAS bd
INNER JOIN RECURSOS r ON bd.id_recurso = r.id
WHERE r.deleted_at IS NULL
GROUP BY r.lenguaje
ORDER BY total_descargas DESC;
```

**Resultado esperado** (ejemplo):
```json
[
    {"lenguaje": "JavaScript", "total_descargas": 312},
    {"lenguaje": "PHP", "total_descargas": 198},
    {"lenguaje": "Python", "total_descargas": 176},
    {"lenguaje": "Java", "total_descargas": 89},
    {"lenguaje": "General", "total_descargas": 47}
]
```

### 8.5.3 Descargas por Día de la Semana

```sql
-- Obtener total de descargas agrupadas por día de la semana
SELECT 
    DAYOFWEEK(bd.fecha_hora) AS dia_numero,
    DAYNAME(bd.fecha_hora) AS dia_nombre,
    COUNT(bd.id) AS total_descargas
FROM BITACORA_DESCARGAS bd
GROUP BY DAYOFWEEK(bd.fecha_hora), DAYNAME(bd.fecha_hora)
ORDER BY dia_numero;
```

**Resultado esperado** (ejemplo):
```json
[
    {"dia_numero": 1, "dia_nombre": "Sunday", "total_descargas": 45},
    {"dia_numero": 2, "dia_nombre": "Monday", "total_descargas": 156},
    {"dia_numero": 3, "dia_nombre": "Tuesday", "total_descargas": 189},
    {"dia_numero": 4, "dia_nombre": "Wednesday", "total_descargas": 201},
    {"dia_numero": 5, "dia_nombre": "Thursday", "total_descargas": 178},
    {"dia_numero": 6, "dia_nombre": "Friday", "total_descargas": 134},
    {"dia_numero": 7, "dia_nombre": "Saturday", "total_descargas": 67}
]
```

### 8.5.4 Descargas por Hora del Día

```sql
-- Obtener total de descargas agrupadas por hora del día
SELECT 
    HOUR(bd.fecha_hora) AS hora,
    COUNT(bd.id) AS total_descargas
FROM BITACORA_DESCARGAS bd
GROUP BY HOUR(bd.fecha_hora)
ORDER BY hora;
```

### 8.5.5 Top 10 Recursos Más Descargados

```sql
-- Obtener los 10 recursos con más descargas
SELECT 
    r.id,
    r.nombre,
    r.tipo_recurso,
    r.lenguaje,
    r.descargas
FROM RECURSOS r
WHERE r.deleted_at IS NULL
ORDER BY r.descargas DESC
LIMIT 10;
```

### 8.5.6 Descargas por Periodo (Últimos 30 días)

```sql
-- Obtener descargas diarias de los últimos 30 días
SELECT 
    DATE(bd.fecha_hora) AS fecha,
    COUNT(bd.id) AS total_descargas
FROM BITACORA_DESCARGAS bd
WHERE bd.fecha_hora >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
GROUP BY DATE(bd.fecha_hora)
ORDER BY fecha;
```

---

## 8.6 Script Completo de Creación de Base de Datos

```sql
-- =============================================
-- Script de creación de base de datos
-- Dashboard para Gestión de Recursos Digitales
-- =============================================

-- Crear base de datos
CREATE DATABASE IF NOT EXISTS recursos_digitales
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE recursos_digitales;

-- =============================================
-- Tabla: USUARIOS
-- =============================================
CREATE TABLE USUARIOS (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    activo TINYINT(1) DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NULL ON UPDATE CURRENT_TIMESTAMP,
    deleted_at DATETIME NULL,
    INDEX idx_email (email),
    INDEX idx_username (username),
    INDEX idx_activo (activo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Tabla: RECURSOS
-- =============================================
CREATE TABLE RECURSOS (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL,
    descripcion TEXT NULL,
    tipo_recurso VARCHAR(50) NOT NULL,
    lenguaje VARCHAR(50) NULL,
    url_descarga VARCHAR(500) NOT NULL,
    imagen VARCHAR(500) NULL,
    descargas INT DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NULL ON UPDATE CURRENT_TIMESTAMP,
    deleted_at DATETIME NULL,
    INDEX idx_tipo (tipo_recurso),
    INDEX idx_lenguaje (lenguaje),
    INDEX idx_deleted (deleted_at),
    INDEX idx_descargas (descargas),
    FULLTEXT INDEX idx_busqueda (nombre, descripcion)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Tabla: BITACORA_ACCESO
-- =============================================
CREATE TABLE BITACORA_ACCESO (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    ip_cliente VARCHAR(45) NOT NULL,
    user_agent VARCHAR(500) NULL,
    pagina VARCHAR(255) NULL,
    referer VARCHAR(500) NULL,
    fecha_hora DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_fecha (fecha_hora),
    INDEX idx_ip (ip_cliente)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Tabla: BITACORA_DESCARGAS
-- =============================================
CREATE TABLE BITACORA_DESCARGAS (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    id_recurso INT NOT NULL,
    ip_cliente VARCHAR(45) NOT NULL,
    user_agent VARCHAR(500) NULL,
    fecha_hora DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_recurso) REFERENCES RECURSOS(id) ON DELETE CASCADE,
    INDEX idx_fecha (fecha_hora),
    INDEX idx_recurso (id_recurso)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Datos de prueba: Usuario administrador
-- Password: admin123 (hash bcrypt)
-- =============================================
INSERT INTO USUARIOS (nombre, email, username, password) VALUES
('Administrador', 'admin@ejemplo.com', 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- =============================================
-- Datos de prueba: Recursos digitales
-- =============================================
INSERT INTO RECURSOS (nombre, descripcion, tipo_recurso, lenguaje, url_descarga, imagen) VALUES
('Manual de JavaScript ES6+', 'Guía completa de las nuevas características de ECMAScript 6 y versiones posteriores.', 'manual', 'JavaScript', 'https://ejemplo.com/recursos/js-es6-manual.pdf', 'https://ejemplo.com/img/js-logo.png'),
('Librería jQuery 3.x', 'Versión minificada de jQuery para manipulación del DOM.', 'libreria', 'JavaScript', 'https://ejemplo.com/recursos/jquery-3.min.js', 'https://ejemplo.com/img/jquery-logo.png'),
('Ejemplos CRUD PHP + MySQL', 'Colección de ejemplos prácticos de operaciones CRUD con PHP y MySQL.', 'ejemplo', 'PHP', 'https://ejemplo.com/recursos/crud-php-mysql.zip', 'https://ejemplo.com/img/php-logo.png'),
('Plantilla Bootstrap Dashboard', 'Plantilla HTML responsive para dashboards administrativos.', 'plantilla', 'General', 'https://ejemplo.com/recursos/bootstrap-dashboard.zip', 'https://ejemplo.com/img/bootstrap-logo.png'),
('Documentación API REST', 'Guía de buenas prácticas para diseño de APIs RESTful.', 'documentacion', 'General', 'https://ejemplo.com/recursos/api-rest-doc.pdf', 'https://ejemplo.com/img/api-logo.png'),
('Tutorial Python Flask', 'Introducción al desarrollo web con Flask en Python.', 'manual', 'Python', 'https://ejemplo.com/recursos/flask-tutorial.pdf', 'https://ejemplo.com/img/python-logo.png'),
('Snippets SQL Avanzados', 'Colección de consultas SQL avanzadas para reportes y análisis.', 'ejemplo', 'General', 'https://ejemplo.com/recursos/sql-snippets.sql', 'https://ejemplo.com/img/sql-logo.png'),
('Framework Chart.js', 'Librería JavaScript para gráficas interactivas.', 'libreria', 'JavaScript', 'https://ejemplo.com/recursos/chartjs.min.js', 'https://ejemplo.com/img/chartjs-logo.png');
```

---

## 8.7 Diccionario de Datos Resumido

| Tabla | Descripción | Registros Estimados |
|-------|-------------|---------------------|
| USUARIOS | Administradores del sistema | 1-10 |
| RECURSOS | Catálogo de recursos digitales | 50-500 |
| BITACORA_ACCESO | Log de visitas al catálogo | 1,000-100,000+ |
| BITACORA_DESCARGAS | Log de descargas realizadas | 500-50,000+ |

