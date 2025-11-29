<?php
session_start();

// funcionalidad para bloquear el producto
include("seguridad.php");

include("../components/conexion.php");

$producto = $_GET["id"];

$consulta = "UPDATE producto
SET estado = 0
WHERE id = '$producto'";

$result = mysqli_query($conn, $consulta);

mysqli_close($conn);

header("LOCATION: " . BASE_PATH . "/encargado/encargado.php");
exit;
