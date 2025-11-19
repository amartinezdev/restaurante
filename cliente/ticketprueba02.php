<?php
session_start();

include("../components/conexion.php");
include("seguridad.php");

// quita las tildes en el ticket
function quitaTildes($texto)
{
    $originales = array("á", "é", "í", "ó", "ú", "Á", "É", "Í", "Ó", "Ú", "ñ", "Ñ");
    $reemplazos = array("a", "e", "i", "o", "u", "A", "E", "I", "O", "U", "n", "N");
    return str_replace($originales, $reemplazos, $texto);
}

if (!isset($_GET['idPedido'])) {
    die("Falta idPedido");
}

$idPedido = (int) $_GET['idPedido'];

// datos del pedido
$consultaPedido = mysqli_query($conn, "SELECT * FROM pedido WHERE id = '$idPedido'");
$pedido = mysqli_fetch_assoc($consultaPedido);
if (!$pedido) {
    die("Pedido no encontrado");
}

$fechaPedido = $pedido["fecha"];
$numMesa     = $pedido["numMesa"];

// productos del pedido (sin JOIN)
$consultaLineas = mysqli_query(
    $conn,
    "SELECT idProducto, cant, comentario
     FROM producto_pedido
     WHERE idPedido = '$idPedido' AND servido = 0"
);

// ====== CONSTRUIMOS EL "TICKET" EN UN STRING ======
$ticket = "";

// CABECERA
$ticket .= "COCINA\n";
$ticket .= str_repeat("-", 32) . "\n";

// MESA “EN GRANDE” (aquí solo texto, pero así ves el orden)
$ticket .= "MESA " . $numMesa . "\n";

$num_pedido = str_pad($idPedido, 4, "0", STR_PAD_LEFT);
$ticket .= "PEDIDO #" . $num_pedido . "\n";

$ticket .= $fechaPedido . "\n";
$ticket .= str_repeat("=", 32) . "\n";

// LISTA DE PRODUCTOS
$ticket .= "CANT  PRODUCTO\n";
$ticket .= str_repeat("-", 32) . "\n";

while ($linea = mysqli_fetch_assoc($consultaLineas)) {
    $idProducto = (int) $linea["idProducto"];
    $cantidad   = (int) $linea["cant"];
    $comentario = trim($linea["comentario"] ?? "");

    // obtenemos el nombre del producto
    $consultaProd = mysqli_query(
        $conn,
        "SELECT nombre FROM producto WHERE id = '$idProducto'"
    );
    $rowProd = mysqli_fetch_assoc($consultaProd);

    if (!$rowProd) {
        $nombre = "Producto ID $idProducto";
    } else {
        $nombre = quitaTildes($rowProd["nombre"]);
    }

    // línea principal: " 2x  Croquetas jamon"
    $ticket .= sprintf("%2dx  %s\n", $cantidad, $nombre);

    // si hay comentario, línea debajo sangrada
    if ($comentario !== "") {
        $ticket .= "    -> " . quitaTildes($comentario) . "\n";
    }
}

$ticket .= str_repeat("=", 32) . "\n";
$ticket .= "FIN PEDIDO COCINA\n";
$ticket .= "\n";

// CERRAMOS CONEXIÓN
mysqli_close($conn);

// MOSTRAMOS EN PANTALLA (modo texto)
header("Content-Type: text/plain; charset=UTF-8");
echo $ticket;
