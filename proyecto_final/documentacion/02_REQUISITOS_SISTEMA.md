# DOCUMENTO DE INGENIERÍA DE SOFTWARE - PARTE 2

## Dashboard para la Gestión de Recursos Digitales

---

# 6. REQUISITOS DEL SISTEMA

## 6.1 Requisitos Funcionales

Los requisitos funcionales describen las funcionalidades específicas que el sistema debe proporcionar. Se identifican con el código **RF** (Requisito Funcional) seguido de un número secuencial.

### 6.1.1 Módulo de Autenticación

| ID | Nombre | Descripción | Prioridad |
|----|--------|-------------|-----------|
| RF01 | Registro de administrador | El sistema debe permitir el registro de nuevos usuarios administradores mediante un formulario de signup que solicite: nombre completo, correo electrónico (único), nombre de usuario (único) y contraseña. | Alta |
| RF02 | Validación de registro | El sistema debe validar que el correo electrónico y nombre de usuario no existan previamente en la base de datos antes de completar el registro. | Alta |
| RF03 | Hash de contraseña | El sistema debe almacenar las contraseñas utilizando un algoritmo de hash seguro (bcrypt o similar), nunca en texto plano. | Alta |
| RF04 | Inicio de sesión | El sistema debe permitir a los administradores iniciar sesión mediante un formulario de login que solicite nombre de usuario/correo y contraseña. | Alta |
| RF05 | Verificación de credenciales | El sistema debe verificar las credenciales ingresadas contra la base de datos y generar una sesión válida si son correctas. | Alta |
| RF06 | Mensaje de error en login | El sistema debe mostrar un mensaje de error genérico cuando las credenciales sean incorrectas, sin especificar si el error es en usuario o contraseña. | Media |
| RF07 | Cierre de sesión | El sistema debe permitir a los administradores cerrar sesión, destruyendo la sesión activa y redirigiendo a la página de login. | Alta |
| RF08 | Protección de rutas | El sistema debe verificar la existencia de una sesión válida antes de permitir el acceso al dashboard administrativo. | Alta |

### 6.1.2 Módulo de Dashboard Administrativo (Gestión de Recursos)

| ID | Nombre | Descripción | Prioridad |
|----|--------|-------------|-----------|
| RF09 | Listar recursos | El sistema debe mostrar un listado de todos los recursos digitales activos (no eliminados lógicamente) en formato de tabla dentro del dashboard. | Alta |
| RF10 | Paginación de recursos | El sistema debe implementar paginación en el listado de recursos, mostrando un número configurable de registros por página. | Media |
| RF11 | Crear recurso | El sistema debe permitir la creación de nuevos recursos digitales mediante un formulario que capture: nombre, descripción, tipo de recurso, lenguaje de programación, URL de descarga, y otros campos relevantes. | Alta |
| RF12 | Validación de creación | El sistema debe validar los campos obligatorios y formatos antes de guardar un nuevo recurso (nombre no vacío, URL válida, etc.). | Alta |
| RF13 | Actualizar recurso | El sistema debe permitir la actualización de recursos existentes, precargando los datos actuales en el formulario de edición. | Alta |
| RF14 | Eliminación lógica | El sistema debe implementar eliminación lógica (soft delete) de recursos, marcándolos con un campo `deleted_at` o `eliminado` en lugar de eliminarlos físicamente. | Alta |
| RF15 | Búsqueda de recursos | El sistema debe permitir la búsqueda de recursos por nombre, tipo, lenguaje de programación o descripción. | Alta |
| RF16 | Filtrado de recursos | El sistema debe permitir filtrar el listado de recursos por tipo de recurso y/o lenguaje de programación. | Media |
| RF17 | Ordenamiento de recursos | El sistema debe permitir ordenar el listado de recursos por nombre, fecha de creación o número de descargas. | Baja |
| RF18 | Confirmación de eliminación | El sistema debe solicitar confirmación antes de ejecutar la eliminación lógica de un recurso. | Media |

### 6.1.3 Módulo de Catálogo Público

| ID | Nombre | Descripción | Prioridad |
|----|--------|-------------|-----------|
| RF19 | Visualizar catálogo | El sistema debe mostrar una página HTML pública con el listado de recursos digitales disponibles (activos). | Alta |
| RF20 | Diseño de solo lectura | La página de catálogo NO debe incluir botones ni opciones para crear, editar o eliminar recursos. | Alta |
| RF21 | Información del recurso | El catálogo debe mostrar para cada recurso: nombre, descripción breve, tipo, lenguaje de programación y botón de descarga. | Alta |
| RF22 | Filtrado en catálogo | El catálogo debe permitir filtrar recursos por tipo y/o lenguaje de programación. | Media |
| RF23 | Búsqueda en catálogo | El catálogo debe permitir buscar recursos por nombre o descripción. | Media |
| RF24 | Descarga de recurso | El sistema debe permitir la descarga del recurso mediante clic en un botón que redirija a la URL del archivo o inicie la descarga. | Alta |
| RF25 | Carga asíncrona | El catálogo debe cargar los recursos de forma asíncrona mediante AJAX, sin recargar la página completa. | Alta |

### 6.1.4 Módulo de Bitácoras

| ID | Nombre | Descripción | Prioridad |
|----|--------|-------------|-----------|
| RF26 | Registro de acceso | El sistema debe registrar automáticamente cada acceso al catálogo público, almacenando: fecha/hora, dirección IP y página accedida. | Alta |
| RF27 | Registro de descarga | El sistema debe registrar automáticamente cada descarga realizada, almacenando: fecha/hora, dirección IP, ID del recurso descargado. | Alta |
| RF28 | Consulta de bitácora | El administrador debe poder consultar los registros de bitácora desde el dashboard (opcional: filtrado por fechas). | Media |

### 6.1.5 Módulo de Estadísticas

| ID | Nombre | Descripción | Prioridad |
|----|--------|-------------|-----------|
| RF29 | Gráfica por tipo de recurso | El sistema debe mostrar una gráfica (Chart.js) con la distribución de descargas por tipo de recurso (manual, librería, ejemplo, etc.). | Alta |
| RF30 | Gráfica por lenguaje | El sistema debe mostrar una gráfica con la distribución de descargas por lenguaje de programación (JavaScript, PHP, Python, etc.). | Alta |
| RF31 | Gráfica temporal | El sistema debe mostrar una gráfica con la actividad de descargas por día de la semana o por hora del día. | Alta |
| RF32 | Datos vía API | Las gráficas deben obtener sus datos mediante peticiones AJAX al API REST, no mediante datos embebidos en el HTML. | Alta |
| RF33 | Actualización de gráficas | Las gráficas deben poder actualizarse dinámicamente al cambiar filtros de fecha o categoría. | Media |

### 6.1.6 API REST

| ID | Nombre | Descripción | Prioridad |
|----|--------|-------------|-----------|
| RF34 | Endpoint de login | El API debe exponer `POST /api/auth/login` para autenticación, recibiendo credenciales y retornando token/sesión o error. | Alta |
| RF35 | Endpoint de logout | El API debe exponer `POST /api/auth/logout` para cerrar sesión. | Alta |
| RF36 | Endpoint listar recursos | El API debe exponer `GET /api/recursos` para obtener el listado de recursos activos, con soporte para parámetros de paginación, búsqueda y filtros. | Alta |
| RF37 | Endpoint recurso individual | El API debe exponer `GET /api/recursos/{id}` para obtener los detalles de un recurso específico. | Alta |
| RF38 | Endpoint crear recurso | El API debe exponer `POST /api/recursos` para crear un nuevo recurso, requiriendo autenticación. | Alta |
| RF39 | Endpoint actualizar recurso | El API debe exponer `PUT /api/recursos/{id}` para actualizar un recurso existente, requiriendo autenticación. | Alta |
| RF40 | Endpoint eliminar recurso | El API debe exponer `DELETE /api/recursos/{id}` para eliminar lógicamente un recurso, requiriendo autenticación. | Alta |
| RF41 | Endpoint de estadísticas | El API debe exponer `GET /api/estadisticas/{tipo}` para obtener datos de estadísticas según el tipo solicitado. | Alta |
| RF42 | Registro de descarga vía API | El API debe exponer `POST /api/descargas` para registrar una descarga en la bitácora. | Alta |
| RF43 | Formato JSON | Todos los endpoints del API deben retornar respuestas en formato JSON con estructura consistente. | Alta |
| RF44 | Códigos HTTP | El API debe retornar códigos de estado HTTP apropiados: 200 (éxito), 201 (creado), 400 (error de validación), 401 (no autorizado), 404 (no encontrado), 500 (error interno). | Alta |

---

## 6.2 Requisitos No Funcionales

Los requisitos no funcionales definen atributos de calidad y restricciones técnicas del sistema. Se identifican con el código **RNF** (Requisito No Funcional).

### 6.2.1 Tecnologías y Stack Técnico

| ID | Categoría | Descripción |
|----|-----------|-------------|
| RNF01 | Frontend - Estructura | El sistema debe utilizar HTML5 o XHTML para la estructura y marcado de las páginas web. |
| RNF02 | Frontend - Estilos | El sistema debe utilizar CSS puro o Bootstrap para el diseño visual y responsive de la interfaz. |
| RNF03 | Frontend - Interactividad | El sistema debe utilizar JavaScript y jQuery para la manipulación del DOM y la lógica del lado del cliente. |
| RNF04 | Frontend - Gráficas | El sistema debe utilizar Chart.js para la generación de gráficas estadísticas. |
| RNF05 | Comunicación | El sistema debe implementar comunicación asíncrona mediante AJAX (preferentemente con jQuery.ajax o fetch). |
| RNF06 | Backend - Lenguaje | El backend del sistema debe desarrollarse en PHP versión 7.4 o superior. |
| RNF07 | Backend - Dependencias | El backend debe utilizar Composer para la gestión de dependencias y autoloading. |
| RNF08 | Base de datos | El sistema debe utilizar MySQL versión 5.7 o superior como sistema de gestión de base de datos. |
| RNF09 | Servidor web | El sistema debe ser compatible con Apache (XAMPP) o Nginx para su despliegue. |

### 6.2.2 Seguridad

| ID | Categoría | Descripción |
|----|-----------|-------------|
| RNF10 | Hash de contraseñas | Las contraseñas deben almacenarse utilizando el algoritmo bcrypt con un costo mínimo de 10. |
| RNF11 | Prevención SQL Injection | Todas las consultas a base de datos deben utilizar sentencias preparadas (prepared statements) con parámetros vinculados. |
| RNF12 | Prevención XSS | Todos los datos mostrados en el frontend deben escaparse adecuadamente usando `htmlspecialchars()` o funciones equivalentes. |
| RNF13 | Validación de entrada | Todos los datos recibidos del cliente deben validarse y sanitizarse antes de su procesamiento. |
| RNF14 | Gestión de sesiones | Las sesiones deben configurarse con parámetros seguros: httponly, secure (en producción), y regeneración de ID tras login. |
| RNF15 | Protección CSRF | Los formularios deben incluir tokens CSRF para prevenir ataques de falsificación de peticiones (deseable). |

### 6.2.3 Rendimiento

| ID | Categoría | Descripción |
|----|-----------|-------------|
| RNF16 | Tiempo de respuesta | Las peticiones al API deben responder en menos de 2 segundos bajo condiciones normales de carga. |
| RNF17 | Optimización de consultas | Las consultas a base de datos deben utilizar índices apropiados para campos de búsqueda y filtrado frecuente. |
| RNF18 | Paginación | Los listados con más de 20 registros deben implementar paginación para evitar cargas excesivas. |
| RNF19 | Carga de recursos | Los recursos estáticos (CSS, JS) deben minimizarse en producción o cargarse desde CDN. |

### 6.2.4 Usabilidad

| ID | Categoría | Descripción |
|----|-----------|-------------|
| RNF20 | Diseño responsive | La interfaz debe adaptarse correctamente a dispositivos de escritorio, tablet y móvil. |
| RNF21 | Feedback visual | El sistema debe proporcionar retroalimentación visual clara para acciones del usuario (mensajes de éxito, error, loading). |
| RNF22 | Navegación intuitiva | La navegación debe ser clara y consistente, con menús y breadcrumbs cuando sea necesario. |
| RNF23 | Mensajes claros | Los mensajes de error deben ser comprensibles para el usuario, indicando cómo corregir el problema. |

### 6.2.5 Mantenibilidad

| ID | Categoría | Descripción |
|----|-----------|-------------|
| RNF24 | Separación de capas | El código debe organizarse siguiendo el patrón de separación de responsabilidades (presentación, lógica, datos). |
| RNF25 | Código documentado | El código fuente debe incluir comentarios explicativos en funciones y secciones críticas. |
| RNF26 | Nomenclatura | Se debe seguir una convención de nomenclatura consistente para variables, funciones y archivos. |
| RNF27 | Estructura de archivos | El proyecto debe seguir una estructura de directorios organizada y lógica. |
| RNF28 | Versionamiento | El código debe gestionarse mediante un sistema de control de versiones (Git). |

### 6.2.6 Compatibilidad

| ID | Categoría | Descripción |
|----|-----------|-------------|
| RNF29 | Navegadores | El sistema debe ser compatible con las últimas versiones de Chrome, Firefox, Edge y Safari. |
| RNF30 | Resoluciones | El sistema debe visualizarse correctamente en resoluciones desde 320px hasta 1920px de ancho. |

---

## 6.3 Matriz de Trazabilidad Requisitos - Objetivos

| Objetivo | Requisitos Funcionales Relacionados |
|----------|-------------------------------------|
| OE1 (Autenticación) | RF01, RF02, RF03, RF04, RF05, RF06, RF07, RF08 |
| OE2 (Dashboard) | RF09, RF10, RF11, RF12, RF13, RF14, RF15, RF16, RF17, RF18 |
| OE3 (Catálogo público) | RF19, RF20, RF21, RF22, RF23, RF24, RF25 |
| OE4 (Base de datos) | RF26, RF27, RF28 (relacionados con estructura de datos) |
| OE5 (API REST) | RF34, RF35, RF36, RF37, RF38, RF39, RF40, RF41, RF42, RF43, RF44 |
| OE6 (AJAX) | RF25, RF32 |
| OE7 (Chart.js) | RF29, RF30, RF31, RF32, RF33 |
| OE8 (Bitácoras) | RF26, RF27, RF28 |

