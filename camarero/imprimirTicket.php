<?php
session_start();

require_once '../vendor/autoload.php';

use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;

include("../components/conexion.php");
include("seguridad.php");

/**
 * Quita tildes y caracteres conflictivos.
 */
function quitar_tildes($texto)
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

// =========================
// DATOS DEL PEDIDO
// =========================
$consultaPedido = mysqli_query($conn, "SELECT * FROM pedido WHERE id = '$idPedido'");
$pedido = mysqli_fetch_array($consultaPedido);
$fechaPedido = $pedido["fecha"];
$numMesa     = $pedido["numMesa"];
$dniCliente  = $pedido["usuario"];

// =========================
// DATOS DEL CLIENTE
// =========================
$consultaCliente = mysqli_query($conn, "SELECT * FROM usuario WHERE dni = '$dniCliente'");
$cliente = mysqli_fetch_array($consultaCliente);


// =========================
// PRODUCTOS DEL PEDIDO
// =========================
$consultaLineas = mysqli_query($conn, "SELECT * FROM producto_pedido WHERE idPedido = '$idPedido'");

$total = 0;

try {
    // Configurar impresora - Usar conexión de red
    $ipImpresora     = "192.168.36.170"; // Cambia a la IP de tu impresora
    $puertoImpresora = 9100;             // Puerto por defecto ESC/POS
    $connector       = new NetworkPrintConnector($ipImpresora, $puertoImpresora);
    $printer         = new Printer($connector);

    // Numero de factura
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
    $printer->text("CIF: B-12345678\n");
    $printer->text("\n");

    $printer->text(str_repeat("-", 38) . "\n");

    // =========================
    // DATOS FACTURA / PEDIDO
    // =========================
    $printer->setJustification(Printer::JUSTIFY_LEFT);
    $printer->text("Factura: " . $num_factura . "\n");
    $printer->text("Mesa: " . $numMesa . "\n");
    $printer->text("Fecha: " . $fechaPedido . "\n");
    $printer->text("Cliente: " . $nombreCliente . " " . $apellidoCliente . "\n");
    $printer->text(str_repeat("-", 38) . "\n");

    // =========================
    // CABECERA PRODUCTOS
    // =========================
    $printer->setJustification(Printer::JUSTIFY_LEFT);
    $printer->text("PRODUCTO            UDS     IMPORTE\n");
    $printer->text(str_repeat("=", 38) . "\n");

    // Ya no usamos tablas de caracteres especiales ni bytes crudos

    // =========================
    // LINEAS DE PRODUCTOS
    // =========================
    while ($linea = mysqli_fetch_array($consultaLineas)) {
        $idProducto = $linea["idProducto"];
        $cantidad   = $linea["cant"];

        $consultaProducto = mysqli_query($conn, "SELECT * FROM producto WHERE id = '$idProducto'");
        $producto = mysqli_fetch_array($consultaProducto);

        // Limpiamos nombre de producto
        $nombreProducto = $producto['nombre'];
        $nombreProducto = quitar_tildes($nombreProducto);

        $precioUnitario = (float)$producto['precio'];
        $subtotal       = $precioUnitario * $cantidad;
        $total         += $subtotal;

        // recorto un poco el nombr
        $nombreCorto = $nombreProducto;
        if (strlen($nombreCorto) > 16) {
            $nombreCorto = substr($nombreCorto, 0, 16);
        }

        // nombre: 16 espacios
        $espaciosNombre = 16 - strlen($nombreCorto);
        if ($espaciosNombre < 0) {
            $espaciosNombre = 0;
        }
        $nombreFormateado = $nombreCorto . str_repeat(" ", $espaciosNombre);

        // cantidad: hasta 3 digitos
        $cantidadTexto    = (string)$cantidad;
        // $espaciosCantidad = 3 - strlen($cantidadTexto);
        // if ($espaciosCantidad < 0) {
        //     $espaciosCantidad = 0;
        // }
        if ($cantidadTexto > 10) {
            $cantidadFormateada = str_repeat(" ", 4) . $cantidadTexto . " ";
        } else {
            $cantidadFormateada = str_repeat(" ", 5) . $cantidadTexto . " ";
        }


        // importe
        $importeTexto = number_format($subtotal, 2); // ej: 12.50

        $espacioExtra = str_repeat(" ", 5); // cambia el 6 por el numero de espacios que quieras

        $lineaTexto =
            $nombreFormateado .
            $cantidadFormateada .
            $espacioExtra .
            $importeTexto .
            " EUR\n";

        // Enviamos la linea limpia a la impresora
        $printer->setJustification(Printer::JUSTIFY_LEFT);
        $printer->text($lineaTexto);
    }

    // =========================
    // TOTALES
    // =========================
    $ivaTipo        = 0.21; // 21%
    $base_imponible = $total / (1 + $ivaTipo);
    $cuota_iva      = $total - $base_imponible;

    $printer->text(str_repeat("=", 38) . "\n");
    $printer->setJustification(Printer::JUSTIFY_LEFT);
    $printer->text("Base Imponible: " . number_format($base_imponible, 2) . " EUR\n");
    $printer->text("IVA (21%): " . number_format($cuota_iva, 2) . " EUR\n");
    $printer->text(str_repeat("-", 38) . "\n");
    $printer->setEmphasis(true);
    $printer->text("TOTAL: " . number_format($total, 2) . " EUR\n");
    $printer->setEmphasis(false);

    // =========================
    // PIE DEL TICKET
    // =========================
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

header("Location: cobros.php");
exit;
