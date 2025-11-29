<?php
// Reinicio de la BBDD de demo. Dos formas de lanzarlo:
//   1) CLI, vía Cron Jobs de cPanel (php demo/reset_demo.php) — la vía normal,
//      automática, todas las noches.
//   2) HTTP, visitando esta URL con ?token=... si coincide con
//      DEMO_RESET_TOKEN (definido en conexion.local.php) — pensado para que
//      el propio admin pueda forzar un reset manual sin acceso a consola,
//      solo con un enlace guardado en favoritos.
// Cualquier otro caso (HTTP sin token válido) responde 403 sin hacer nada.

require __DIR__ . '/../components/conexion.php';
require __DIR__ . '/../components/demo.php';

$esCli = php_sapi_name() === 'cli';
$tokenValido = defined('DEMO_RESET_TOKEN') && DEMO_RESET_TOKEN !== ''
    && isset($_GET['token']) && hash_equals(DEMO_RESET_TOKEN, $_GET['token']);

if (!$esCli && !$tokenValido) {
    http_response_code(403);
    exit('Forbidden');
}

if (!$esCli) {
    header('Content-Type: text/plain; charset=utf-8');
}

// Seguro adicional: este script borra y recrea tablas enteras. Que solo
// pueda correr contra una BBDD que el propio proyecto marca como demo evita
// ejecutarlo por error contra una instalación real.
if (!DEMO_MODE) {
    $mensaje = "DEMO_MODE no está activo, no se ejecuta el reinicio.\n";
    $esCli ? fwrite(STDERR, $mensaje) : print($mensaje);
    exit(1);
}

$sql = file_get_contents(__DIR__ . '/../bd/demo_reset.sql');

if ($sql === false) {
    $mensaje = "No se ha podido leer bd/demo_reset.sql\n";
    $esCli ? fwrite(STDERR, $mensaje) : print($mensaje);
    exit(1);
}

mysqli_multi_query($conn, $sql);

do {
    if ($result = mysqli_store_result($conn)) {
        mysqli_free_result($result);
    }
} while (mysqli_more_results($conn) && mysqli_next_result($conn));

if (mysqli_errno($conn)) {
    $mensaje = '[' . date('c') . '] Error al reiniciar la demo: ' . mysqli_error($conn) . "\n";
    $esCli ? fwrite(STDERR, $mensaje) : print($mensaje);
    exit(1);
}

echo '[' . date('c') . "] Base de datos de demo reiniciada correctamente.\n";
