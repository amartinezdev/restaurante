# 🍽️ Proyecto Restaurante

Aplicación web en **PHP** para la gestión integral de pedidos de un restaurante mediante tres roles de usuario: **cliente**, **camarero** y **encargado**. El sistema cubre todo el flujo de trabajo: gestión de mesas, carta dinámica según stock, pedidos por rondas, cobros, generación de facturas en PDF, impresión de tickets (comanda de cocina y cuenta) e informes de rendimiento.

El desarrollo se organizó con un enfoque **ágil (Scrum)** en **5 sprints**, todos completados. ✅

---

## 📋 Funcionalidades por rol

### 👤 Cliente
- Registro e inicio de sesión.
- Selección de mesa y número de comensales.
- Carta dinámica: solo se muestran productos **con stock disponible**, con buscador por nombre.
- Pedidos por **rondas acumulativas** (se pueden añadir productos a un pedido ya iniciado).
- **Notas por producto** ("sin azúcar", "extra picante"…).
- Cancelación de productos mientras no se hayan enviado a cocina; una vez enviados, el pedido queda bloqueado.
- **Bloqueo de mesa**: aunque cierre sesión, el cliente no puede cambiar de mesa hasta que esté pagada y liberada.
- Descarga de la factura en **PDF** una vez pagado el pedido.

### 🤵 Camarero
- Visualización y gestión de todas las mesas activas.
- Cambio de estado de productos de *pedido* a *servido*.
- Cobro de mesas: al marcarlas como **pagadas**, desaparecen de la vista de mesas activas.
- **Impresión del ticket** de la cuenta completa en la impresora de tickets.
- Gestión de su propio perfil.

### 👔 Encargado
- Alta, baja, modificación y bloqueo de **categorías** y **productos** (con control de stock).
- Gestión del **personal**: registro, edición y suspensión/bloqueo de camareros.
- **Informes de rendimiento**: ingresos por periodo, número de comensales, mesas atendidas…
- Envío de pedidos a cocina con impresión automática de la comanda.

### ⚙️ Sistema
- Control de stock en tiempo real: al confirmar un pedido se descuenta el stock y se valida que haya cantidad suficiente.
- Redirección automática según el rol tras el login.
- Control de acceso por rol en cada sección (`seguridad.php`).
- Historial de pedidos persistido en BD para auditorías.

---

## 🛠️ Tecnologías

| Capa | Tecnología |
|------|------------|
| Backend | PHP (mysqli) |
| Base de datos | MySQL / MariaDB |
| Frontend | HTML5, CSS3, Bootstrap |
| Dependencias | Composer |
| PDFs | [`mpdf/mpdf`](https://github.com/mpdf/mpdf) ^8.2 |
| Impresión de tickets | [`mike42/escpos-php`](https://github.com/mike42/escpos-php) ^4.0 (ESC/POS por red) |
| Entorno de desarrollo | XAMPP |

---

## 📁 Estructura del proyecto

```
restaurante/
├── index.php            # Pantalla de inicio y login
├── registro.php         # Registro de nuevos clientes
├── styles.css           # Estilos globales
├── components/          # Conexión a BD, header y navbar comunes
│   └── conexion.php     # Configuración de la conexión MySQL
├── cliente/             # Vistas y lógica del rol cliente
├── camarero/            # Vistas y lógica del rol camarero
├── encargado/           # Vistas y lógica del rol encargado
├── bd/                  # Scripts SQL (versiones incrementales)
│   └── restaurante_08_con_datos.sql   # ⭐ Versión final, con datos de prueba
├── img/                 # Imágenes de la aplicación
├── img_productos/       # Imágenes de los productos de la carta
├── memoria/             # Documentación del proyecto (memoria, manuales)
└── vendor/              # Dependencias de Composer
```

---

## 🚀 Instalación y configuración

### Requisitos

- XAMPP (o equivalente: Apache + PHP + MySQL/MariaDB).
- Composer.

### Pasos

1. **Clona el proyecto** dentro de `htdocs` (XAMPP):

   ```bash
   cd C:/xampp/htdocs
   git clone git@github.com:amartinezdev/restaurante.git
   ```

2. **Instala las dependencias** (solo necesario si no existe la carpeta `vendor/`):

   ```bash
   cd restaurante
   composer install
   ```

3. **Importa la base de datos**: crea una BD llamada `restaurante` en phpMyAdmin e importa el script final:

   ```
   bd/restaurante_08_con_datos.sql
   ```

4. **Revisa la conexión a la BD** en [`components/conexion.php`](components/conexion.php) (por defecto: `localhost`, usuario `root`, sin contraseña, BD `restaurante`).

5. **Configura la impresora de tickets** ⚠️

   El proyecto imprime tickets por red en la IP `192.168.36.170`. Si no tienes una impresora ESC/POS accesible en esa IP, el flujo de pedidos fallará. Tienes dos opciones:

   - **Con impresora**: cambia la IP en estos dos archivos por la de tu impresora:
     - [`cliente/ticketCocina.php`](cliente/ticketCocina.php) (comanda de cocina)
     - [`camarero/imprimirTicket.php`](camarero/imprimirTicket.php) (ticket de cuenta)
   - **Sin impresora**: en [`cliente/carta.php`](cliente/carta.php), cambia la redirección `header("LOCATION: ticketCocina.php?idPedido=$idPedido")` por `header("LOCATION: pedidos.php")` para saltarte la impresión.

6. **Accede a la aplicación**: `http://localhost/restaurante/`

### 🔑 Usuarios de prueba

El script `restaurante_08_con_datos.sql` incluye usuarios ya creados (se accede con DNI y contraseña):

| Rol | DNI | Contraseña |
|-----|-----|------------|
| Encargado | `1` | `1` |
| Camarero | `2` | `2` |
| Cliente | `3` | `3` |

---

## 📅 Historial de sprints

| Sprint | Fecha fin | Objetivo | Estado |
|-------:|-----------|----------|:------:|
| 1 | 09/11/2025 | Fundamentos: autenticación, roles, BD y gestión de categorías/productos | ✅ |
| 2 | 16/11/2025 | Gestión de mesas, carta según stock, pedidos por rondas y notas | ✅ |
| 3 | 23/11/2025 | Cuentas en PDF, cobro y cierre de mesas, impresión de tickets | ✅ |
| 4 | 30/11/2025 | Comandas a cocina, gestión de personal e informes de rendimiento | ✅ |
| 5 | 05/12/2025 | Pruebas finales, memoria del proyecto y revisión de seguridad | ✅ |

---

## 📖 Documentación

En la carpeta [`memoria/`](memoria/) se encuentra la documentación completa del proyecto: memoria con el modelo de datos, manuales de usuario por rol, problemas encontrados y propuestas de mejora.

---

## ✍️ Autor

**Álvaro Martínez** — [@amartinezdev](https://github.com/amartinezdev)
