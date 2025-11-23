<?php
session_start();

require_once '../vendor/autoload.php';

use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\DummyPrintConnector;

include("../components/conexion.php");
include("seguridad.php");

// Comprobar que recibimos idPedido
if (!isset($_GET['idPedido'])) {
    die("No se ha indicado ningún pedido.");
}

$idPedido = (int) $_GET['idPedido'];

// =========================
// 1. OBTENER DATOS DEL PEDIDO
// =========================

// pedido: id, usuario (dni), fecha, estado, numMesa
$consultaPedido = mysqli_query($conn, "SELECT * FROM pedido WHERE id = '$idPedido'");

if (!$consultaPedido || mysqli_num_rows($consultaPedido) == 0) {
    die("No se ha encontrado el pedido.");
}

$pedido = mysqli_fetch_array($consultaPedido);

$fechaPedido = date("d/m/Y H:i", strtotime($pedido['fecha']));
$numMesa     = $pedido['numMesa'];
$dniCliente  = $pedido['usuario'];

// =========================
// 2. OBTENER DATOS DEL CLIENTE
// =========================

$consultaCliente = mysqli_query($conn, "SELECT * FROM usuario WHERE dni = '$dniCliente'");
$cliente = mysqli_fetch_array($consultaCliente);

$nombreCliente   = $cliente['nombre'];
$apellidoCliente = $cliente['apellido'];

// =========================
// 3. OBTENER PRODUCTOS DEL PEDIDO
// =========================

$consultaLineas = mysqli_query($conn, "SELECT * FROM producto_pedido WHERE idPedido = '$idPedido'");

$total = 0;
$lineasTicket = [];

while ($linea = mysqli_fetch_array($consultaLineas)) {
    $idProducto = $linea['idProducto'];
    $cantidad   = $linea['cant'];

    $consultaProducto = mysqli_query($conn, "SELECT * FROM producto WHERE id = '$idProducto'");
    $producto = mysqli_fetch_array($consultaProducto);

    $nombreProducto = $producto['nombre'];
    $precioUnitario = (float)$producto['precio'];
    $subtotal       = $precioUnitario * $cantidad;
    $total         += $subtotal;

    $lineasTicket[] = [
        'nombre'   => $nombreProducto,
        'cantidad' => $cantidad,
        'subtotal' => $subtotal
    ];
}

mysqli_close($conn);

// =========================
// 4. CONFIGURAR DUMMY CONNECTOR (NO IMPRIME NADA DE VERDAD)
// =========================

$connector = new DummyPrintConnector();
$printer   = new Printer($connector);

// Número de factura
$num_factura = "FP-" . str_pad($idPedido, 4, "0", STR_PAD_LEFT);

// También montamos un PREVIEW legible
$previewTicket  = "";
$add = function ($text) use (&$previewTicket) {
    $previewTicket .= $text;
};

// =========================
// CABECERA
// =========================
$printer->setJustification(Printer::JUSTIFY_CENTER);
$printer->selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
$printer->text("EL QUINTO PINO\n");
$printer->selectPrintMode();
$printer->text("C/ Falsa 123, Murcia\n");
$printer->text("Tel: 666 666 666\n");
$printer->text("CIF: 12345678B\n");
$printer->text("\n");

// Preview
$add("EL QUINTO PINO\n");
$add("C/ Falsa 123, Murcia\n");
$add("Tel: 666 666 666\n");
$add("CIF: 12345678B\n\n");

// Pino ASCII 🌲
$pino = [
    "        *        \n",
    "       ***       \n",
    "      *****      \n",
    "     *******     \n",
    "    *********    \n",
    "       ***       \n",
    "       ***       \n",
    "\n"
];

foreach ($pino as $line) {
    $printer->text($line);
    $add($line);
}

$linea32 = str_repeat("-", 32) . "\n";
$printer->text($linea32);
$add($linea32);

// =========================
// DATOS FACTURA / PEDIDO
// =========================
$printer->setJustification(Printer::JUSTIFY_LEFT);
$printer->text("Factura: " . $num_factura . "\n");
$printer->text("Mesa: " . $numMesa . "\n");
$printer->text("Fecha: " . $fechaPedido . "\n");
$printer->text("Cliente: " . $nombreCliente . " " . $apellidoCliente . "\n");
$printer->text($linea32);

$add("Factura: " . $num_factura . "\n");
$add("Mesa: " . $numMesa . "\n");
$add("Fecha: " . $fechaPedido . "\n");
$add("Cliente: " . $nombreCliente . " " . $apellidoCliente . "\n");
$add($linea32);

// =========================
// CABECERA PRODUCTOS
// =========================
$printer->text(str_repeat("=", 32) . "\n");
$printer->text(sprintf("%-16s %3s %10s\n", "PRODUCTO", "UDS", "IMPORTE"));
$printer->text(str_repeat("=", 32) . "\n");

$add(str_repeat("=", 32) . "\n");
$add(sprintf("%-16s %3s %10s\n", "PRODUCTO", "UDS", "IMPORTE"));
$add(str_repeat("=", 32) . "\n");

// =========================
// LÍNEAS DE PRODUCTOS
// =========================
foreach ($lineasTicket as $linea) {
    $nombre   = mb_substr($linea['nombre'], 0, 16);
    $cantidad = $linea['cantidad'];
    $importe  = $linea['subtotal'];

    $lineaTexto = sprintf("%-16s %3d %10.2f\n", $nombre, $cantidad, $importe);

    $printer->text($lineaTexto);
    $add($lineaTexto);
}

// =========================
// TOTALES
// =========================
$ivaTipo = 0.21; // 21%
$base_imponible = $total / (1 + $ivaTipo);
$cuota_iva      = $total - $base_imponible;

$printer->text($linea32);
$printer->setJustification(Printer::JUSTIFY_RIGHT);
$printer->text(sprintf("Base Imponible: %8.2f EUR\n", $base_imponible));
$printer->text(sprintf("IVA (21%%): %12.2f EUR\n", $cuota_iva));
$printer->text(str_repeat("=", 32) . "\n");
$printer->setEmphasis(true);
$printer->text(sprintf("TOTAL: %15.2f EUR\n", $total));
$printer->setEmphasis(false);

$add($linea32);
$add(sprintf("Base Imponible: %8.2f EUR\n", $base_imponible));
$add(sprintf("IVA (21%%): %12.2f EUR\n", $cuota_iva));
$add(str_repeat("=", 32) . "\n");
$add(sprintf("TOTAL: %15.2f EUR\n", $total));

// =========================
// PIE
// =========================
$printer->setJustification(Printer::JUSTIFY_CENTER);
$printer->text("\n");
$printer->text("¡Gracias por su visita!\n");
$printer->text("El Quinto Pino\n");
$printer->text("\n");
$printer->text("Conserve este ticket\n");
$printer->text("para cualquier reclamación.\n");
$printer->text("\n\n");

$add("\n");
$add("¡Gracias por su visita!\n");
$add("El Quinto Pino\n\n");
$add("Conserve este ticket\n");
$add("para cualquier reclamación.\n\n");

$printer->cut();
// MUY IMPORTANTE: no llamamos a close() con Dummy en esta versión
// $printer->close();

// Datos crudos ESC/POS (pueden estar vacíos según versión)
$rawData = $connector->getData();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>El Quinto Pino - Vista Previa Ticket</title>
    <link rel="shortcut icon" href="../img/ico.png" type="image/x-icon">
</head>

<body>
    <h2>Salida simulada del ticket para el pedido #<?= htmlspecialchars($idPedido) ?></h2>

    <h3>Preview legible del ticket</h3>
    <pre><?= htmlspecialchars($previewTicket) ?></pre>

    <h3>Datos ESC/POS crudos (opcional)</h3>
    <pre><?= htmlspecialchars($rawData) ?></pre>

    <p><a href="cobros.php">Volver a cobros</a></p>
</body>

</html>