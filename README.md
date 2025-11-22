# 🍽️ Proyecto Restaurante

Aplicación web en PHP para la gestión de pedidos de un restaurante mediante distintos roles de usuario (**cliente, camarero y encargado**).  
El sistema permite gestionar mesas, carta, stock, pedidos por rondas, cobros, tickets e informes.

---

## Descripción general

Este proyecto implementa un flujo completo de trabajo en un restaurante:

- Los **clientes** pueden registrarse, iniciar sesión, seleccionar mesa, ver la carta según el stock disponible, hacer pedidos y añadir notas a los productos.
- Los **camareros** gestionan las mesas, marcan productos como servidos y cierran las cuentas.
- Los **encargados** administran el personal, el menú, el stock y los informes del restaurante.

El desarrollo se organiza siguiendo un enfoque **ágil** basado en **Scrum**, dividido en **5 Sprints**.

---

### 📅 Planificación de Sprints

| Sprint  | Fecha fin       | Objetivo principal                                      |
|--------:|-----------------|---------------------------------------------------------|
| Sprint 1 | 9 noviembre 2025  | Fundamentos del sistema y roles                         |
| Sprint 2 | 16 noviembre 2025 | Gestión de mesas y toma de pedidos                     |
| Sprint 3 | 23 noviembre 2025 | Generación de cuentas y cierre de mesas (camarero)     |
| Sprint 4 | 30 noviembre 2025 | Funciones administrativas y gestión avanzada del menú  |
| Sprint 5 | 5 diciembre 2025  | Pruebas finales, documentación y revisión de seguridad |

---

## Roadmap por Sprints

### Sprint 1 – Fundamentos del sistema y roles (09/11/2025)

**Objetivo**  
Construir la base del sistema: autenticación, roles de usuario y estructura inicial de la aplicación y la base de datos.

**Alcance funcional**

- ✅ Crear la estructura básica en **HTML5** con diseño **responsive** (Bootstrap).
- ✅ Crear la **base de datos** con sus tablas, relaciones y atributos necesarios.
- ✅ Crear la pantalla de **inicio** y sistema de **autenticación y registro**:
  - `index.php` permite el inicio de sesión.
  - `registro.php` permite el registro de nuevos clientes.
  - Una vez logueado, el sistema redirige al usuario según su **rol**.
- ✅ Implementar **medidas de seguridad** básicas y **cierre de sesión**.
- ✅ Crear sistema de:
  - Alta, baja y modificación de **categorías**.
  - Alta, baja y modificación de **productos** y su **stock** (rol encargado).

**Archivos clave**

- `index.php` – Pantalla de inicio y login.
- `registro.php` – Registro de clientes.
- `components/conexion.php` – Conexión a la base de datos.
- Carpeta `cliente/` – Vistas y lógica del cliente.
- Carpeta `camarero/` – Vistas y lógica del camarero.
- Carpeta `encargado/` – Vistas y lógica del encargado.
- Carpeta `bd/` – Scripts SQL (por ejemplo, `restaurante.sql`).

**Estado del Sprint 1**: ✅ Completado.

---

### Sprint 2 – Gestión de mesas y toma de pedidos (16/11/2025)

**Objetivo**  
Permitir a los clientes seleccionar mesa, realizar pedidos (con stock controlado y rondas) y facilitar al camarero la gestión de esos pedidos.

**Alcance funcional**

- ✅ **Selección de mesa y número de comensales** por parte del cliente.
- ✅ **Carta dinámica según stock**:
  - Si no hay stock de un producto, **no aparece** en la carta.
- ✅ **Control de stock en tiempo real**:
  - Al confirmar un pedido, se descuenta automáticamente del stock la cantidad pedida.
  - Se comprueba que haya stock suficiente antes de confirmar; en caso contrario, se muestra un mensaje de error/advertencia.
- ✅ **Múltiples rondas de pedido** (segunda, tercera, cuarta…):
  - El cliente puede añadir nuevos productos a un pedido ya iniciado.
  - Las nuevas rondas se **acumulan** al pedido anterior sin sobrescribirlo.
- ✅ **Notas por producto**:
  - Posibilidad de añadir notas como “sin azúcar”, “extra picante”, etc.
- ✅ **Buscador en la carta**:
  - Campo de búsqueda para filtrar productos por nombre o palabra clave.
- ✅ **Gestión de mesas por el camarero**:
  - Visualización de todas las mesas.
  - Cambio de estado de productos de *“pedido”* a *“servido”*.
- ✅ **Envío/cancelación de productos pendientes**:
  - Se pueden cancelar productos mientras no hayan sido enviados a cocina.
  - Una vez enviados, el pedido queda bloqueado para modificaciones.
- ✅ **Bloqueo de mesa**:
  - Aunque el cliente cierre sesión, no puede cambiar de mesa hasta que ésta esté **pagada y liberada**.

**Estado del Sprint 2**: ✅ Completado.

---

### Sprint 3 – Generación de cuentas y cierre de mesas (rol Camarero) (23/11/2025)

**Objetivo**  
Completar el flujo de pedidos hasta el pago y cierre de mesa.

**Funcionalidades planificadas**

- ✅ Generación automática de **cuentas en PDF** con el consumo total de la mesa.
    - El usuario puede generar la factura en `pedidos.php` una vez que el pedido esté pagado.
-  ✅Opción de marcar la mesa como **“pagada”**:
     - La mesa desaparece de la vista de mesas activas para el camarero.
- ✅ **Impresión de tickets** de la cuenta en la máquina de tickets.
  - El camarero puede imprimir el ticket de la mesa completa.
- ✅ Persistencia de los datos de cada mesa y sus cuentas:
  - Historial almacenado en la BD para auditorías futuras en la tabla `Pedidos`.

**Librerías previstas**

- `mpdf/mpdf` para generación de PDFs.
- `mike42/escpos-php` para impresión en impresoras de tickets (ESC/POS).

**Estado del Sprint 3**: ✅ Completado.

---

### Sprint 4 – Funciones administrativas y gestión del menú (rol Encargado) (30/11/2025)

**Objetivo**  
Dotar al encargado de herramientas avanzadas para gestionar pedidos, personal, menú e informes.

**Funcionalidades planificadas**

- ✅ Sistema de **envío de pedidos a cocina** con impresión automática en la máquina de tickets.
  - Cuando un usuario realiza un pedido, aparece un ticket de cocina.
- ✅ Gestión de **perfiles de camareros**:
  - Registro de nuevos camareros.
  - Suspensión/bloqueo de camareros.
- ✅ Sistema de **informes de rendimiento**:
  - Ingresos por periodo.
  - Número de comensales.
  - Mesas atendidas, etc.


**Estado del Sprint 4**: ✅ Completado.

---

### Sprint 5 – Pruebas finales y documentación (05/12/2025)

**Objetivo**  
Cerrar el proyecto con pruebas de usuario, documentación completa y revisión de seguridad.

**Tareas planificadas**

- **Pruebas de usuario** (internas):
  - Verificar la funcionalidad de cada rol.
  - Recoger feedback y aplicar mejoras.
- **Memoria del proyecto**:
  - Portada.
  - Índice.
  - Modelo de datos (diagrama y explicación).
  - Manuales de usuario (cliente, camarero, encargado).
  - Problemas encontrados y soluciones adoptadas.
  - Propuestas de mejora futura.
- **Revisión final de seguridad**:
  - Validación de roles y permisos.
  - Comprobación de accesos no autorizados.
  - Revisión de gestión de sesiones y datos sensibles.

**Estado del Sprint 5**: ✅ Completado.

---

## Tecnologías utilizadas

- **Backend**: PHP
- **Base de datos**: MySQL
- **Frontend**: HTML5, CSS3, Bootstrap
- **Gestión de dependencias**: Composer
- **Librerías utilizadas**:
  - `mpdf/mpdf` – generación de PDFs.
  - `mike42/escpos-php` – impresión de tickets.
- **Servidor**: XAMPP.

---

## Instalación y configuración

1. **Clona el proyecto**
   ```bash
   git clone git@github.com:amartinezdev/restaurante.git
2. **Importa la base de datos en XAMPP**
3. **⚠️ El proyecto dará fallo si no tienes en rango la IP de la impresora de tickets.**
    ```bash 
    192.168.36.170
4. Para evitar el fallo, cambia el `header(LOCATION:)` de `carta.php` a `pedidos.php`