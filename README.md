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




## Estructura del proyecto

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

## 🎯 Objetivo del Sprint 2  
**Gestión de Mesas y Toma de Pedidos**  
El objetivo de este sprint era implementar todo el sistema relacionado con la selección de mesa, gestión del número de comensales, toma de pedidos, control de stock en tiempo real y nuevas rondas de pedido. Además, se amplió la funcionalidad del camarero y se añadió un buscador dentro de la carta.

### Lo que se pedía en el Sprint 2  

**Selección de mesa y número de comensales** ✅  
Los clientes pueden seleccionar su mesa, indicar cuántos comensales son y comenzar su pedido, manteniendo la mesa bloqueada hasta que se pague.

**Carta dinámica según stock** ✅  
Si un producto no tiene stock, no aparece en la carta.

**Realización de pedidos con stock sincronizado** ✅  
Al confirmar un pedido:
- El stock se descuenta automáticamente según la cantidad pedida.  
- El sistema impide confirmar pedidos si no hay stock suficiente, mostrando un mensaje de advertencia.

**Nuevas rondas de pedido (segunda, tercera, cuarta…)** ✅  
Los clientes pueden seguir agregando productos a un pedido ya iniciado.  
Cada nueva ronda se suma a las anteriores sin sobrescribir nada.

**Notas en los productos** ✅  
El cliente puede añadir una nota por producto (por ejemplo, “sin azúcar”, “extra picante”, etc.).

**Buscador en la carta** ✅  
Incluye un campo de búsqueda que filtra productos por nombre o palabra clave dentro de las categorías.

**Gestión de mesas por parte del camarero** ✅  
El camarero puede visualizar todas las mesas y marcar los productos como *servidos*.

**Envío y cancelación de productos antes de cocina** ✅  
Los productos pueden cancelarse mientras estén pendientes.  
Una vez enviados a cocina dejan de ser modificables.

**Persistencia de mesa tras cierre de sesión** ✅  
Si el cliente cierra sesión y vuelve a entrar, no puede seleccionar otra mesa mientras la mesa actual siga pendiente de pago.

**Documentación del sprint** ✅ 
En la carpeta de memoria

📅 **Sprint 2 — 16 de noviembre de 2025**

## Estructura del proyecto

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


