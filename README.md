# 🍽️ Proyecto Restaurante


## 🎯 Objetivo del Sprint 1

El objetivo de este sprint era **crear el sistema de autenticación y los roles principales (cliente, camarero y encargado)**, además de establecer la estructura inicial del proyecto y las bases de seguridad.

### Lo que se pedía en el Sprint 1

* **Estructura básica del sistema y diseño responsive** ✅

* **Base de datos funcional** ✅

* **Sistema de autenticación y registro** ✅
  * Desde `index.php` se puede iniciar sesión, y desde `registro.php` se pueden     registrar nuevos clientes. Una vez logueado, el sistema redirige al usuario según su rol.

* **Roles diferenciados** ✅

  * 👤 Cliente → `/cliente/`
  * 👨‍🍳 Camarero → `/camarero/`
  * 🧾 Encargado → `/encargado/`
  
  Cada uno tiene su propia zona, navegación y cierre de sesión.

* **Seguridad** ✅
  * Todos los archivos revisan la sesión y el rol. Si un usuario intenta acceder donde no debe, se le redirige al inicio.

* **Inicio del panel del encargado** ✅
  
  * He preparado la parte del encargado con estructura inicial para gestionar categorías y productos.




## Estructura del proyecto por ahora

```
restaurante/
├── index.php
├── registro.php
├── components/
│   ├── conexion.php
│   ├── header.php
│   └── navbar.php
├── cliente/
│   ├── cliente.php
│   ├── pedidos.php
│   ├── perfil.php
│   └── cerrarSesion.php
├── camarero/
│   ├── camarero.php
│   └── cerrarSesion.php
└── encargado/
    ├── encargado.php
    ├── addProductos.php
    ├── addProductos02.php
    ├── editarProducto.php
    ├── editarProducto02.php
    ├── bloquearProducto.php
    ├── desbloquearProducto.php
    ├── navbar.php
    └── cerrarSesion.php
```

```
restaurante/                             # carpeta raíz del proyecto
├── .gitattributes                       # configuración de Git
├── index.php                            # página principal de la aplicación
├── README.md                            # documentación del proyecto
├── registro.php                         # formulario de registro de nuevos usuarios
├── styles.css                           # hoja de estilos principal
├── bd/                                  # scripts SQL de la base de datos
│   ├── restaurante.sql                  # copia de respaldo
│   ├── restaurante_02.sql               
│   ├── restaurante_03.sql
|   └── restaurante_04.sql               # base de datos actual   
|
├── camarero/                            # módulo del rol camarero
│   ├── camarero.php                     # página principal del camarero
│   ├── cerrarSesion.php                 # cierre de sesión
│   ├── editarPerfil.php                 # edición de perfil
│   ├── navbar.php                       # barra de navegación
│   ├── pedidos.php                      # lista de pedidos
│   ├── perfil.php                       # página de perfil
│   ├── productoServido.php              # marcar productos como servidos
│   └── seguridad.php                    # control de acceso
|
├── cliente/                             # módulo del rol cliente
│   ├── addProductos.php                 # añadir productos al pedido
│   ├── carta.php                        # ver la carta del restaurante
│   ├── cerrarSesion.php                 # cierre de sesión
│   ├── editarPerfil.php                 # editar perfil del cliente
│   ├── eligeComensales.php              # selección de número de comensales
│   ├── mesa.php                         # asignación o selección de mesa
│   ├── navbar.php                       # barra de navegación del cliente
│   ├── pedidos.php                      # ver pedidos del cliente
│   ├── perfil.php                       # perfil del cliente
│   ├── reserva.php                      # realizar reserva
│   └── seguridad.php                    # control de acceso
|
├── components/                          # componentes comunes
│   ├── conexion.php                     # conexión a la base de datos
│   ├── header.php                       # cabecera HTML reutilizable
│   └── navbar.php                       # barra de navegación general
|
└── encargado/                           # módulo del rol encargado
    ├── addPersonal.php                  # añadir empleados
    ├── addProductos.php                 # añadir nuevos productos
    ├── addProductos02.php               # versión alternativa de añadir productos
    ├── bloquearPersonal.php             # bloquear empleados
    ├── bloquearProducto.php             # bloquear productos
    ├── categorias.php                   # gestión de categorías
    ├── cerrarSesion.php                 # cierre de sesión
    ├── desbloquearPersonal.php          # desbloquear empleados
    ├── desbloquearProducto.php          # desbloquear productos
    ├── editarCategoria.php              # editar categorías
    ├── editarPerfil.php                 # editar perfil del encargado
    ├── editarPersonal.php               # editar información del personal
    ├── editarPersonal02.php             # versión alternativa de edición de personal
    ├── editarProducto.php               # editar productos existentes
    ├── editarProducto02.php             # versión alternativa de edición de productos
    ├── encargado.php                    # página principal del encargado
    ├── navbar.php                       # barra de navegación del encargado
    ├── perfil.php                       # perfil del encargado
    └── seguridad.php                    # control de acceso
```

---

📅 **Sprint 1** –– 9 – Noviembre 2025
