# 🍽️ Proyecto Restaurante

Aplicación web en **PHP** para la gestión integral de pedidos de un restaurante mediante tres roles de usuario: **cliente**, **camarero** y **encargado**. El sistema cubre todo el flujo de trabajo: gestión de mesas, carta dinámica según stock, pedidos por rondas, cobros, generación de facturas en PDF, impresión de tickets (comanda de cocina y cuenta) e informes de rendimiento.

El desarrollo se organizó con un enfoque **ágil (Scrum)** en **5 sprints**, todos completados. ✅

---

## 🎭 Demo

Hay una demo pública en **[alvaromartinez.dev/restaurante](https://alvaromartinez.dev/restaurante)**, desplegada automáticamente desde este repo (ver [🚀 Despliegue](#-despliegue)).

Es la misma app, con los datos de `bd/restaurante_08_con_datos.sql`, y algunas diferencias solo activas en ese entorno (controladas por `DEMO_MODE`, ver `components/demo.php`):

- **La base de datos se reinicia entera cada día a las 00:00h (hora de España)** — todo lo que se cree o modifique durante el día desaparece esa noche.
- Las contraseñas de los usuarios de prueba **no se pueden cambiar** (el resto de campos del perfil sí se guardan).
- Máximo **10 productos nuevos**, **4 cuentas nuevas** y **5 categorías nuevas** por ciclo de 24h — al llegar al límite se avisa y no se crea nada más hasta el siguiente reinicio.
- El encargado sí puede bloquear/desbloquear personal con normalidad — si alguien bloquea la cuenta de prueba del camarero, basta con volver a entrar como encargado (`1`/`1`) para desbloquearla, y el reinicio diario lo arregla igualmente.

### 🔑 Credenciales de prueba de la demo

| Rol | DNI | Contraseña |
|-----|-----|------------|
| Encargado | `1` | `1` |
| Camarero | `2` | `2` |
| Cliente | `3`, `4` o `5` | igual al DNI |

(Las mismas credenciales aparecen directamente en la pantalla de login de la demo.)

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
| Frontend | HTML5, CSS3, Bootstrap, [Tailwind CSS](https://tailwindcss.com) v4 (solo tablas y pastillas de estado) |
| Dependencias | Composer, npm (solo build de CSS) |
| PDFs | [`mpdf/mpdf`](https://github.com/mpdf/mpdf) ^8.2 |
| Impresión de tickets | [`mike42/escpos-php`](https://github.com/mike42/escpos-php) ^4.0 (ESC/POS por red) |
| Entorno de desarrollo | XAMPP / Docker |
| Despliegue | GitHub Actions (FTPS a cPanel) |

---

## 🎨 Tailwind CSS

Las tablas y las pastillas de estado (`.pill`) de toda la app usan Tailwind CSS, con el prefijo `tw:` en cada clase para no chocar con Bootstrap (ambos frameworks comparten nombres de clase — `.collapse`, `.container`, `.gap-3`… — con significados distintos). Se compila una sola vez a un CSS estático (`tailwind/tailwind.css`); no hay build en tiempo de request.

```bash
npm install          # una vez
npm run build:css    # tras tocar clases tw: en el PHP, o el archivo tailwind/input.css
npm run watch:css     # opcional, recompila en cada cambio mientras desarrollas
```

---

## 🚀 Despliegue

La demo se despliega sola en cada push a `main` vía `.github/workflows/deploy.yml`: compila el CSS de Tailwind, genera `components/conexion.local.php` a partir de secrets de GitHub y sube el proyecto por FTPS a cPanel con [`SamKirkland/FTP-Deploy-Action`](https://github.com/SamKirkland/FTP-Deploy-Action).

### Secrets del repo (Settings → Secrets and variables → Actions)

| Secret | Para qué |
|---|---|
| `FTP_HOST`, `FTP_USERNAME`, `FTP_PASSWORD` | Cuenta FTP de cPanel |
| `DEMO_DB_HOST` | Host de la BBDD (normalmente `localhost` en cPanel) |
| `DEMO_DB_USER`, `DEMO_DB_PASS`, `DEMO_DB_NAME` | BBDD de MySQL creada en cPanel para la demo |
| `DEMO_RESET_TOKEN` | Token para poder forzar un reinicio manual desde el navegador (ver más abajo) |

`components/conexion.local.php` (generado por el workflow, nunca commiteado) hace de puente entre esos secrets y `components/conexion.php`; el formato exacto está documentado en `components/conexion.local.php.example`.

### Preparación única en cPanel (no se puede automatizar sin SSH)

1. **MySQL® Databases**: crear la base de datos y un usuario con todos los privilegios sobre ella. Esos datos van en los secrets `DEMO_DB_*`.
2. **Cuenta FTP**: se usa la cuenta principal de cPanel (usuario `alvaroma`), cuya raíz por FTP es `/home/alvaroma/`. `alvaromartinez.dev` es el dominio principal de la cuenta (document root = `public_html/` directamente), así que la app vive en `/home/alvaroma/public_html/restaurante`; `server-dir` en el workflow apunta a la ruta relativa: `/public_html/restaurante/`.
3. **Cron Jobs**: nueva tarea programada para reiniciar la demo cada noche:
   - Minuto `0`, hora `0` (revisa antes la hora que usa el servidor en el propio cPanel; si no está en horario de España, ajusta la hora del cron en consecuencia).
   - Comando: `/usr/local/bin/php /home/alvaroma/public_html/restaurante/demo/reset_demo.php >> /home/alvaroma/public_html/restaurante/demo/reset.log 2>&1` — importante usar la **ruta absoluta** al binario de PHP (`php` a secas no funciona: cron usa un `PATH` mínimo donde no está). Si esa ruta no existiera en tu hosting, usa la específica de la versión de PHP asignada al dominio (mirar en MultiPHP Manager), con el formato `/usr/local/bin/ea-phpXX ...` que indica la propia página de Cron Jobs de cPanel.
   - `demo/reset_demo.php` solo se ejecuta por CLI o con el token correcto (nunca sin él), y solo si `DEMO_MODE` está activo, así que no hay riesgo de que alguien lo dispare desde fuera ni de que borre una instalación que no sea la demo.
   - **Reinicio manual sin consola**: visita `https://alvaromartinez.dev/restaurante/demo/reset_demo.php?token=TU_DEMO_RESET_TOKEN` (el mismo valor que pusiste en el secret) — resetea al momento. Puedes guardarlo en favoritos. Alternativa sin tocar nada de esto: reimportar `bd/demo_reset.sql` a mano desde phpMyAdmin (Importar → elegir archivo → Go), igual que la primera carga de datos.
4. **Dependencias de Composer (`vendor/`)**: excluida del deploy automático (tarda mucho por FTP y apenas cambia). Se sube **una vez a mano** por FTP a `/public_html/restaurante/vendor/` — a partir de ahí el workflow no la toca ni la borra en los siguientes deploys.
5. **Primera carga de datos**: tras el primer deploy, la BBDD está vacía hasta el primer reinicio programado. O se espera a la primera ejecución del cron, o se importa `bd/restaurante_08_con_datos.sql` una vez a mano desde phpMyAdmin para no esperar.

---

## 📁 Estructura del proyecto

```
restaurante/
├── index.php            # Pantalla de inicio y login
├── registro.php         # Registro de nuevos clientes
├── styles.css           # Estilos globales
├── components/          # Conexión a BD, header y navbar comunes
│   ├── conexion.php     # Configuración de la conexión MySQL
│   ├── demo.php         # Config y helpers del modo demo (DEMO_MODE, límites)
│   └── demoModal.php    # Modal compartida para acciones bloqueadas en demo
├── cliente/             # Vistas y lógica del rol cliente
├── camarero/            # Vistas y lógica del rol camarero
├── encargado/           # Vistas y lógica del rol encargado
├── bd/                  # Scripts SQL (versiones incrementales)
│   ├── restaurante_08_con_datos.sql   # ⭐ Versión final, con datos de prueba
│   └── demo_reset.sql   # Misma semilla, sin DROP/CREATE DATABASE (reinicio diario)
├── demo/
│   └── reset_demo.php   # Reinicio diario de la BBDD de demo (solo CLI, vía cron)
├── .github/workflows/
│   └── deploy.yml       # Despliegue automático a cPanel por FTPS
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

2. **Instala las dependencias** (solo necesario si no existen `vendor/` o `tailwind/tailwind.css`):

   ```bash
   cd restaurante
   composer install
   npm install && npm run build:css
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
