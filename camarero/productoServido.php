<?php

include("../components/conexion.php");
// include("seguridad.php");


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $idProducto = $_POST["id"];

    $consulta = mysqli_query($conn, "UPDATE pedido SET estado = 1 WHERE id = '$idProducto'");

    echo mysqli_error($conn);

    header("LOCATION: pedidos.php");
    exit;
}
