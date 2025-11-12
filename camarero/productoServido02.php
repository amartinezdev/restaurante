<?php

include("../components/conexion.php");
// include("seguridad.php");


$idProducto = $_GET["idPro"];
$idPedido = $_GET["idPed"];

$consulta = "UPDATE producto_pedido
            SET servido = 1
            WHERE idPedido = '$idPedido' AND idProducto = '$idProducto'";

$result = mysqli_query($conn, $consulta);

header("LOCATION: pedidos.php");
exit;
