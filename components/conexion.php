<?php
// conexion
// En el despliegue de cPanel (sin variables de entorno propias como en
// Docker) conexion.local.php hace de puente: define las mismas DB_* vía
// putenv() antes de que se lean aquí abajo. Ese archivo lo genera el
// workflow de GitHub Actions a partir de secrets y nunca se commitea (ver
// .gitignore). Si no existe (Docker, XAMPP local), este include no hace nada
// y todo sigue como siempre.
if (file_exists(__DIR__ . '/conexion.local.php')) {
    require __DIR__ . '/conexion.local.php';
}

// BASE_PATH: prefijo de la URL bajo el que vive la app. En Docker/XAMPP la
// app se sirve en la raíz del host (BASE_PATH vacío); en cPanel vive bajo
// /restaurante, así que las rutas absolutas (header LOCATION, href, src)
// tienen que llevar este prefijo delante o apuntan al dominio equivocado.
// Se define vía APP_BASE_PATH (putenv en conexion.local.php, igual que las
// DB_*) para no tener el valor pegado en el código.
if (!defined('BASE_PATH')) {
    define('BASE_PATH', getenv('APP_BASE_PATH') ?: '');
}

// Las variables de entorno DB_* permiten sobreescribir estos valores al
// ejecutar el proyecto con Docker (ver docker-compose.yml) o en cPanel (ver
// conexion.local.php arriba). Si no existen, se usan los valores por defecto
// de un entorno local tipo XAMPP.
$servidor = getenv('DB_HOST') ?: "localhost";
$user = getenv('DB_USER') ?: "root";
$clave = getenv('DB_PASS') ?: "";
$basededatos = getenv('DB_NAME') ?: "restaurante";

// Desde PHP 8.1, mysqli lanza excepción en vez de devolver false en caso de
// error; aquí se prefiere el modo clásico (silencioso) para poder mostrar un
// mensaje claro en vez de un 500 en blanco sin pista de qué ha fallado.
mysqli_report(MYSQLI_REPORT_OFF);

// Establecimiento de la conexión al servidor localhost,
$conn = mysqli_connect($servidor, $user, $clave);

if (!$conn) {
    http_response_code(500);
    die('Error de conexión a la base de datos (' . htmlspecialchars($servidor) . '): ' . htmlspecialchars(mysqli_connect_error()));
}

// Seleccionamos la BBDD
if (!mysqli_select_db($conn, $basededatos)) {
    http_response_code(500);
    die('No se ha podido seleccionar la base de datos "' . htmlspecialchars($basededatos) . '": ' . htmlspecialchars(mysqli_error($conn)));
}

// Sin esto mysqli usa el charset por defecto del cliente (normalmente
// latin1) en vez del utf8 de las tablas, y las tildes/ñ salen mal aunque
// el HTML declare UTF-8.
mysqli_set_charset($conn, 'utf8mb4');

// Imprimimos si hay algún error
echo mysqli_error($conn);
