<?php
session_start();

include("../components/conexion.php");
include("seguridad.php");

$idPedido = $_GET["idPedido"];


$consulta = mysqli_query($conn, "UPDATE pedido SET estado = 2 WHERE id = '$idPedido'");

$numMesaLibre = mysqli_query($conn, "UPDATE mesa SET estado = 1 WHERE numMesa = 
                                        (SELECT numMesa FROM pedido WHERE id = '$idPedido')");

$consulta2 = mysqli_query($conn, "DELETE FROM reserva WHERE numMesa = 
                                        (SELECT numMesa FROM pedido WHERE id = '$idPedido')");



mysqli_close($conn);

header("LOCATION: cobros.php");
exit;
