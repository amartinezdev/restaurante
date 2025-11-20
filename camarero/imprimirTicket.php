<?php
session_start();

require_once '../vendor/autoload.php';

use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;

include("../components/conexion.php");
include("seguridad.php");

// quita las tildes en el ticket, evita errores de quitar espacios
function quitaTildes($texto)
{
    $originales = array(
        "á",
        "é",
        "í",
        "ó",
        "ú"
    );

    $reemplazos = array(
        "a",
        "e",
        "i",
        "o",
        "u"
    );

    return str_replace($originales, $reemplazos, $texto);
}

$idPedido = $_GET['idPedido'];

// datos del pedido
$consultaPedido = mysqli_query($conn, "SELECT * FROM pedido WHERE id = '$idPedido'");
$pedido = mysqli_fetch_array($consultaPedido);
$fechaPedido = $pedido["fecha"];
$numMesa = $pedido["numMesa"];
$dniCliente = $pedido["usuario"];

//datos del usuario
$consultaCliente = mysqli_query($conn, "SELECT * FROM usuario WHERE dni = '$dniCliente'");
$cliente = mysqli_fetch_array($consultaCliente);
$nombreCliente = $cliente["nombre"];
$apellidoCliente = $cliente["apellido"];


// productos del pedido
$consultaLineas = mysqli_query($conn, "SELECT * FROM producto_pedido WHERE idPedido = '$idPedido'");

$total = 0;

try {
    // Configurar impresora - Usar conexión de red
    $ipImpresora = "192.168.36.170"; // Cambia a la IP de tu impresora
    $puertoImpresora = 9100;             // Puerto por defecto ESC/POS
    $connector = new NetworkPrintConnector($ipImpresora, $puertoImpresora);
    $printer = new Printer($connector);

    // Numero de factura
    $num_factura = str_pad($idPedido, 4, "0", STR_PAD_LEFT);

    // cabecera
    $printer->setJustification(Printer::JUSTIFY_CENTER);
    $printer->selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
    $printer->text("EL QUINTO PINO\n");
    $printer->selectPrintMode();
    $printer->text("C/ Falsa 123, Murcia\n");
    $printer->text("Tel: 666 666 666\n");
    $printer->text("CIF: B-12345678\n");
    $printer->text("\n");

    $printer->text(str_repeat("-", 39) . "\n");

    // datos del pedido impresos
    $printer->setJustification(Printer::JUSTIFY_LEFT);
    $printer->text("Factura: " . $num_factura . "\n");
    $printer->text("Mesa: " . $numMesa . "\n");
    $printer->text("Fecha: " . $fechaPedido . "\n");
    $printer->text("Cliente: " . $nombreCliente . " " . $apellidoCliente . "\n");
    $printer->text(str_repeat("-", 39) . "\n");

    // cabecera de la tabla
    $printer->setJustification(Printer::JUSTIFY_LEFT);
    $printer->text("PRODUCTO              UDS      IMPORTE\n");
    $printer->text(str_repeat("=", 39) . "\n");

    // lineas
    while ($linea = mysqli_fetch_array($consultaLineas)) {
        $idProducto = $linea["idProducto"];
        $cantidad   = $linea["cant"];
        // saco el precio
        $precioConsulta = mysqli_query($conn, "SELECT * FROM producto WHERE id = '$idProducto'");
        $row = mysqli_fetch_array($precioConsulta);
        $precio = $row["precio"];
        $nombre = $row["nombre"];
        $nombre = quitaTildes($nombre);
        $precio = $precio * $cantidad;
        $precioFormateado = number_format($precio, 2);
        $total += $precioFormateado;

        // reservamos espacios; 20 pegados a la izquierda, 3 para la cantidad y 10 para el precio
        $printer->text(sprintf("%-20s %3s %10s EUR\n", $nombre, $cantidad, $precioFormateado));
    }

    // total
    $ivaTipo = 0.21; // 21%
    $base_imponible = $total / (1 + $ivaTipo);
    $cuota_iva = $total - $base_imponible;

    $printer->text(str_repeat("=", 39) . "\n");
    $printer->setJustification(Printer::JUSTIFY_LEFT);
    $printer->text("Base Imponible: " . number_format($base_imponible, 2) . " EUR\n");
    $printer->text("IVA (21%): " . number_format($cuota_iva, 2) . " EUR\n");
    $printer->text(str_repeat("-", 39) . "\n");
    $printer->setEmphasis(true);
    $printer->text("TOTAL: " . number_format($total, 2) . " EUR\n");
    $printer->setEmphasis(false);

    // pie del ticket
    $printer->setJustification(Printer::JUSTIFY_CENTER);
    $printer->text("\n");
    $printer->text("Gracias por su visita!\n");
    $printer->text("El Quinto Pino\n");
    $printer->text("\n");
    $printer->text("Conserve este ticket\n");
    $printer->text("para cualquier reclamacion.\n");
    $printer->text("\n\n");

    // Cortar y cerrar
    $printer->cut();
    $printer->close();
} catch (Exception $e) {
    // Cerramos la impresora si llego a crearse
    if (isset($printer)) {
        $printer->close();
    }

    echo "Error al imprimir ticket: " . $e->getMessage();
    exit;
}

mysqli_close($conn);

header("LOCATION: cobros.php");
exit;
