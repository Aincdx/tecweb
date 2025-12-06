# DOCUMENTO DE INGENIERÍA DE SOFTWARE - PARTE 6

## Dashboard para la Gestión de Recursos Digitales

---

# 10. DISEÑO DE INTERFAZ (MAQUETADO)

## 10.1 Introducción al Diseño de Interfaces

El diseño de las interfaces de usuario se basa en principios de usabilidad, accesibilidad y consistencia visual. Las pantallas se diseñarán utilizando herramientas como MockFlow o Miro, siguiendo las especificaciones descritas en esta sección.

Se propone utilizar **Bootstrap 5** como framework CSS para garantizar un diseño responsive y componentes UI estandarizados.

## 10.2 Pantallas del Sistema

### 10.2.1 Pantalla de Login

**Propósito**: Permitir a los administradores autenticarse para acceder al dashboard.

**Componentes principales**:

| Componente | Tipo | Descripción |
|------------|------|-------------|
| Logo/Título | Imagen/Texto | Logo del sistema y título "Dashboard de Recursos Digitales" |
| Campo Usuario | Input text | Campo para ingresar nombre de usuario o correo electrónico |
| Campo Contraseña | Input password | Campo para ingresar la contraseña (con opción mostrar/ocultar) |
| Botón "Iniciar Sesión" | Button submit | Botón principal para enviar credenciales |
| Enlace "Crear cuenta" | Link | Enlace a la página de registro (signup) |
| Mensaje de error | Div alert | Área para mostrar mensajes de error de autenticación |

**Wireframe textual**:
```
┌────────────────────────────────────────────────────────────┐
│                                                            │
│                    [LOGO]                                  │
│           Dashboard de Recursos Digitales                  │
│                                                            │
│    ┌──────────────────────────────────────────────────┐   │
│    │                                                  │   │
│    │         Iniciar Sesión                          │   │
│    │                                                  │   │
│    │    Usuario o correo electrónico                 │   │
│    │    ┌────────────────────────────────────────┐   │   │
│    │    │                                        │   │   │
│    │    └────────────────────────────────────────┘   │   │
│    │                                                  │   │
│    │    Contraseña                                   │   │
│    │    ┌────────────────────────────────────────┐   │   │
│    │    │                                    [👁] │   │   │
│    │    └────────────────────────────────────────┘   │   │
│    │                                                  │   │
│    │    ┌────────────────────────────────────────┐   │   │
│    │    │         INICIAR SESIÓN                 │   │   │
│    │    └────────────────────────────────────────┘   │   │
│    │                                                  │   │
│    │    ¿No tienes cuenta? Regístrate aquí          │   │
│    │                                                  │   │
│    └──────────────────────────────────────────────────┘   │
│                                                            │
└────────────────────────────────────────────────────────────┘
```

**Validaciones en frontend**:
- Campo usuario: requerido, mínimo 3 caracteres
- Campo contraseña: requerido, mínimo 6 caracteres
- Mostrar spinner durante petición AJAX

---

### 10.2.2 Pantalla de Signup (Registro)

**Propósito**: Permitir el registro de nuevos administradores en el sistema.

**Componentes principales**:

| Componente | Tipo | Descripción |
|------------|------|-------------|
| Campo Nombre | Input text | Nombre completo del administrador |
| Campo Email | Input email | Correo electrónico (validación de formato) |
| Campo Usuario | Input text | Nombre de usuario único |
| Campo Contraseña | Input password | Contraseña con indicador de fortaleza |
| Campo Confirmar | Input password | Confirmación de contraseña |
| Botón "Registrarse" | Button submit | Botón para enviar formulario de registro |
| Enlace "Ya tengo cuenta" | Link | Enlace de regreso a login |

**Wireframe textual**:
```
┌────────────────────────────────────────────────────────────┐
│                                                            │
│                    [LOGO]                                  │
│              Crear Nueva Cuenta                            │
│                                                            │
│    ┌──────────────────────────────────────────────────┐   │
│    │                                                  │   │
│    │    Nombre completo                              │   │
│    │    ┌────────────────────────────────────────┐   │   │
│    │    │                                        │   │   │
│    │    └────────────────────────────────────────┘   │   │
│    │                                                  │   │
│    │    Correo electrónico                           │   │
│    │    ┌────────────────────────────────────────┐   │   │
│    │    │                                        │   │   │
│    │    └────────────────────────────────────────┘   │   │
│    │                                                  │   │
│    │    Nombre de usuario                            │   │
│    │    ┌────────────────────────────────────────┐   │   │
│    │    │                                        │   │   │
│    │    └────────────────────────────────────────┘   │   │
│    │                                                  │   │
│    │    Contraseña            [████████░░░░] Fuerte  │   │
│    │    ┌────────────────────────────────────────┐   │   │
│    │    │                                        │   │   │
│    │    └────────────────────────────────────────┘   │   │
│    │                                                  │   │
│    │    Confirmar contraseña                         │   │
│    │    ┌────────────────────────────────────────┐   │   │
│    │    │                                        │   │   │
│    │    └────────────────────────────────────────┘   │   │
│    │                                                  │   │
│    │    ┌────────────────────────────────────────┐   │   │
│    │    │           REGISTRARSE                  │   │   │
│    │    └────────────────────────────────────────┘   │   │
│    │                                                  │   │
│    │    ¿Ya tienes cuenta? Inicia sesión            │   │
│    │                                                  │   │
│    └──────────────────────────────────────────────────┘   │
│                                                            │
└────────────────────────────────────────────────────────────┘
```

**Validaciones en frontend**:
- Nombre: requerido, 3-100 caracteres
- Email: requerido, formato válido
- Usuario: requerido, 3-50 caracteres, alfanumérico
- Contraseña: requerido, mínimo 8 caracteres
- Confirmar: debe coincidir con contraseña

---

### 10.2.3 Dashboard Administrativo (SPA)

**Propósito**: Interfaz principal para que los administradores gestionen el catálogo de recursos digitales y visualicen estadísticas.

**Layout general**:
```
┌────────────────────────────────────────────────────────────────────────┐
│  [LOGO] Dashboard de Recursos         🔔  👤 Admin ▼  [Cerrar Sesión] │
├──────────────┬─────────────────────────────────────────────────────────┤
│              │                                                         │
│  MENÚ       │                    ÁREA DE CONTENIDO                     │
│              │                                                         │
│  📊 Inicio   │   ┌─────────────────────────────────────────────────┐  │
│  📁 Recursos │   │                                                 │  │
│  📈 Estadíst.│   │         (Contenido dinámico según               │  │
│  📋 Bitácora │   │          sección seleccionada)                  │  │
│              │   │                                                 │  │
│              │   │                                                 │  │
│              │   │                                                 │  │
│              │   │                                                 │  │
│              │   └─────────────────────────────────────────────────┘  │
│              │                                                         │
└──────────────┴─────────────────────────────────────────────────────────┘
```

**Sección: Gestión de Recursos**

| Componente | Tipo | Descripción |
|------------|------|-------------|
| Barra de búsqueda | Input search | Búsqueda por nombre o descripción |
| Filtro por tipo | Select | Dropdown para filtrar por tipo de recurso |
| Filtro por lenguaje | Select | Dropdown para filtrar por lenguaje |
| Botón "Nuevo Recurso" | Button | Abre modal/formulario de creación |
| Tabla de recursos | Table | Listado con columnas: ID, Nombre, Tipo, Lenguaje, Descargas, Acciones |
| Acciones por fila | Buttons | Botones Editar ✏️ y Eliminar 🗑️ |
| Paginación | Nav | Controles de página (Anterior, números, Siguiente) |

**Wireframe de sección Recursos**:
```
┌─────────────────────────────────────────────────────────────────────────┐
│                                                                         │
│  Gestión de Recursos Digitales                    [+ Nuevo Recurso]    │
│                                                                         │
│  ┌──────────────────┐ ┌─────────────┐ ┌─────────────┐                  │
│  │ 🔍 Buscar...     │ │ Tipo    ▼   │ │ Lenguaje ▼  │  [Buscar]       │
│  └──────────────────┘ └─────────────┘ └─────────────┘                  │
│                                                                         │
│  ┌─────────────────────────────────────────────────────────────────┐   │
│  │ ID │ Nombre              │ Tipo     │ Lenguaje │ Desc │ Acciones│   │
│  ├────┼─────────────────────┼──────────┼──────────┼──────┼─────────┤   │
│  │ 1  │ Manual JavaScript   │ Manual   │ JS       │ 156  │ ✏️ 🗑️  │   │
│  │ 2  │ jQuery Library      │ Librería │ JS       │ 245  │ ✏️ 🗑️  │   │
│  │ 3  │ CRUD PHP MySQL      │ Ejemplo  │ PHP      │ 89   │ ✏️ 🗑️  │   │
│  │ 4  │ Bootstrap Template  │ Plantilla│ General  │ 67   │ ✏️ 🗑️  │   │
│  │ 5  │ API REST Docs       │ Doc      │ General  │ 45   │ ✏️ 🗑️  │   │
│  └─────────────────────────────────────────────────────────────────┘   │
│                                                                         │
│                     [<] [1] [2] [3] [4] [5] [>]                        │
│                                                                         │
└─────────────────────────────────────────────────────────────────────────┘
```

**Modal de Creación/Edición de Recurso**:
```
┌─────────────────────────────────────────────────────┐
│  [X]                                                │
│        Nuevo Recurso / Editar Recurso               │
│  ─────────────────────────────────────────────────  │
│                                                     │
│  Nombre del recurso *                               │
│  ┌─────────────────────────────────────────────┐   │
│  │                                             │   │
│  └─────────────────────────────────────────────┘   │
│                                                     │
│  Descripción                                        │
│  ┌─────────────────────────────────────────────┐   │
│  │                                             │   │
│  │                                             │   │
│  └─────────────────────────────────────────────┘   │
│                                                     │
│  Tipo de recurso *         Lenguaje                 │
│  ┌──────────────────┐     ┌──────────────────┐     │
│  │ Seleccionar... ▼ │     │ Seleccionar... ▼ │     │
│  └──────────────────┘     └──────────────────┘     │
│                                                     │
│  URL de descarga *                                  │
│  ┌─────────────────────────────────────────────┐   │
│  │ https://                                    │   │
│  └─────────────────────────────────────────────┘   │
│                                                     │
│  URL de imagen (opcional)                           │
│  ┌─────────────────────────────────────────────┐   │
│  │ https://                                    │   │
│  └─────────────────────────────────────────────┘   │
│                                                     │
│  ┌─────────────────┐  ┌─────────────────┐          │
│  │    Cancelar     │  │     Guardar     │          │
│  └─────────────────┘  └─────────────────┘          │
│                                                     │
└─────────────────────────────────────────────────────┘
```

**Sección: Estadísticas (con Chart.js)**:
```
┌─────────────────────────────────────────────────────────────────────────┐
│                                                                         │
│  Estadísticas de Descargas                                             │
│                                                                         │
│  Filtrar por periodo: [Fecha inicio] [Fecha fin] [Aplicar]             │
│                                                                         │
│  ┌────────────────────────────┐  ┌────────────────────────────┐        │
│  │                            │  │                            │        │
│  │   DESCARGAS POR TIPO       │  │   DESCARGAS POR LENGUAJE   │        │
│  │                            │  │                            │        │
│  │       [Gráfica Pie]        │  │    [Gráfica Barras]        │        │
│  │                            │  │                            │        │
│  │    Manual: 35%             │  │   JS   ████████ 312        │        │
│  │    Librería: 25%           │  │   PHP  █████░░░ 198        │        │
│  │    Ejemplo: 20%            │  │   Python████░░░ 176        │        │
│  │    Otros: 20%              │  │   Java ██░░░░░░ 89         │        │
│  │                            │  │                            │        │
│  └────────────────────────────┘  └────────────────────────────┘        │
│                                                                         │
│  ┌──────────────────────────────────────────────────────────────────┐  │
│  │                                                                  │  │
│  │                  ACTIVIDAD POR DÍA DE LA SEMANA                  │  │
│  │                                                                  │  │
│  │                      [Gráfica de Línea/Barras]                   │  │
│  │   250 ┤                                                          │  │
│  │   200 ┤            ████                                          │  │
│  │   150 ┤       ████ ████ ████                                     │  │
│  │   100 ┤  ████ ████ ████ ████ ████                                │  │
│  │    50 ┤  ████ ████ ████ ████ ████ ████                           │  │
│  │     0 └──Lun──Mar──Mié──Jue──Vie──Sáb──Dom──                     │  │
│  │                                                                  │  │
│  └──────────────────────────────────────────────────────────────────┘  │
│                                                                         │
└─────────────────────────────────────────────────────────────────────────┘
```

---

### 10.2.4 Catálogo Público

**Propósito**: Página HTML pública que permite a los visitantes consultar y descargar recursos digitales. **No incluye opciones de crear, editar ni eliminar**.

**Componentes principales**:

| Componente | Tipo | Descripción |
|------------|------|-------------|
| Header | Nav | Logo, título y descripción del catálogo |
| Barra de búsqueda | Input search | Búsqueda por nombre o descripción |
| Filtro por tipo | Select/Chips | Filtrar por tipo de recurso |
| Filtro por lenguaje | Select/Chips | Filtrar por lenguaje de programación |
| Grid/Lista de recursos | Cards/Table | Tarjetas con info del recurso y botón descargar |
| Botón Descargar | Button | Inicia descarga y registra en bitácora |
| Paginación | Nav | Navegación entre páginas de resultados |
| Footer | Footer | Información de contacto, créditos |

**Wireframe del Catálogo Público**:
```
┌────────────────────────────────────────────────────────────────────────────┐
│  [LOGO]  Catálogo de Recursos Digitales para Programadores                │
│  ──────────────────────────────────────────────────────────────────────── │
│                                                                            │
│  ┌─────────────────────────────────────────────────────────────────────┐  │
│  │ 🔍 Buscar recursos...                                      [Buscar] │  │
│  └─────────────────────────────────────────────────────────────────────┘  │
│                                                                            │
│  Filtrar por:  [Todos los tipos ▼]  [Todos los lenguajes ▼]               │
│                                                                            │
│  ┌─────────────────────┐ ┌─────────────────────┐ ┌─────────────────────┐  │
│  │ [IMG]               │ │ [IMG]               │ │ [IMG]               │  │
│  │                     │ │                     │ │                     │  │
│  │ Manual JavaScript   │ │ jQuery Library      │ │ CRUD PHP MySQL      │  │
│  │ ES6+                │ │ 3.x                 │ │                     │  │
│  │ ───────────────     │ │ ───────────────     │ │ ───────────────     │  │
│  │ Guía completa de    │ │ Librería para       │ │ Ejemplos prácticos  │  │
│  │ ECMAScript 6...     │ │ manipulación DOM... │ │ de operaciones...   │  │
│  │                     │ │                     │ │                     │  │
│  │ 📁 Manual           │ │ 📁 Librería         │ │ 📁 Ejemplo          │  │
│  │ 💻 JavaScript       │ │ 💻 JavaScript       │ │ 💻 PHP              │  │
│  │ ⬇️ 156 descargas    │ │ ⬇️ 245 descargas    │ │ ⬇️ 89 descargas     │  │
│  │                     │ │                     │ │                     │  │
│  │  [    Descargar   ] │ │  [    Descargar   ] │ │  [    Descargar   ] │  │
│  └─────────────────────┘ └─────────────────────┘ └─────────────────────┘  │
│                                                                            │
│  ┌─────────────────────┐ ┌─────────────────────┐ ┌─────────────────────┐  │
│  │ [IMG]               │ │ [IMG]               │ │ [IMG]               │  │
│  │ ...                 │ │ ...                 │ │ ...                 │  │
│  └─────────────────────┘ └─────────────────────┘ └─────────────────────┘  │
│                                                                            │
│                       [<] [1] [2] [3] [>]                                  │
│                                                                            │
│  ──────────────────────────────────────────────────────────────────────── │
│  © 2024 Dashboard de Recursos Digitales | Proyecto Académico              │
└────────────────────────────────────────────────────────────────────────────┘
```

**Nota importante**: El catálogo público **NO** tiene:
- Botón "Nuevo Recurso"
- Botón "Editar" en las tarjetas
- Botón "Eliminar" en las tarjetas
- Acceso al dashboard administrativo
- Formularios de creación/edición

Es una interfaz de **solo lectura** con capacidad de **descarga**.

---

## 10.3 Paleta de Colores Sugerida

| Uso | Color | Código Hex |
|-----|-------|------------|
| Primario | Azul | #007BFF |
| Secundario | Gris oscuro | #6C757D |
| Éxito | Verde | #28A745 |
| Error | Rojo | #DC3545 |
| Advertencia | Amarillo | #FFC107 |
| Info | Cyan | #17A2B8 |
| Fondo | Blanco/Gris claro | #FFFFFF / #F8F9FA |
| Texto | Gris oscuro | #212529 |

---

# 11. GESTIÓN DEL PROYECTO Y HERRAMIENTAS

## 11.1 Metodología de Trabajo

Se sugiere utilizar una metodología ágil simplificada, dividiendo el desarrollo en iteraciones o sprints semanales, con entregas incrementales de funcionalidad.

## 11.2 Herramientas de Gestión y Diseño

### 11.2.1 Trello - Gestión de Tareas

**Propósito**: Organizar y dar seguimiento a las tareas del proyecto mediante un tablero Kanban.

**Estructura del tablero sugerida**:

| Columna | Descripción |
|---------|-------------|
| **Backlog** | Tareas identificadas pendientes de priorizar |
| **Por Hacer** | Tareas priorizadas para el sprint actual |
| **En Progreso** | Tareas actualmente en desarrollo |
| **En Revisión** | Tareas completadas pendientes de revisión/pruebas |
| **Terminado** | Tareas finalizadas y validadas |

**Ejemplo de tarjetas**:
- ✅ Diseñar modelo de base de datos
- ✅ Crear script SQL de tablas
- 🔄 Desarrollar endpoint de login
- 📋 Implementar formulario de recursos
- 📋 Integrar Chart.js para estadísticas

### 11.2.2 MockFlow / Miro - Wireframes y Flujos

**Propósito**: Diseñar los wireframes de las interfaces y diagramar flujos de navegación.

**Entregables sugeridos**:
- Wireframe de pantalla de Login
- Wireframe de pantalla de Signup
- Wireframe del Dashboard completo
- Wireframe del Catálogo Público
- Diagrama de flujo de navegación entre pantallas
- Diagrama de flujo del proceso de descarga

### 11.2.3 StarUML / Visual Paradigm - Diagramas UML

**Propósito**: Elaborar diagramas UML formales para documentación técnica.

**Diagramas a elaborar**:

| Diagrama | Descripción |
|----------|-------------|
| Diagrama de Casos de Uso | Representación gráfica de actores y casos de uso |
| Diagrama de Clases | Estructura de clases del backend (opcional) |
| Diagrama de Secuencia | Interacción entre componentes para flujos clave (login, descarga) |
| Diagrama de Componentes | Arquitectura de módulos del sistema |
| Diagrama de Despliegue | Distribución física de componentes |
| Diagrama Entidad-Relación | Modelo de datos de la base de datos |

### 11.2.4 Git / GitHub - Control de Versiones

**Propósito**: Gestionar el versionamiento del código fuente y facilitar colaboración.

**Buenas prácticas**:
- Commits frecuentes con mensajes descriptivos
- Ramas por funcionalidad (feature branches)
- Pull requests para integración de cambios
- README.md con instrucciones de instalación

---

# 12. ESTRUCTURA PROPUESTA DEL REPORTE TÉCNICO FINAL

A continuación se presenta la estructura sugerida para el documento de reporte técnico final que debe entregarse como parte de la evaluación del proyecto.

## 12.1 Portada

```
┌─────────────────────────────────────────────────────────────┐
│                                                             │
│                    [LOGO INSTITUCIÓN]                       │
│                                                             │
│            UNIVERSIDAD / INSTITUTO TECNOLÓGICO              │
│                                                             │
│               FACULTAD / DEPARTAMENTO DE                    │
│            INGENIERÍA EN SISTEMAS / COMPUTACIÓN             │
│                                                             │
│  ───────────────────────────────────────────────────────── │
│                                                             │
│                    REPORTE TÉCNICO                          │
│                                                             │
│         "Dashboard para la Gestión de Recursos              │
│                Digitales para Programadores"                │
│                                                             │
│  ───────────────────────────────────────────────────────── │
│                                                             │
│                    MATERIA:                                 │
│               Tecnologías Web / Desarrollo Web              │
│                                                             │
│                    DOCENTE:                                 │
│               [Nombre del Profesor]                         │
│                                                             │
│                    INTEGRANTES:                             │
│               [Nombre 1] - [Matrícula]                      │
│               [Nombre 2] - [Matrícula]                      │
│               [Nombre 3] - [Matrícula]                      │
│                                                             │
│                    FECHA:                                   │
│               [Mes] de [Año]                                │
│                                                             │
└─────────────────────────────────────────────────────────────┘
```

## 12.2 Índice de Contenidos

```
ÍNDICE

1. Introducción .................................................. 1
   1.1 Contexto del proyecto ..................................... 1
   1.2 Problema o necesidad ...................................... 1
   1.3 Justificación ............................................. 2
   1.4 Objetivos ................................................. 3

2. Análisis del Sistema .......................................... 4
   2.1 Alcance del sistema ....................................... 4
   2.2 Actores del sistema ....................................... 5
   2.3 Requisitos funcionales .................................... 6
   2.4 Requisitos no funcionales ................................. 9
   2.5 Casos de uso .............................................. 11

3. Diseño del Sistema ............................................ 16
   3.1 Arquitectura de software .................................. 16
   3.2 Modelo de datos ........................................... 18
   3.3 Diseño de API REST ........................................ 21
   3.4 Diseño de interfaces (wireframes) ......................... 24

4. Desarrollo e Implementación ................................... 28
   4.1 Tecnologías utilizadas .................................... 28
   4.2 Estructura del proyecto ................................... 29
   4.3 Implementación del backend (API REST) ..................... 30
   4.4 Implementación del frontend ............................... 32
   4.5 Implementación de estadísticas (Chart.js) ................. 34
   4.6 Comunicación AJAX ......................................... 35

5. Resultados y Pruebas .......................................... 37
   5.1 Capturas de pantalla ...................................... 37
   5.2 Pruebas realizadas ........................................ 42
   5.3 Demostración de funcionalidades ........................... 43

6. Conclusiones .................................................. 45
   6.1 Logros alcanzados ......................................... 45
   6.2 Limitaciones .............................................. 45
   6.3 Trabajo futuro ............................................ 46

7. Referencias Bibliográficas .................................... 47

Anexos ........................................................... 48
   A. Script SQL de base de datos ................................ 48
   B. Manual de instalación ...................................... 50
   C. Diagramas UML adicionales .................................. 52
```

## 12.3 Contenido por Sección

### Sección 1: Introducción (2-3 páginas)
- Presentación del proyecto y su contexto académico
- Descripción del problema que resuelve
- Justificación técnica y práctica
- Objetivo general y objetivos específicos

### Sección 2: Análisis del Sistema (10-12 páginas)
- Definición del alcance (qué incluye y qué no)
- Descripción de actores
- Lista completa de requisitos funcionales (tabla RF)
- Lista de requisitos no funcionales (tabla RNF)
- Especificación de casos de uso principales (con flujos)
- Diagrama de casos de uso (imagen)

### Sección 3: Diseño del Sistema (10-12 páginas)
- Diagrama de arquitectura
- Descripción de capas (presentación, lógica, datos)
- Modelo entidad-relación (diagrama)
- Diccionario de datos (tablas)
- Especificación de endpoints del API REST
- Wireframes de interfaces principales

### Sección 4: Desarrollo e Implementación (8-10 páginas)
- Tabla de tecnologías utilizadas con versiones
- Estructura de directorios del proyecto
- Fragmentos de código relevantes (no todo el código)
- Explicación de la comunicación AJAX
- Implementación de Chart.js para gráficas

### Sección 5: Resultados y Pruebas (6-8 páginas)
- Capturas de pantalla de todas las interfaces
  - Login
  - Signup
  - Dashboard (listado, formulario, estadísticas)
  - Catálogo público
  - Gráficas de Chart.js
- Descripción de pruebas funcionales realizadas
- Evidencia de funcionamiento

### Sección 6: Conclusiones (1-2 páginas)
- Resumen de objetivos cumplidos
- Lecciones aprendidas
- Limitaciones encontradas
- Propuestas de mejora futura

### Sección 7: Referencias Bibliográficas
Formato sugerido: APA o IEEE

**Ejemplo de referencias**:
```
- Mozilla Developer Network. (2024). HTML5 Reference. 
  https://developer.mozilla.org/es/docs/Web/HTML

- jQuery Foundation. (2024). jQuery API Documentation. 
  https://api.jquery.com/

- Chart.js. (2024). Chart.js Documentation. 
  https://www.chartjs.org/docs/

- Bootstrap. (2024). Bootstrap 5 Documentation. 
  https://getbootstrap.com/docs/5.0/

- PHP Group. (2024). PHP Manual. 
  https://www.php.net/manual/es/

- Oracle Corporation. (2024). MySQL 8.0 Reference Manual. 
  https://dev.mysql.com/doc/refman/8.0/en/

- Fielding, R. T. (2000). Architectural Styles and the Design 
  of Network-based Software Architectures. Doctoral dissertation.
```

### Anexos
- Script SQL completo de creación de base de datos
- Instrucciones de instalación (README)
- Diagramas UML adicionales
- Código fuente relevante adicional

---

# CONCLUSIÓN DEL DOCUMENTO

Este documento de Ingeniería de Software proporciona una base sólida para el desarrollo del proyecto "Dashboard para la Gestión de Recursos Digitales". Incluye:

1. ✅ Definición clara del proyecto y sus objetivos
2. ✅ Alcance delimitado con inclusiones y exclusiones
3. ✅ Identificación de actores y sus responsabilidades
4. ✅ Requisitos funcionales y no funcionales detallados
5. ✅ Casos de uso con especificaciones completas
6. ✅ Modelo de datos con scripts SQL
7. ✅ Arquitectura de software y flujos de comunicación
8. ✅ Diseño de interfaces con wireframes
9. ✅ Guía de herramientas de gestión
10. ✅ Estructura del reporte técnico final

El siguiente paso es proceder con la implementación siguiendo este diseño, utilizando las herramientas de gestión propuestas y documentando el progreso para el reporte final.

---

**Fin del Documento de Ingeniería de Software**

