<?php
session_start();

require_once '../vendor/autoload.php';

use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;

include("../components/conexion.php");
include("seguridad.php");

$idPedido = $_GET['idPedido'];

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

// Opcional: datos del cliente (si quieres usarlos en el ticket)
$consultaCliente = mysqli_query($conn, "SELECT * FROM usuario WHERE dni = '$dniCliente'");
$cliente = mysqli_fetch_array($consultaCliente);
$nombreCliente   = $cliente['nombre'];
$apellidoCliente = $cliente['apellido'];

// =========================
// 2. OBTENER PRODUCTOS DEL PEDIDO
// =========================

$consultaLineas = mysqli_query($conn, "SELECT * FROM producto_pedido WHERE idPedido = '$idPedido'");

$total = 0;

$lineasTicket = []; // Para ir guardando las líneas

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

// =========================
// 3. CONFIGURAR IMPRESORA
// =========================

try {

    // Configurar impresora - Usar conexión de red
    $ipImpresora = "192.168.36.170";  // Cambiar a la IP de tu impresora
    $puertoImpresora = 9100;         // Puerto por defecto para impresoras ESC/POS
    $connector = new NetworkPrintConnector($ipImpresora, $puertoImpresora);
    $printer = new Printer($connector);

    // Número de factura (por ejemplo, idPedido + fecha)
    $num_factura = "FP-" . str_pad($idPedido, 4, "0", STR_PAD_LEFT);

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

    // Pino ASCII 🌲
    $printer->text("        *        \n");
    $printer->text("       ***       \n");
    $printer->text("      *****      \n");
    $printer->text("     *******     \n");
    $printer->text("    *********    \n");
    $printer->text("       ***       \n");
    $printer->text("       ***       \n");
    $printer->text("\n");

    $printer->text(str_repeat("-", 32) . "\n");

    // =========================
    // DATOS FACTURA / PEDIDO
    // =========================
    $printer->setJustification(Printer::JUSTIFY_LEFT);
    $printer->text("Factura: " . $num_factura . "\n");
    $printer->text("Mesa: " . $numMesa . "\n");
    $printer->text("Fecha: " . $fechaPedido . "\n");
    $printer->text("Cliente: " . $nombreCliente . " " . $apellidoCliente . "\n");
    $printer->text(str_repeat("-", 32) . "\n");

    // =========================
    // CABECERA PRODUCTOS
    // =========================
    $printer->text(str_repeat("=", 32) . "\n");
    $printer->text(sprintf("%-16s %3s %10s\n", "PRODUCTO", "UDS", "IMPORTE"));
    $printer->text(str_repeat("=", 32) . "\n");

    // =========================
    // LÍNEAS DE PRODUCTOS
    // =========================
    foreach ($lineasTicket as $linea) {
        $nombre   = mb_substr($linea['nombre'], 0, 16); // recortar si es largo
        $cantidad = $linea['cantidad'];
        $importe  = $linea['subtotal'];

        $printer->text(sprintf("%-16s %3d %10.2f\n", $nombre, $cantidad, $importe));
    }

    // =========================
    // TOTALES (con IVA si quieres)
    // =========================
    $ivaTipo = 0.21; // 21%
    $base_imponible = $total / (1 + $ivaTipo);
    $cuota_iva      = $total - $base_imponible;

    $printer->text(str_repeat("-", 32) . "\n");
    $printer->setJustification(Printer::JUSTIFY_RIGHT);
    $printer->text(sprintf("Base Imponible: %8.2f EUR\n", $base_imponible));
    $printer->text(sprintf("IVA (21%%): %12.2f EUR\n", $cuota_iva));
    $printer->text(str_repeat("=", 32) . "\n");
    $printer->setEmphasis(true);
    $printer->text(sprintf("TOTAL: %15.2f EUR\n", $total));
    $printer->setEmphasis(false);

    // =========================
    // PIE DEL TICKET
    // =========================
    $printer->setJustification(Printer::JUSTIFY_CENTER);
    $printer->text("\n");
    $printer->text("¡Gracias por su visita!\n");
    $printer->text("El Quinto Pino\n");
    $printer->text("\n");
    $printer->text("Conserve este ticket\n");
    $printer->text("para cualquier reclamación.\n");
    $printer->text("\n\n");

    // Cortar y cerrar
    $printer->cut();
    $printer->close();

    // Puedes redirigir de vuelta si quieres (pero al ser un script
    // que solo imprime, puedes dejarlo en blanco o mostrar un mensaje simple)
    echo "Ticket enviado a la impresora.";
} catch (Exception $e) {
    echo "Error al imprimir ticket: " . $e->getMessage();
}

// Cerrar conexión BD por si acaso
mysqli_close($conn);
