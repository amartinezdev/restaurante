<?php
session_start();

require_once '../vendor/autoload.php';

use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;

include("../components/conexion.php");
include("seguridad.php");

// quita las tildes en el ticket
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
$pedido = mysqli_fetch_assoc($consultaPedido);
$fechaPedido = $pedido["fecha"];
$numMesa = $pedido["numMesa"];

try {
    // Configurar impresora - Usar conexión de red
    $ipImpresora = "192.168.36.170"; // IP de la impresora de cocina
    $puertoImpresora = 9100;             // Puerto por defecto ESC/POS
    $connector = new NetworkPrintConnector($ipImpresora, $puertoImpresora);
    $printer = new Printer($connector);

    // ===== CABECERA COCINA =====
    $printer->setJustification(Printer::JUSTIFY_CENTER);
    $printer->setEmphasis(true);
    $printer->text("COCINA\n");
    $printer->setEmphasis(false);
    $printer->text(str_repeat("-", 32) . "\n");

    // MESA EN GRANDE
    $printer->setJustification(Printer::JUSTIFY_CENTER);
    $printer->setTextSize(2, 2); // tamaño grande
    $printer->text("MESA " . $numMesa . "\n");
    $printer->setTextSize(1, 1);

    // NÚMERO DE PEDIDO (también grande pero un poco menos importante)
    $num_pedido = str_pad($idPedido, 4, "0", STR_PAD_LEFT);
    $printer->setEmphasis(true);
    $printer->text("PEDIDO #" . $num_pedido . "\n");
    $printer->setEmphasis(false);

    // Fecha/hora
    $printer->text($fechaPedido . "\n");
    $printer->text(str_repeat("=", 32) . "\n");

    // ===== LISTA DE PRODUCTOS =====
    $printer->setJustification(Printer::JUSTIFY_LEFT);
    $printer->text("CANT  PRODUCTO\n");
    $printer->text(str_repeat("-", 32) . "\n");

    $consultaLineas = mysqli_query(
        $conn,
        "SELECT *
        FROM producto_pedido
        WHERE idPedido = '$idPedido' AND servido = 0"
    );

    while ($linea = mysqli_fetch_assoc($consultaLineas)) {
        $cantidad = $linea["cant"];
        $nombre = quitaTildes($linea["nombre"]);
        $comentario = trim($linea["comentario"] ?? "");

        $consultaProd = mysqli_query(
            $conn,
            "SELECT nombre FROM producto WHERE id = '$idProducto'"
        );

        // línea principal: " 2x  Croquetas jamon"
        $lineaProd = sprintf("%2dx  %s\n", $cantidad, $nombre);
        $printer->text($lineaProd);

        // si hay comentario, lo imprimimos en la línea de abajo, sangrado
        if ($comentario !== "") {
            $comentario = quitaTildes($comentario);
            $printer->setEmphasis(true);
            $printer->text("    -> " . $comentario . "\n");
            $printer->setEmphasis(false);
        }
    }

    $printer->text(str_repeat("=", 32) . "\n");
    $printer->setJustification(Printer::JUSTIFY_CENTER);
    $printer->text("FIN PEDIDO COCINA\n");
    $printer->text("\n\n");

    // Cortar y cerrar
    $printer->cut();
    $printer->close();
} catch (Exception $e) {
    if (isset($printer)) {
        $printer->close();
    }
    echo "Error al imprimir ticket de cocina: " . $e->getMessage();
    exit;
}

mysqli_close($conn);

// puedes redirigir de vuelta donde quieras
header("LOCATION: pedidos.php");
exit;
