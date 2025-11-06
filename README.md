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



---

📅 **Sprint 1** –– 9 – Noviembre 2025
