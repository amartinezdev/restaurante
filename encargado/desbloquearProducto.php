<?php
session_start();

// funcionalidad para desbloquear el producto
if (!isset($_SESSION["usuario"]) || $_SESSION["rol"] != 2) {
    header("LOCATION: ../index.php");
    exit;
}

include("../components/conexion.php");

$producto = $_GET["id"];

$consulta = "UPDATE producto
SET estado = 1
WHERE id = '$producto'";

$result = mysqli_query($conn, $consulta);

mysqli_close($conn);

header("LOCATION: /restaurante/encargado/encargado.php");
exit;
