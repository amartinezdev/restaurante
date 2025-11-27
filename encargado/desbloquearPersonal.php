<?php
session_start();

// funcionalidad para bloquear el producto
include("seguridad.php");
include("../components/conexion.php");

$id = $_GET["id"];

$consulta = "UPDATE usuario
SET estado = 0
WHERE dni = '$id'";

$result = mysqli_query($conn, $consulta);

mysqli_close($conn);

header("LOCATION: /encargado/personal.php");
exit;
