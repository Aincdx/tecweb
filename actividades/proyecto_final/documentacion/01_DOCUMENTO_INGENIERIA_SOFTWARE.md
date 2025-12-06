# DOCUMENTO DE INGENIERÍA DE SOFTWARE

## Dashboard para la Gestión de Recursos Digitales
### (Archivos de Soporte para Programadores)

---

# 1. INFORMACIÓN GENERAL DEL PROYECTO

## 1.1 Datos del Proyecto

| Campo | Descripción |
|-------|-------------|
| **Nombre del Proyecto** | Dashboard para la Gestión de Recursos Digitales |
| **Materia** | Tecnologías Web / Desarrollo Web |
| **Periodo Académico** | [Semestre/Cuatrimestre - Año] |
| **Institución** | [Nombre de la Universidad/Instituto] |
| **Docente** | [Nombre del Docente] |

## 1.2 Integrantes del Equipo

| No. | Nombre Completo | Matrícula | Correo Electrónico |
|-----|-----------------|-----------|-------------------|
| 1 | [Nombre del Integrante 1] | [Matrícula] | [correo@ejemplo.com] |
| 2 | [Nombre del Integrante 2] | [Matrícula] | [correo@ejemplo.com] |
| 3 | [Nombre del Integrante 3] | [Matrícula] | [correo@ejemplo.com] |

## 1.3 Contexto del Proyecto

El presente proyecto se desarrolla como trabajo final de la materia de Tecnologías Web, con el propósito de demostrar la aplicación práctica de los conocimientos adquiridos durante el curso. El sistema propuesto integra tecnologías del lado del cliente (frontend) y del lado del servidor (backend), implementando comunicación asíncrona mediante AJAX y un API REST desarrollado en PHP con Composer.

El proyecto consiste en una aplicación web que permite la gestión integral de recursos digitales orientados a programadores, incluyendo manuales técnicos, librerías de código, ejemplos prácticos y documentación de referencia. La plataforma contempla dos perfiles de usuario: administradores con acceso completo al dashboard de gestión, y visitantes con acceso al catálogo público para consulta y descarga de recursos.

---

# 2. INTRODUCCIÓN

## 2.1 Problema o Necesidad a Resolver

En el entorno actual del desarrollo de software, los programadores enfrentan constantemente la necesidad de acceder a recursos digitales de apoyo: documentación técnica, manuales de frameworks, librerías de código reutilizable, snippets, tutoriales y ejemplos prácticos. Esta información se encuentra dispersa en múltiples fuentes, lo que genera los siguientes problemas:

1. **Fragmentación de recursos**: Los materiales de consulta están distribuidos en diversos sitios web, repositorios y plataformas, dificultando su localización rápida.

2. **Falta de organización centralizada**: No existe un punto único de acceso donde se puedan catalogar, clasificar y gestionar estos recursos de manera eficiente.

3. **Ausencia de métricas de uso**: Las organizaciones y equipos de desarrollo carecen de información estadística sobre qué recursos son más consultados o descargados, impidiendo la toma de decisiones informadas sobre qué materiales priorizar o actualizar.

4. **Dificultad para mantener actualizados los recursos**: Sin un sistema de gestión adecuado, los recursos obsoletos permanecen disponibles mientras que los nuevos no se integran de forma ordenada.

5. **Carencia de trazabilidad**: No se cuenta con registros de quién accede a qué recursos ni cuándo, lo cual es relevante para auditorías internas y análisis de uso.

## 2.2 Justificación del Proyecto

La implementación de un Dashboard para la Gestión de Recursos Digitales orientado a programadores se justifica por las siguientes razones:

### Justificación Técnica

- **Demostración de competencias**: El proyecto permite aplicar de manera integrada las tecnologías estudiadas en el curso: HTML5, CSS, JavaScript, jQuery, PHP, MySQL, AJAX y Chart.js.
- **Arquitectura moderna**: La implementación de un API REST con comunicación asíncrona refleja las prácticas actuales de desarrollo web.
- **Escalabilidad**: La separación entre frontend y backend mediante API facilita futuras extensiones o migraciones tecnológicas.

### Justificación Práctica

- **Centralización de recursos**: Proporciona un repositorio único y organizado para materiales de apoyo técnico.
- **Gestión eficiente**: Los administradores pueden crear, actualizar y dar de baja recursos de forma controlada.
- **Accesibilidad**: Los usuarios visitantes disponen de un catálogo público intuitivo para consultar y descargar recursos.
- **Análisis de datos**: Las gráficas estadísticas permiten identificar patrones de uso y tomar decisiones basadas en datos.

### Justificación Académica

- **Integración de conocimientos**: El proyecto sintetiza los temas abordados a lo largo del curso en una aplicación funcional.
- **Documentación formal**: El proceso de desarrollo genera documentación técnica aplicable en contextos profesionales.
- **Metodología de ingeniería de software**: Se aplican principios de análisis, diseño e implementación sistemática.

---

# 3. OBJETIVOS

## 3.1 Objetivo General

Desarrollar una aplicación web que permita la gestión integral de recursos digitales de apoyo para programadores, mediante un dashboard administrativo y un catálogo público, implementando comunicación asíncrona con un API REST en PHP y visualización de estadísticas con Chart.js, demostrando el dominio de tecnologías web cliente-servidor.

## 3.2 Objetivos Específicos

| ID | Objetivo Específico |
|----|---------------------|
| OE1 | Implementar un sistema de autenticación (signup y login) que permita el registro e inicio de sesión de usuarios administradores, aplicando técnicas de seguridad como hash de contraseñas y validación de entradas. |
| OE2 | Desarrollar un dashboard administrativo tipo SPA (Single Page Application) que permita a los administradores gestionar el catálogo de recursos digitales mediante operaciones CRUD (Crear, Leer, Actualizar, Eliminar lógicamente). |
| OE3 | Crear una página web de catálogo público que permita a los visitantes consultar los recursos disponibles y realizar descargas, sin capacidad de modificar el contenido. |
| OE4 | Diseñar e implementar una base de datos relacional en MySQL que almacene la información de usuarios, recursos digitales y bitácoras de acceso y descargas. |
| OE5 | Desarrollar un API REST en PHP utilizando Composer para la gestión de dependencias, que exponga endpoints para todas las operaciones del sistema. |
| OE6 | Implementar comunicación asíncrona mediante AJAX (utilizando jQuery) entre el frontend y el API REST, garantizando una experiencia de usuario fluida sin recargas de página. |
| OE7 | Integrar al menos tres gráficas estadísticas con Chart.js que muestren métricas relevantes como descargas por tipo de recurso, por lenguaje de programación y por periodo de tiempo. |
| OE8 | Implementar bitácoras de acceso y descargas que registren la actividad del sistema para fines de auditoría y generación de estadísticas. |
| OE9 | Documentar el proceso de desarrollo mediante un reporte técnico que incluya análisis, diseño, implementación y resultados del proyecto. |
| OE10 | Aplicar buenas prácticas de desarrollo web incluyendo separación de capas, validación de datos, protección contra inyección SQL y XSS, y organización modular del código. |

---

# 4. ALCANCE DEL SISTEMA

## 4.1 Funcionalidades Incluidas (Dentro del Alcance)

El sistema **SÍ contempla** las siguientes funcionalidades:

### Módulo de Autenticación
- Registro de nuevos usuarios administradores (signup)
- Inicio de sesión con credenciales (login)
- Cierre de sesión (logout)
- Almacenamiento seguro de contraseñas mediante hash

### Módulo de Dashboard Administrativo
- Visualización del listado completo de recursos digitales
- Creación de nuevos recursos con formulario de captura
- Actualización de recursos existentes
- Eliminación lógica de recursos (soft delete)
- Búsqueda y filtrado de recursos por diversos criterios
- Paginación de resultados

### Módulo de Catálogo Público
- Visualización del catálogo de recursos disponibles
- Filtrado por tipo de recurso, lenguaje de programación, categoría
- Descarga de recursos digitales
- Interfaz de solo lectura (sin opciones de edición)

### Módulo de Estadísticas
- Gráfica de descargas por tipo de recurso
- Gráfica de descargas por lenguaje de programación
- Gráfica de actividad por día de la semana u hora del día
- Actualización dinámica de datos vía AJAX

### Módulo de Bitácoras
- Registro automático de accesos al catálogo público
- Registro de descargas realizadas
- Almacenamiento de metadatos (fecha, hora, IP, recurso)

### API REST
- Endpoints para autenticación (login, logout, verificación de sesión)
- Endpoints CRUD para recursos digitales
- Endpoints para consulta de estadísticas
- Respuestas en formato JSON

## 4.2 Funcionalidades Excluidas (Fuera del Alcance)

El sistema **NO contempla** las siguientes funcionalidades:

| Funcionalidad Excluida | Justificación |
|------------------------|---------------|
| Sistema de roles y permisos granulares | Se maneja únicamente el rol de administrador; un sistema de permisos por módulo excede el alcance del proyecto académico. |
| Procesamiento de pagos | No se requiere monetización ni suscripciones en esta versión. |
| Integración con servicios externos (OAuth, APIs de terceros) | El proyecto se enfoca en desarrollo propio sin dependencias de servicios externos. |
| Aplicación móvil nativa | El alcance se limita a una aplicación web responsive. |
| Sistema de comentarios o valoraciones | No se incluye interacción social entre usuarios. |
| Notificaciones por correo electrónico | No se implementa sistema de notificaciones. |
| Versionado de recursos | No se controlan múltiples versiones de un mismo recurso. |
| Subida de archivos al servidor | Los recursos referencian URLs externas; no se implementa almacenamiento de archivos. |
| Recuperación de contraseña | No se incluye flujo de "olvidé mi contraseña". |
| Internacionalización (i18n) | El sistema se desarrolla únicamente en español. |

## 4.3 Supuestos y Restricciones

### Supuestos
- Se cuenta con un servidor web con soporte para PHP 7.4 o superior.
- Se dispone de un servidor MySQL 5.7 o superior.
- Los usuarios administradores acceden desde navegadores modernos (Chrome, Firefox, Edge).
- La conexión a internet es estable para la carga de librerías CDN (jQuery, Chart.js, Bootstrap).

### Restricciones
- El proyecto debe completarse dentro del periodo académico establecido.
- Se utilizan exclusivamente las tecnologías especificadas en el enunciado del proyecto.
- El desarrollo se realiza en entorno local (XAMPP o similar) con posibilidad de despliegue en servidor compartido.

---

# 5. ACTORES DEL SISTEMA

## 5.1 Diagrama de Actores

```
                    ┌─────────────────────────────────────┐
                    │         SISTEMA WEB                 │
                    │  Dashboard de Recursos Digitales    │
                    └─────────────────────────────────────┘
                                    │
            ┌───────────────────────┼───────────────────────┐
            │                       │                       │
            ▼                       ▼                       ▼
    ┌───────────────┐      ┌───────────────┐      ┌───────────────┐
    │ Administrador │      │   Visitante   │      │   API REST    │
    │    (Actor)    │      │    (Actor)    │      │ (Componente)  │
    └───────────────┘      └───────────────┘      └───────────────┘
            │                       │                       │
            │                       │                       │
            ▼                       ▼                       ▼
    ┌───────────────┐      ┌───────────────┐      ┌───────────────┐
    │   Dashboard   │      │   Catálogo    │      │    Base de    │
    │ Administrativo│      │    Público    │      │     Datos     │
    └───────────────┘      └───────────────┘      └───────────────┘
```

## 5.2 Descripción de Actores

### 5.2.1 Administrador

| Atributo | Descripción |
|----------|-------------|
| **Tipo** | Actor primario (humano) |
| **Descripción** | Usuario registrado con credenciales de acceso que gestiona el catálogo de recursos digitales a través del dashboard administrativo. |
| **Responsabilidades** | - Registrarse en el sistema (signup)<br>- Iniciar y cerrar sesión<br>- Crear nuevos recursos digitales<br>- Actualizar información de recursos existentes<br>- Eliminar lógicamente recursos obsoletos<br>- Consultar listados y realizar búsquedas<br>- Visualizar estadísticas de uso |
| **Objetivos** | Mantener actualizado el catálogo de recursos digitales y monitorear su uso mediante estadísticas. |
| **Requisitos técnicos** | Navegador web moderno, conexión a internet, credenciales de acceso válidas. |

### 5.2.2 Visitante / Cliente

| Atributo | Descripción |
|----------|-------------|
| **Tipo** | Actor primario (humano) |
| **Descripción** | Usuario anónimo que accede al catálogo público para consultar y descargar recursos digitales. No requiere autenticación. |
| **Responsabilidades** | - Navegar por el catálogo de recursos<br>- Aplicar filtros de búsqueda<br>- Descargar recursos de interés |
| **Objetivos** | Encontrar y obtener recursos digitales relevantes para sus necesidades de programación. |
| **Requisitos técnicos** | Navegador web, conexión a internet. |
| **Limitaciones** | No puede crear, modificar ni eliminar recursos. Solo tiene acceso de lectura y descarga. |

### 5.2.3 Sistema de Autenticación (Componente Lógico)

| Atributo | Descripción |
|----------|-------------|
| **Tipo** | Actor secundario (sistema) |
| **Descripción** | Componente del backend responsable de verificar credenciales, gestionar sesiones y controlar el acceso a funcionalidades restringidas. |
| **Responsabilidades** | - Validar credenciales de login<br>- Generar y mantener sesiones de usuario<br>- Verificar permisos de acceso a endpoints protegidos<br>- Hashear contraseñas en el registro |
| **Interacciones** | Se comunica con el Administrador (validación de acceso) y con la Base de Datos (consulta de credenciales). |

### 5.2.4 API REST (Componente Lógico)

| Atributo | Descripción |
|----------|-------------|
| **Tipo** | Actor secundario (sistema) |
| **Descripción** | Capa de servicios web que expone endpoints HTTP para todas las operaciones del sistema, respondiendo en formato JSON. |
| **Responsabilidades** | - Recibir peticiones HTTP (GET, POST, PUT, DELETE)<br>- Validar datos de entrada<br>- Ejecutar lógica de negocio<br>- Interactuar con la base de datos<br>- Retornar respuestas JSON estructuradas |
| **Endpoints principales** | - `/api/auth/login`<br>- `/api/auth/logout`<br>- `/api/recursos`<br>- `/api/estadisticas` |
| **Interacciones** | Recibe peticiones del Dashboard y Catálogo vía AJAX; consulta y modifica datos en MySQL. |

### 5.2.5 Base de Datos MySQL (Componente Lógico)

| Atributo | Descripción |
|----------|-------------|
| **Tipo** | Actor secundario (sistema) |
| **Descripción** | Sistema de gestión de base de datos relacional que almacena toda la información persistente del sistema. |
| **Responsabilidades** | - Almacenar datos de usuarios, recursos y bitácoras<br>- Ejecutar consultas SQL<br>- Mantener integridad referencial<br>- Proveer datos para estadísticas |
| **Tablas principales** | USUARIOS, RECURSOS, BITACORA_ACCESO, BITACORA_DESCARGAS |

