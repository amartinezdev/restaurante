<?php

include("../components/conexion.php");
// include("seguridad.php");


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $idPedido = $_POST["id"];

    $consulta = mysqli_query($conn, "UPDATE pedido SET estado = 1 WHERE id = '$idPedido'");
    $consulta2 = mysqli_query($conn, "UPDATE producto_pedido SET servido = 1 WHERE idPedido = '$idPedido'");

    echo mysqli_error($conn);

    header("LOCATION: pedidos.php");
    exit;
}
