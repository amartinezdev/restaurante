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

// Las variables de entorno DB_* permiten sobreescribir estos valores al
// ejecutar el proyecto con Docker (ver docker-compose.yml) o en cPanel (ver
// conexion.local.php arriba). Si no existen, se usan los valores por defecto
// de un entorno local tipo XAMPP.
$servidor = getenv('DB_HOST') ?: "localhost";
$user = getenv('DB_USER') ?: "root";
$clave = getenv('DB_PASS') ?: "";
$basededatos = getenv('DB_NAME') ?: "restaurante";

// Establecimiento de la conexión al servidor localhost,
$conn = mysqli_connect($servidor, $user, $clave);

// Seleccionamos la BBDD
mysqli_select_db($conn, $basededatos);

// Imprimimos si hay algún error
echo mysqli_error($conn);
