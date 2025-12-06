# DOCUMENTO DE INGENIERÍA DE SOFTWARE - PARTE 3

## Dashboard para la Gestión de Recursos Digitales

---

# 7. CASOS DE USO

## 7.1 Lista de Casos de Uso

A continuación se presenta la lista completa de casos de uso identificados para el sistema, organizados por módulo funcional.

### 7.1.1 Módulo de Autenticación

| ID | Nombre del Caso de Uso | Actor Principal | Prioridad |
|----|------------------------|-----------------|-----------|
| CU01 | Registrarse como administrador | Administrador | Alta |
| CU02 | Iniciar sesión (login) | Administrador | Alta |
| CU03 | Cerrar sesión (logout) | Administrador | Alta |

### 7.1.2 Módulo de Gestión de Recursos (Dashboard)

| ID | Nombre del Caso de Uso | Actor Principal | Prioridad |
|----|------------------------|-----------------|-----------|
| CU04 | Registrar un recurso digital | Administrador | Alta |
| CU05 | Actualizar un recurso digital | Administrador | Alta |
| CU06 | Eliminar lógicamente un recurso digital | Administrador | Alta |
| CU07 | Consultar listado de recursos en el dashboard | Administrador | Alta |
| CU08 | Buscar recursos en el dashboard | Administrador | Media |

### 7.1.3 Módulo de Catálogo Público

| ID | Nombre del Caso de Uso | Actor Principal | Prioridad |
|----|------------------------|-----------------|-----------|
| CU09 | Consultar catálogo público de recursos | Visitante | Alta |
| CU10 | Descargar un recurso desde el catálogo | Visitante | Alta |
| CU11 | Filtrar recursos en el catálogo | Visitante | Media |

### 7.1.4 Módulo de Estadísticas

| ID | Nombre del Caso de Uso | Actor Principal | Prioridad |
|----|------------------------|-----------------|-----------|
| CU12 | Visualizar estadísticas de descargas | Administrador | Alta |
| CU13 | Filtrar estadísticas por periodo | Administrador | Media |

### 7.1.5 Módulo de Bitácoras (Casos de Uso Internos)

| ID | Nombre del Caso de Uso | Actor Principal | Prioridad |
|----|------------------------|-----------------|-----------|
| CU14 | Registrar acceso a catálogo | Sistema (API) | Alta |
| CU15 | Registrar descarga en bitácora | Sistema (API) | Alta |

---

## 7.2 Diagrama General de Casos de Uso (Representación Textual)

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    SISTEMA: Dashboard de Recursos Digitales                  │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                             │
│   ┌─────────────┐                                      ┌─────────────┐      │
│   │             │                                      │             │      │
│   │ Administra- │◄──── CU01: Registrarse              │  Visitante  │      │
│   │    dor      │◄──── CU02: Iniciar sesión           │             │      │
│   │             │◄──── CU03: Cerrar sesión            └──────┬──────┘      │
│   │             │◄──── CU04: Registrar recurso               │             │
│   │             │◄──── CU05: Actualizar recurso              │             │
│   │             │◄──── CU06: Eliminar recurso                │             │
│   │             │◄──── CU07: Consultar listado               │             │
│   │             │◄──── CU08: Buscar recursos                 │             │
│   │             │◄──── CU12: Ver estadísticas                │             │
│   │             │◄──── CU13: Filtrar estadísticas            │             │
│   └─────────────┘                                            │             │
│                                                              │             │
│                              CU09: Consultar catálogo ───────┤             │
│                              CU10: Descargar recurso ────────┤             │
│                              CU11: Filtrar catálogo ─────────┘             │
│                                                                             │
│   ┌─────────────┐                                                           │
│   │   Sistema   │◄──── CU14: Registrar acceso                              │
│   │   (API)     │◄──── CU15: Registrar descarga                            │
│   └─────────────┘                                                           │
│                                                                             │
└─────────────────────────────────────────────────────────────────────────────┘
```

---

## 7.3 Especificación Detallada de Casos de Uso Clave

### 7.3.1 CU02: Iniciar Sesión (Login)

| Campo | Descripción |
|-------|-------------|
| **ID** | CU02 |
| **Nombre** | Iniciar sesión (login) |
| **Actor Principal** | Administrador |
| **Actores Secundarios** | Sistema de Autenticación, API REST, Base de Datos |
| **Descripción** | Permite a un administrador registrado acceder al sistema mediante sus credenciales (usuario/correo y contraseña) para utilizar las funcionalidades del dashboard administrativo. |
| **Trigger** | El administrador accede a la página de login del sistema. |

#### Precondiciones
| # | Precondición |
|---|--------------|
| PRE1 | El usuario debe estar previamente registrado en el sistema (cuenta activa). |
| PRE2 | El usuario no debe tener una sesión activa en el navegador. |
| PRE3 | El sistema debe estar disponible y la base de datos accesible. |

#### Postcondiciones
| # | Postcondición (Éxito) |
|---|----------------------|
| POST1 | Se crea una sesión válida para el usuario autenticado. |
| POST2 | El usuario es redirigido al dashboard administrativo. |
| POST3 | Se registra el acceso en la bitácora del sistema (opcional). |

#### Flujo Principal
| Paso | Actor | Sistema |
|------|-------|---------|
| 1 | El administrador accede a la URL del sistema. | |
| 2 | | El sistema muestra el formulario de login con campos: usuario/correo electrónico y contraseña. |
| 3 | El administrador ingresa su nombre de usuario o correo electrónico. | |
| 4 | El administrador ingresa su contraseña. | |
| 5 | El administrador hace clic en el botón "Iniciar Sesión". | |
| 6 | | El sistema envía una petición POST vía AJAX al endpoint `/api/auth/login` con las credenciales. |
| 7 | | El API valida que los campos no estén vacíos. |
| 8 | | El API busca al usuario en la base de datos por usuario o correo. |
| 9 | | El API verifica la contraseña usando `password_verify()` contra el hash almacenado. |
| 10 | | El API genera una sesión PHP y retorna respuesta JSON de éxito con datos del usuario. |
| 11 | | El frontend recibe la respuesta exitosa y redirige al dashboard. |
| 12 | | El dashboard carga mostrando el nombre del usuario autenticado. |

#### Flujos Alternos

**FA1: Campos vacíos**
| Paso | Descripción |
|------|-------------|
| FA1.1 | En el paso 7, si algún campo está vacío, el API retorna error 400 con mensaje "Todos los campos son obligatorios". |
| FA1.2 | El frontend muestra el mensaje de error junto al formulario. |
| FA1.3 | El flujo regresa al paso 3. |

**FA2: Usuario no encontrado**
| Paso | Descripción |
|------|-------------|
| FA2.1 | En el paso 8, si no existe un usuario con ese nombre o correo, el API retorna error 401. |
| FA2.2 | El frontend muestra mensaje genérico "Credenciales incorrectas". |
| FA2.3 | El flujo regresa al paso 3. |

**FA3: Contraseña incorrecta**
| Paso | Descripción |
|------|-------------|
| FA3.1 | En el paso 9, si la contraseña no coincide con el hash, el API retorna error 401. |
| FA3.2 | El frontend muestra mensaje genérico "Credenciales incorrectas". |
| FA3.3 | El flujo regresa al paso 3. |

**FA4: Error de conexión**
| Paso | Descripción |
|------|-------------|
| FA4.1 | En cualquier paso de comunicación, si hay error de red, el frontend detecta el fallo AJAX. |
| FA4.2 | El frontend muestra mensaje "Error de conexión. Intente nuevamente.". |
| FA4.3 | El flujo regresa al paso 3. |

#### Requisitos Relacionados
RF04, RF05, RF06, RF08, RNF10, RNF11, RNF14

---

### 7.3.2 CU04: Registrar un Recurso Digital

| Campo | Descripción |
|-------|-------------|
| **ID** | CU04 |
| **Nombre** | Registrar un recurso digital |
| **Actor Principal** | Administrador |
| **Actores Secundarios** | API REST, Base de Datos |
| **Descripción** | Permite al administrador crear un nuevo recurso digital en el catálogo, ingresando toda la información necesaria a través del formulario del dashboard. |
| **Trigger** | El administrador hace clic en el botón "Agregar Recurso" dentro del dashboard. |

#### Precondiciones
| # | Precondición |
|---|--------------|
| PRE1 | El administrador debe tener una sesión activa válida. |
| PRE2 | El administrador debe estar en la vista del dashboard administrativo. |

#### Postcondiciones
| # | Postcondición (Éxito) |
|---|----------------------|
| POST1 | El nuevo recurso se almacena en la base de datos con estado activo. |
| POST2 | El recurso aparece en el listado del dashboard. |
| POST3 | El recurso está disponible para consulta en el catálogo público. |
| POST4 | Se muestra mensaje de confirmación al administrador. |

#### Flujo Principal
| Paso | Actor | Sistema |
|------|-------|---------|
| 1 | El administrador hace clic en "Agregar Recurso" o accede al formulario de creación. | |
| 2 | | El sistema muestra un formulario vacío con los campos: nombre, descripción, tipo de recurso (select), lenguaje de programación (select), URL de descarga, imagen/icono (opcional). |
| 3 | El administrador ingresa el nombre del recurso. | |
| 4 | El administrador ingresa la descripción del recurso. | |
| 5 | El administrador selecciona el tipo de recurso (manual, librería, ejemplo, plantilla, etc.). | |
| 6 | El administrador selecciona el lenguaje de programación asociado (JavaScript, PHP, Python, etc.). | |
| 7 | El administrador ingresa la URL de descarga del recurso. | |
| 8 | El administrador opcionalmente ingresa URL de imagen. | |
| 9 | El administrador hace clic en el botón "Guardar" o "Agregar". | |
| 10 | | El sistema valida los campos en el frontend (campos obligatorios, formato de URL). |
| 11 | | El sistema envía petición POST vía AJAX a `/api/recursos` con los datos en formato JSON. |
| 12 | | El API verifica que exista sesión activa (autenticación). |
| 13 | | El API valida los datos recibidos (campos obligatorios, longitudes, formato). |
| 14 | | El API inserta el nuevo registro en la tabla RECURSOS con `created_at` = fecha actual. |
| 15 | | El API retorna respuesta JSON 201 (Created) con los datos del recurso creado. |
| 16 | | El frontend recibe la respuesta exitosa. |
| 17 | | El frontend limpia el formulario y muestra mensaje "Recurso creado exitosamente". |
| 18 | | El frontend actualiza el listado de recursos para mostrar el nuevo registro. |

#### Flujos Alternos

**FA1: Validación frontend fallida**
| Paso | Descripción |
|------|-------------|
| FA1.1 | En el paso 10, si algún campo obligatorio está vacío o tiene formato inválido, el frontend marca el campo con error. |
| FA1.2 | Se muestra mensaje indicando qué campos deben corregirse. |
| FA1.3 | El flujo regresa al paso correspondiente al campo con error. |

**FA2: Sesión expirada**
| Paso | Descripción |
|------|-------------|
| FA2.1 | En el paso 12, si no existe sesión activa, el API retorna error 401 (Unauthorized). |
| FA2.2 | El frontend detecta el código 401 y redirige al usuario a la página de login. |
| FA2.3 | Se muestra mensaje "Su sesión ha expirado. Inicie sesión nuevamente.". |

**FA3: Validación backend fallida**
| Paso | Descripción |
|------|-------------|
| FA3.1 | En el paso 13, si los datos no pasan la validación del servidor, el API retorna error 400 con detalle de errores. |
| FA3.2 | El frontend muestra los mensajes de error retornados por el API. |
| FA3.3 | El flujo regresa al paso 3. |

**FA4: Error de base de datos**
| Paso | Descripción |
|------|-------------|
| FA4.1 | En el paso 14, si ocurre un error al insertar en la base de datos, el API retorna error 500. |
| FA4.2 | El frontend muestra mensaje "Error interno. Intente nuevamente.". |
| FA4.3 | El flujo termina; el administrador puede reintentar. |

#### Requisitos Relacionados
RF11, RF12, RF08, RNF11, RNF12, RNF13

---

### 7.3.3 CU10: Descargar un Recurso desde el Catálogo

| Campo | Descripción |
|-------|-------------|
| **ID** | CU10 |
| **Nombre** | Descargar un recurso desde el catálogo |
| **Actor Principal** | Visitante |
| **Actores Secundarios** | API REST, Base de Datos |
| **Descripción** | Permite al visitante del catálogo público descargar un recurso digital de su interés, registrando la descarga en la bitácora del sistema. |
| **Trigger** | El visitante hace clic en el botón "Descargar" de un recurso específico. |

#### Precondiciones
| # | Precondición |
|---|--------------|
| PRE1 | El visitante debe estar visualizando el catálogo público. |
| PRE2 | El recurso debe estar activo (no eliminado lógicamente). |
| PRE3 | El recurso debe tener una URL de descarga válida. |

#### Postcondiciones
| # | Postcondición (Éxito) |
|---|----------------------|
| POST1 | Se registra la descarga en la tabla BITACORA_DESCARGAS. |
| POST2 | El contador de descargas del recurso se incrementa. |
| POST3 | El visitante recibe/accede al archivo del recurso. |

#### Flujo Principal
| Paso | Actor | Sistema |
|------|-------|---------|
| 1 | El visitante navega por el catálogo público y localiza el recurso deseado. | |
| 2 | El visitante hace clic en el botón "Descargar" del recurso. | |
| 3 | | El sistema captura el evento de clic mediante JavaScript. |
| 4 | | El sistema envía petición POST vía AJAX a `/api/descargas` con el ID del recurso. |
| 5 | | El API registra la descarga en BITACORA_DESCARGAS con: id_recurso, fecha_hora, ip_cliente. |
| 6 | | El API incrementa el campo `descargas` en la tabla RECURSOS para ese registro. |
| 7 | | El API retorna la URL de descarga del recurso en la respuesta JSON. |
| 8 | | El frontend recibe la URL y abre una nueva pestaña/ventana con la URL del recurso. |
| 9 | | El navegador del visitante inicia la descarga del archivo o muestra el recurso. |

#### Flujos Alternos

**FA1: Recurso no encontrado**
| Paso | Descripción |
|------|-------------|
| FA1.1 | En el paso 5, si el ID del recurso no existe o está eliminado, el API retorna error 404. |
| FA1.2 | El frontend muestra mensaje "El recurso solicitado no está disponible.". |
| FA1.3 | El flujo termina. |

**FA2: URL de descarga inválida**
| Paso | Descripción |
|------|-------------|
| FA2.1 | En el paso 8, si la URL retornada está vacía o es inválida, el frontend detecta el problema. |
| FA2.2 | Se muestra mensaje "No se pudo obtener el enlace de descarga.". |
| FA2.3 | El flujo termina. |

**FA3: Error de registro en bitácora**
| Paso | Descripción |
|------|-------------|
| FA3.1 | En el paso 5, si falla el registro en bitácora (error de BD), el API registra el error en log pero continúa. |
| FA3.2 | El flujo continúa desde el paso 7 para no afectar la experiencia del usuario. |

**FA4: Bloqueador de ventanas emergentes**
| Paso | Descripción |
|------|-------------|
| FA4.1 | En el paso 8, si el navegador bloquea la apertura de nueva ventana, no se abre. |
| FA4.2 | El frontend muestra mensaje "Permita las ventanas emergentes para descargar.". |
| FA4.3 | Alternativa: se ofrece copiar la URL al portapapeles. |

#### Requisitos Relacionados
RF24, RF27, RF42, RNF16

---

### 7.3.4 CU12: Visualizar Estadísticas de Descargas

| Campo | Descripción |
|-------|-------------|
| **ID** | CU12 |
| **Nombre** | Visualizar estadísticas de descargas |
| **Actor Principal** | Administrador |
| **Actores Secundarios** | API REST, Base de Datos, Chart.js |
| **Descripción** | Permite al administrador visualizar gráficas estadísticas sobre las descargas de recursos, incluyendo distribución por tipo, por lenguaje de programación y por periodo temporal. |
| **Trigger** | El administrador accede a la sección de "Estadísticas" dentro del dashboard. |

#### Precondiciones
| # | Precondición |
|---|--------------|
| PRE1 | El administrador debe tener una sesión activa válida. |
| PRE2 | Deben existir registros en la tabla BITACORA_DESCARGAS para mostrar datos. |
| PRE3 | La librería Chart.js debe estar correctamente cargada en el frontend. |

#### Postcondiciones
| # | Postcondición (Éxito) |
|---|----------------------|
| POST1 | Se muestran al menos 3 gráficas con datos actualizados. |
| POST2 | Las gráficas reflejan los datos reales de la base de datos. |

#### Flujo Principal
| Paso | Actor | Sistema |
|------|-------|---------|
| 1 | El administrador hace clic en el menú "Estadísticas" del dashboard. | |
| 2 | | El sistema carga la vista de estadísticas con contenedores para las gráficas. |
| 3 | | El sistema envía petición GET vía AJAX a `/api/estadisticas/por-tipo`. |
| 4 | | El API consulta la BD y calcula descargas agrupadas por tipo de recurso. |
| 5 | | El API retorna JSON con labels (tipos) y valores (cantidades). |
| 6 | | El frontend renderiza la gráfica de "Descargas por Tipo de Recurso" usando Chart.js (gráfica de pastel o barras). |
| 7 | | Paralelamente o secuencialmente, el sistema envía petición GET a `/api/estadisticas/por-lenguaje`. |
| 8 | | El API consulta y retorna descargas agrupadas por lenguaje de programación. |
| 9 | | El frontend renderiza la gráfica de "Descargas por Lenguaje" (gráfica de barras horizontales o dona). |
| 10 | | El sistema envía petición GET a `/api/estadisticas/por-dia` o `/api/estadisticas/por-hora`. |
| 11 | | El API consulta y retorna descargas agrupadas por día de la semana u hora del día. |
| 12 | | El frontend renderiza la gráfica de "Actividad por Día/Hora" (gráfica de línea o barras). |
| 13 | | El administrador visualiza las tres gráficas con los datos correspondientes. |

#### Flujos Alternos

**FA1: Sin datos en bitácora**
| Paso | Descripción |
|------|-------------|
| FA1.1 | En los pasos 4, 8 u 11, si no existen registros de descargas, el API retorna arreglos vacíos. |
| FA1.2 | El frontend renderiza las gráficas vacías o muestra mensaje "No hay datos disponibles aún". |
| FA1.3 | El flujo continúa normalmente. |

**FA2: Error en petición de estadísticas**
| Paso | Descripción |
|------|-------------|
| FA2.1 | Si alguna petición AJAX falla, el frontend captura el error. |
| FA2.2 | Se muestra mensaje de error en el contenedor de la gráfica afectada. |
| FA2.3 | Las demás gráficas se cargan normalmente si sus peticiones son exitosas. |

**FA3: Sesión expirada**
| Paso | Descripción |
|------|-------------|
| FA3.1 | Si el API detecta sesión inválida, retorna error 401. |
| FA3.2 | El frontend redirige al login con mensaje de sesión expirada. |

#### Requisitos Relacionados
RF29, RF30, RF31, RF32, RF33, RNF04

---

## 7.4 Relaciones entre Casos de Uso

### 7.4.1 Relaciones de Inclusión (<<include>>)

| Caso de Uso Base | Incluye | Descripción |
|------------------|---------|-------------|
| CU04: Registrar recurso | Verificar sesión activa | Antes de cualquier operación de gestión, se verifica autenticación. |
| CU05: Actualizar recurso | Verificar sesión activa | Ídem. |
| CU06: Eliminar recurso | Verificar sesión activa | Ídem. |
| CU10: Descargar recurso | CU15: Registrar descarga | Toda descarga se registra automáticamente en bitácora. |
| CU09: Consultar catálogo | CU14: Registrar acceso | Todo acceso al catálogo se registra en bitácora. |

### 7.4.2 Relaciones de Extensión (<<extend>>)

| Caso de Uso Base | Extendido por | Condición |
|------------------|---------------|-----------|
| CU07: Consultar listado | CU08: Buscar recursos | Si el administrador ingresa criterio de búsqueda. |
| CU09: Consultar catálogo | CU11: Filtrar recursos | Si el visitante aplica filtros de búsqueda. |
| CU12: Ver estadísticas | CU13: Filtrar por periodo | Si el administrador selecciona rango de fechas. |

