<?php
// Reinicio diario de la BBDD de demo. Pensado para lanzarse por Cron Jobs de
// cPanel (php demo/reset_demo.php), nunca por HTTP: si algún día alguien
// intenta pedir esta URL por el navegador, no hace nada.
if (php_sapi_name() !== 'cli') {
    http_response_code(403);
    exit('Forbidden');
}

require __DIR__ . '/../components/conexion.php';
require __DIR__ . '/../components/demo.php';

// Seguro adicional: este script borra y recrea tablas enteras. Que solo
// pueda correr contra una BBDD que el propio proyecto marca como demo evita
// ejecutarlo por error contra una instalación real.
if (!DEMO_MODE) {
    fwrite(STDERR, "DEMO_MODE no está activo, no se ejecuta el reinicio.\n");
    exit(1);
}

$sql = file_get_contents(__DIR__ . '/../bd/demo_reset.sql');

if ($sql === false) {
    fwrite(STDERR, "No se ha podido leer bd/demo_reset.sql\n");
    exit(1);
}

mysqli_multi_query($conn, $sql);

do {
    if ($result = mysqli_store_result($conn)) {
        mysqli_free_result($result);
    }
} while (mysqli_more_results($conn) && mysqli_next_result($conn));

if (mysqli_errno($conn)) {
    fwrite(STDERR, '[' . date('c') . '] Error al reiniciar la demo: ' . mysqli_error($conn) . "\n");
    exit(1);
}

echo '[' . date('c') . "] Base de datos de demo reiniciada correctamente.\n";
