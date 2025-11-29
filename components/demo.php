<?php
// Modo demo: config y helpers.
// DEMO_MODE se define en conexion.local.php (solo existe en el despliegue de
// cPanel, generado por el workflow de GitHub Actions). En local/Docker ese
// archivo no existe, así que aquí cae al valor por defecto (false) y la app
// se comporta como una instalación normal, sin restricciones.
if (!defined('DEMO_MODE')) {
    define('DEMO_MODE', false);
}

// DNIs de los usuarios de la semilla (bd/restaurante_08_con_datos.sql) y el
// mayor id de producto de esa misma semilla. Sirven para calcular cuántas
// cuentas/productos se han creado desde el último reinicio sin necesidad de
// contadores propios: el reinicio diario de la BBDD ya los pone a 0 solo.
const DEMO_SEED_DNIS = ['1', '2', '3', '4', '5'];
const DEMO_SEED_PRODUCTO_MAX_ID = 30;
const DEMO_SEED_CATEGORIA_MAX_ID = 6;

const DEMO_MAX_PRODUCTOS_NUEVOS = 10;
const DEMO_MAX_CUENTAS_NUEVAS = 4;
const DEMO_MAX_CATEGORIAS_NUEVAS = 5;

// Marca en sesión el motivo por el que se ha bloqueado una acción; lo lee y
// lo limpia components/demoModal.php en la siguiente página que se cargue.
function demo_block(string $motivo): void
{
    $_SESSION['demo_block'] = $motivo;
}

function demo_productos_creados($conn): int
{
    $result = mysqli_query($conn, "SELECT COUNT(*) AS c FROM producto WHERE id > " . DEMO_SEED_PRODUCTO_MAX_ID);
    $row = mysqli_fetch_assoc($result);
    return (int) $row['c'];
}

function demo_cuentas_creadas($conn): int
{
    $seed = "'" . implode("','", DEMO_SEED_DNIS) . "'";
    $result = mysqli_query($conn, "SELECT COUNT(*) AS c FROM usuario WHERE dni NOT IN ($seed)");
    $row = mysqli_fetch_assoc($result);
    return (int) $row['c'];
}

function demo_categorias_creadas($conn): int
{
    $result = mysqli_query($conn, "SELECT COUNT(*) AS c FROM categoria WHERE id > " . DEMO_SEED_CATEGORIA_MAX_ID);
    $row = mysqli_fetch_assoc($result);
    return (int) $row['c'];
}
