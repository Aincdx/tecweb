# DOCUMENTO DE INGENIERÍA DE SOFTWARE
## Dashboard para la Gestión de Recursos Digitales
### (Archivos de Soporte para Programadores)

---

## 📋 ÍNDICE DEL DOCUMENTO

Este documento de ingeniería de software está dividido en 6 partes para facilitar su lectura y edición. A continuación se presenta el índice general con enlaces a cada sección.

---

### PARTE 1: Información General, Introducción y Objetivos
**Archivo:** `01_DOCUMENTO_INGENIERIA_SOFTWARE.md`

| Sección | Contenido |
|---------|-----------|
| 1 | Información General del Proyecto |
| 2 | Introducción |
| 3 | Objetivos (General y Específicos) |
| 4 | Alcance del Sistema |
| 5 | Actores del Sistema |

---

### PARTE 2: Requisitos del Sistema
**Archivo:** `02_REQUISITOS_SISTEMA.md`

| Sección | Contenido |
|---------|-----------|
| 6 | Requisitos del Sistema |
| 6.1 | Requisitos Funcionales (RF01-RF44) |
| 6.2 | Requisitos No Funcionales (RNF01-RNF30) |
| 6.3 | Matriz de Trazabilidad |

---

### PARTE 3: Casos de Uso
**Archivo:** `03_CASOS_DE_USO.md`

| Sección | Contenido |
|---------|-----------|
| 7 | Casos de Uso |
| 7.1 | Lista de Casos de Uso (CU01-CU15) |
| 7.2 | Diagrama General de Casos de Uso |
| 7.3 | Especificación Detallada (CU02, CU04, CU10, CU12) |
| 7.4 | Relaciones entre Casos de Uso |

---

### PARTE 4: Modelo de Datos
**Archivo:** `04_MODELO_DATOS.md`

| Sección | Contenido |
|---------|-----------|
| 8 | Modelo de Datos |
| 8.1 | Introducción |
| 8.2 | Diagrama Entidad-Relación |
| 8.3 | Descripción de Tablas |
| 8.4 | Tabla Opcional TIPOS_RECURSO |
| 8.5 | Consultas SQL para Estadísticas |
| 8.6 | Script Completo de Base de Datos |
| 8.7 | Diccionario de Datos |

---

### PARTE 5: Arquitectura de Software
**Archivo:** `05_ARQUITECTURA_SOFTWARE.md`

| Sección | Contenido |
|---------|-----------|
| 9 | Arquitectura de Software |
| 9.1 | Estilo Arquitectónico |
| 9.2 | Descripción de las Capas |
| 9.3 | Comunicación AJAX con API REST |
| 9.4 | Especificación de Endpoints |
| 9.5 | Flujos de Comunicación Detallados |
| 9.6 | Diagrama de Componentes |
| 9.7 | Diagrama de Despliegue |

---

### PARTE 6: Diseño de Interfaz, Gestión y Estructura del Reporte
**Archivo:** `06_DISENO_INTERFAZ_GESTION_REPORTE.md`

| Sección | Contenido |
|---------|-----------|
| 10 | Diseño de Interfaz (Maquetado) |
| 10.1 | Introducción al Diseño |
| 10.2 | Pantallas del Sistema (Login, Signup, Dashboard, Catálogo) |
| 10.3 | Paleta de Colores |
| 11 | Gestión del Proyecto y Herramientas |
| 11.1 | Metodología de Trabajo |
| 11.2 | Herramientas (Trello, MockFlow, StarUML, Git) |
| 12 | Estructura del Reporte Técnico Final |
| 12.1 | Portada |
| 12.2 | Índice de Contenidos |
| 12.3 | Contenido por Sección |

---

## 📁 ESTRUCTURA DE ARCHIVOS

```
proyecto_final/
└── documentacion/
    ├── 00_INDICE_DOCUMENTO.md              (Este archivo)
    ├── 01_DOCUMENTO_INGENIERIA_SOFTWARE.md (Secciones 1-5)
    ├── 02_REQUISITOS_SISTEMA.md            (Sección 6)
    ├── 03_CASOS_DE_USO.md                  (Sección 7)
    ├── 04_MODELO_DATOS.md                  (Sección 8)
    ├── 05_ARQUITECTURA_SOFTWARE.md         (Sección 9)
    └── 06_DISENO_INTERFAZ_GESTION_REPORTE.md (Secciones 10-12)
```

---

## 📊 RESUMEN ESTADÍSTICO DEL DOCUMENTO

| Elemento | Cantidad |
|----------|----------|
| Secciones principales | 12 |
| Requisitos funcionales | 44 |
| Requisitos no funcionales | 30 |
| Casos de uso identificados | 15 |
| Casos de uso detallados | 4 |
| Tablas de base de datos | 4 |
| Endpoints API REST | 15+ |
| Wireframes descritos | 5 |

---

## 🛠️ TECNOLOGÍAS DEL PROYECTO

### Frontend
- HTML5 / XHTML
- CSS3 / Bootstrap 5
- JavaScript (ES6+)
- jQuery 3.x
- Chart.js

### Backend
- PHP 7.4+
- Composer
- API REST (JSON)

### Base de Datos
- MySQL 5.7+ / MariaDB

### Herramientas de Desarrollo
- XAMPP (Apache + MySQL)
- Visual Studio Code
- Git / GitHub

### Herramientas de Documentación
- Trello (gestión de tareas)
- MockFlow / Miro (wireframes)
- StarUML / Visual Paradigm (diagramas UML)

---

## 📝 NOTAS PARA EL EQUIPO

1. **Para copiar a Word/LaTeX**: Cada archivo está formateado con Markdown estándar. Puede convertirse fácilmente usando Pandoc o copiando directamente.

2. **Placeholders**: Buscar y reemplazar los textos entre corchetes [ ] con la información real del equipo.

3. **Diagramas**: Los diagramas en ASCII/texto son representaciones conceptuales. Se deben elaborar versiones gráficas en las herramientas indicadas.

4. **Actualización**: Mantener el documento actualizado conforme avance el desarrollo.

---

**Última actualización:** Diciembre 2024

