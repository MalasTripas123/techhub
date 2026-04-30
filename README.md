# TechHub Store — Plataforma de E-Commerce

**Evaluación Sumativa 2 · Diseño y Programación de Aplicaciones para Internet · AIEP**

#### Descripción

TechHub Store es una aplicación web de e-commerce desarrollada en **PHP utilizando arquitectura MVC**. Permite a los usuarios visualizar productos tecnológicos, gestionar un carrito de compras y realizar búsquedas dinámicas sin recargar la página.

El sistema está diseñado para ejecutarse en un entorno local utilizando XAMPP y una base de datos MySQL.

---

#### Tecnologías utilizadas

* PHP
* MySQL
* Apache (XAMPP)
* JavaScript (AJAX)
* HTML5 / CSS3

---

#### Instalación y ejecución
1. Clonar el repositorio
`git clone <URL_DEL_REPOSITORIO>`

2. Mover el proyecto a
`C:\xampp\htdocs\`

3. Iniciar Apache y MySQL desde XAMPP

4. Desde phpMyAdmin, crear base de datos del proyecto
`Nombre: techhub`

5. Importar el archivo SQL que crea y popula las tablas.
`/database/techhub.sql`

6. Para ejecutar en el navegador debe entrar a su carpeta localhost raiz +
`/techhub/`

---

#### Arquitectura del sistema

El proyecto sigue el patrón MVC (Modelo - Vista - Controlador):

* **Modelos (`app/models`)**
Gestionan la lógica de negocio y acceso a base de datos

* **Controladores (`app/controllers`)**
Gestionan las acciones del sistema:

    * AuthController
    * ProductoController
    * CarritoController
    * AdminController

* **Vistas (`app/views`)**
Renderizan la interfaz de usuario:

    * Autenticación (login / register)
    * Catálogo de productos
    * Carrito
    * Panel de administración
    * Historial

* **Router (`index.php`)**
Maneja las rutas mediante los parámetros:
`?controller`, `?action` y `?categoria`

* **Esquema**
```
techhub/
├── index.php
├── .htaccess
├── config/
│   └── config.php
├── app/
│   ├── models/
│   │   ├── Database.php
│   │   ├── Usuario.php
│   │   ├── Producto.php
│   │   ├── Carrito.php
│   │   └── Orden.php
│   ├── controllers/
│   │   ├── AuthController.php
│   │   ├── ProductoController.php
│   │   ├── CarritoController.php
│   │   └── AdminController.php
│   └── views/
│       ├── layout/
│       │   ├── header.php
│       │   └── footer.php
│       ├── home.php
│       ├── login.php
│       ├── registro.php
│       ├── carrito.php
│       ├── historial.php
│       ├── detalle_producto.php
│       └── admin/
│           ├── dashboard.php
│           ├── productos.php
│           ├── producto_form.php
│           └── producto_editar.php
├── assets/
│   ├── css/style.css
│   ├── js/main.js
│   └── img/
└── database/
    └── techhub.sql
```

---

### Credenciales de prueba

| Rol | Email | Contraseña |
|-----|-------|-----------|
| Admin | admin@techhub.cl | password |
| Cliente | juan@email.com | password |
| Cliente | maria@email.com | password |

---

#### Funcionalidades principales
* Autenticación de usuarios (login y registro)
* Visualización de productos
* Búsqueda de productos
* Gestión de carrito de compras
* Administración de productos
* Visualización de historial de compras

---

#### Uso de AJAX

La búsqueda de productos se realiza mediante AJAX, permitiendo actualizar resultados sin recargar la página.

---

#### Flujo general del sistema

1. El usuario accede al sistema
2. Visualiza productos disponibles
3. Puede buscar productos
4. Puede agregar productos al carrito
5. Puede interactuar con funcionalidades de usuario

---

#### Flujo de búsqueda

1. El usuario ingresa texto en el buscador  
2. Se envía una solicitud con la información para el router 
3. El servidor procesa la solicitud  
4. Se devuelve contenido HTML  
5. Los resultados se muestran dinámicamente en la interfaz  

---

#### Interfaz de usuario

* Uso de Flexbox y Grid para la distribución  
* Diseño basado en tarjetas para productos  
* Visualización dinámica de resultados de búsqueda  
* Estructura con header, contenido principal y footer  

---

#### Base de datos

El proyecto incluye un script SQL que permite crear la estructura de la base de datos:  
`/database/techhub.sql`

**Este archivo es necesario para el funcionamiento del sistema.**

### Diagrama de base de datos

```
usuarios          productos
─────────         ─────────
id (PK)           id (PK)
nombre            nombre
email             descripcion
password          precio
rol               stock
created_at        categoria
                  imagen
                  activo
                  created_at

ordenes           detalles_orden        carritos
───────           ──────────────        ────────
id (PK)           id (PK)               id (PK)
usuario_id (FK)   orden_id (FK)         usuario_id (FK)
total             producto_id (FK)      producto_id (FK)
estado            cantidad              cantidad
created_at        precio_unitario       created_at
```

---

#### Notas
* El sistema está diseñado para ejecutarse en entorno local
* La navegación se basa en parámetros GET
* Proyecto académico desarrollado en el contexto de una evaluación.

---

#### Equipo

* Gaspar Vieira
* Renata Zambrano
* Gisselle Sepulveda

